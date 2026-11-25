<x-erp-layout title="Financial Period">
<div class="space-y-6 p-6" x-data="{
    openAdd: false,
    openEdit: false,
    openDeleteModal: false,
    deleteAction: '',
    deleteName: '',
    editItem: { id: null, name: '', start_date: '', end_date: '', working_days: 26, is_current: false, status: 'active', notes: '' },
    openDeleteConfirm(actionUrl, name) {
        this.deleteAction = actionUrl;
        this.deleteName = name || 'this financial period';
        this.openDeleteModal = true;
    },
    initEdit(item) {
        this.editItem = {
            id: item.id,
            name: item.name,
            start_date: item.start_date ? String(item.start_date).substring(0, 10) : '',
            end_date: item.end_date ? String(item.end_date).substring(0, 10) : '',
            working_days: item.working_days || 26,
            is_current: !!item.is_current,
            status: item.status || 'active',
            notes: item.notes || ''
        };
        this.openEdit = true;
    }
}">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Financial Period</h1>
            <p class="text-xs text-slate-500 font-medium">Configure payroll financial periods and date ranges</p>
        </div>
        @if(auth()->user()->hasModulePermission('payroll.financial_period', 'create'))
        <button type="button" @click="openAdd = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-md transition cursor-pointer">
            <span>+ Add Financial Period</span>
        </button>
        @endif
    </div>

    @if(session('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-bold">{{ $errors->first() }}</div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5 erp-table-header w-14 text-center">#</th>
                        <th class="px-4 py-3.5 erp-table-header">Period Name</th>
                        <th class="px-4 py-3.5 erp-table-header">Start Date</th>
                        <th class="px-4 py-3.5 erp-table-header">End Date</th>
                        <th class="px-4 py-3.5 erp-table-header text-center">Current</th>
                        <th class="px-4 py-3.5 erp-table-header text-center">Status</th>
                        <th class="px-4 py-3.5 erp-table-header text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($periods as $idx => $p)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-center text-slate-400 font-bold">{{ $idx + 1 }}</td>
                            <td class="px-4 py-3 font-bold text-slate-900">{{ $p->name }}</td>
                            <td class="px-4 py-3 font-mono text-slate-600">{{ $p->start_date->format('d M Y') }}</td>
                            <td class="px-4 py-3 font-mono text-slate-600">{{ $p->end_date->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($p->is_current)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#a38c29]/15 text-[#8a7522]">Current</span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $p->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ ucfirst($p->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    @if(auth()->user()->hasModulePermission('payroll.financial_period', 'edit'))
                                    <button type="button" @click="initEdit({{ $p->toJson() }})" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Edit Financial Period">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    @endif
                                    @if(auth()->user()->hasModulePermission('payroll.financial_period', 'delete'))
                                    <button type="button" @click="openDeleteConfirm('{{ route('payroll.financial-period.destroy', $p->id) }}', {{ Js::from($p->name) }})" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Delete Financial Period">
                                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400 italic">No financial periods configured yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Modal --}}
    <div x-show="openAdd" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden" @click.outside="openAdd = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                <h3 class="font-black text-sm uppercase tracking-wider">Add Financial Period</h3>
                <button type="button" @click="openAdd = false" class="text-white/80 hover:text-white font-bold cursor-pointer">✕</button>
            </div>
            <form action="{{ route('payroll.financial-period.store') }}" method="POST" class="p-6 space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-bold uppercase text-slate-600 mb-1">Period Name *</label>
                        <input type="text" name="name" required placeholder="e.g. FY 2026-27" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-600 mb-1">Start Date *</label>
                        <input type="date" name="start_date" required class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-600 mb-1">End Date *</label>
                        <input type="date" name="end_date" required class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-bold uppercase text-slate-600 mb-1">Status *</label>
                        <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                            <option value="active">Active</option>
                            <option value="closed">Closed</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="inline-flex items-center gap-2 font-bold text-slate-700 cursor-pointer">
                            <input type="checkbox" name="is_current" value="1" class="rounded border-slate-300">
                            <span>Set as Current Active Financial Period</span>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="openAdd = false" class="px-4 py-2 rounded-xl bg-slate-200 text-slate-800 font-bold uppercase cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white font-bold uppercase cursor-pointer">Save Period</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div x-show="openEdit" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden" @click.outside="openEdit = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                <h3 class="font-black text-sm uppercase tracking-wider">Edit Financial Period</h3>
                <button type="button" @click="openEdit = false" class="text-white/80 hover:text-white font-bold cursor-pointer">✕</button>
            </div>
            <form :action="'{{ url('/payroll/financial-period') }}/' + editItem.id + '/update'" method="POST" class="p-6 space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-bold uppercase text-slate-600 mb-1">Period Name *</label>
                        <input type="text" name="name" required x-model="editItem.name" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-600 mb-1">Start Date *</label>
                        <input type="date" name="start_date" required x-model="editItem.start_date" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-600 mb-1">End Date *</label>
                        <input type="date" name="end_date" required x-model="editItem.end_date" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-bold uppercase text-slate-600 mb-1">Status *</label>
                        <select name="status" x-model="editItem.status" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                            <option value="active">Active</option>
                            <option value="closed">Closed</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="inline-flex items-center gap-2 font-bold text-slate-700 cursor-pointer">
                            <input type="checkbox" name="is_current" value="1" x-model="editItem.is_current" class="rounded border-slate-300">
                            <span>Set as Current Active Financial Period</span>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="openEdit = false" class="px-4 py-2 rounded-xl bg-slate-200 text-slate-800 font-bold uppercase cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white font-bold uppercase cursor-pointer">Update Period</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="openDeleteModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none transform transition-all" @click.outside="openDeleteModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">CONFIRMATION</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">DELETE PERIOD</h3>
                </div>
                <button type="button" @click="openDeleteModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <div class="p-6 space-y-4 text-center font-sans">
                <p class="text-xs font-semibold text-slate-600">Are you sure you want to remove <span class="font-bold text-slate-900" x-text="deleteName"></span>?</p>
                <form :action="deleteAction" method="POST" class="flex justify-center gap-3 pt-2">
                    @csrf
                    <button type="button" @click="openDeleteModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">CONFIRM DELETE</button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-erp-layout>
