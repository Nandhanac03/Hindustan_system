<x-erp-layout title="Salary Head">
<div class="space-y-6 p-6" style="font-family: 'Plus Jakarta Sans', sans-serif;" x-data="{
    openAdd: false,
    openEdit: false,
    openDeleteModal: false,
    deleteAction: '',
    deleteName: '',
    addFromDate: '',
    addToDate: '',
    editItem: { id: null, name: '', financial_period_id: '', from_date: '', to_date: '' },
    openAddModal() {
        this.addFromDate = '';
        this.addToDate = '';
        this.openAdd = true;
    },
    initEdit(item) {
        this.editItem = {
            id: item.id,
            name: item.name || '',
            financial_period_id: item.financial_period_id || '',
            from_date: item.from_date ? String(item.from_date).substring(0, 10) : '',
            to_date: item.to_date ? String(item.to_date).substring(0, 10) : ''
        };
        this.openEdit = true;
    },
    openDeleteConfirm(actionUrl, name) {
        this.deleteAction = actionUrl;
        this.deleteName = name || 'this salary head';
        this.openDeleteModal = true;
    }
}">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Salary Head</h1>
            <p class="text-xs text-slate-500 font-medium">Create monthly salary heads with financial year and date range</p>
        </div>
        @if(auth()->user()->hasModulePermission('payroll.salary_head', 'create'))
        <button type="button" @click="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-md transition cursor-pointer">
            <span>+ Add Salary Head</span>
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
                        <th class="px-4 py-3.5 erp-table-header w-14 text-center">SL</th>
                        <th class="px-4 py-3.5 erp-table-header">Title</th>
                        <th class="px-4 py-3.5 erp-table-header">Financial Year</th>
                        <th class="px-4 py-3.5 erp-table-header">From Date</th>
                        <th class="px-4 py-3.5 erp-table-header">To Date</th>
                        <th class="px-4 py-3.5 erp-table-header text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($heads as $idx => $h)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-center text-slate-500 font-bold">{{ $idx + 1 }}</td>
                            <td class="px-4 py-3 font-bold text-slate-900">
                                <a href="{{ route('payroll.salary.index', ['head_id' => $h->id]) }}" class="hover:text-[#a38c29] hover:underline">{{ $h->name }}</a>
                            </td>
                            <td class="px-4 py-3 font-semibold text-slate-700">{{ $h->financialPeriod?->name ?: '—' }}</td>
                            <td class="px-4 py-3 font-mono text-slate-600">{{ $h->from_date ? $h->from_date->format('d-m-Y') : '—' }}</td>
                            <td class="px-4 py-3 font-mono text-slate-600">{{ $h->to_date ? $h->to_date->format('d-m-Y') : '—' }}</td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    @if(auth()->user()->hasModulePermission('payroll.salary_head', 'edit'))
                                    <button type="button" @click="initEdit({{ $h->toJson() }})" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Edit Salary Head">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    @endif
                                    @if(auth()->user()->hasModulePermission('payroll.salary_head', 'delete'))
                                    <button type="button" @click="openDeleteConfirm('{{ route('payroll.salary-head.destroy', $h->id) }}', {{ Js::from($h->name) }})" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Delete Salary Head">
                                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400 italic">No salary heads created yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Modal --}}
    <div x-show="openAdd" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none" @click.outside="openAdd = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                <h3 class="font-black text-sm uppercase tracking-wider text-white">Add New Salary Head</h3>
                <button type="button" @click="openAdd = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <form action="{{ route('payroll.salary-head.store') }}" method="POST" class="p-6 space-y-4 text-xs font-sans">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Title *</label>
                        <input type="text" name="name" required placeholder="Enter Title" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Financial Year</label>
                        <select name="financial_period_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                            <option value="">Select Financial Year...</option>
                            @foreach($periods as $p)
                                <option value="{{ $p->id }}" {{ (int)$defaultPeriodId === (int)$p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">From Date *</label>
                        <input type="date" name="from_date" required x-model="addFromDate" @change="if (addToDate && addToDate < addFromDate) addToDate = addFromDate"
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">To Date *</label>
                        <input type="date" name="to_date" required x-model="addToDate" :min="addFromDate" :disabled="!addFromDate"
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900 disabled:opacity-60">
                        <p x-show="!addFromDate" class="text-[10px] text-slate-400 mt-1">Select From Date first</p>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="openAdd = false" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold uppercase cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase cursor-pointer">Save</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div x-show="openEdit" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none" @click.outside="openEdit = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                <h3 class="font-black text-sm uppercase tracking-wider text-white">Edit Salary Head</h3>
                <button type="button" @click="openEdit = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <form :action="'{{ url('/payroll/salary-head') }}/' + editItem.id + '/update'" method="POST" class="p-6 space-y-4 text-xs font-sans">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Title *</label>
                        <input type="text" name="name" required x-model="editItem.name" placeholder="Enter Title" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Financial Year</label>
                        <select name="financial_period_id" x-model="editItem.financial_period_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                            <option value="">Select Financial Year...</option>
                            @foreach($periods as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">From Date *</label>
                        <input type="date" name="from_date" required x-model="editItem.from_date" @change="if (editItem.to_date && editItem.to_date < editItem.from_date) editItem.to_date = editItem.from_date"
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">To Date *</label>
                        <input type="date" name="to_date" required x-model="editItem.to_date" :min="editItem.from_date"
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="openEdit = false" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold uppercase cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase cursor-pointer">Update</button>
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
                    <h3 class="font-black text-base uppercase tracking-wider text-white">DELETE SALARY HEAD</h3>
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
