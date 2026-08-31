<x-erp-layout title="Voucher Type Master">
<div class="space-y-6 p-6" x-data="{
    openAddModal: false,
    openEditModal: false,
    openDeleteModal: false,
    openViewModal: false,
    viewVoucher: { id: null, code: '', name: '', prefix: '', description: '', is_active: true },
    editVoucher: { id: null, code: '', name: '', prefix: '', description: '', is_active: true },
    deleteVoucher: { id: null, name: '' },
    initView(v) {
        this.viewVoucher = { ...v };
        this.openViewModal = true;
    },
    initEdit(v) {
        this.editVoucher = { ...v };
        this.openEditModal = true;
    },
    initDelete(v) {
        this.deleteVoucher = { ...v };
        this.openDeleteModal = true;
    }
}">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Voucher Type Master</h1>
                    <p class="text-xs text-slate-500 font-medium">Configure dynamic voucher types, prefixes, and auto-numbering rules</p>
                </div>
            </div>
        </div>
        <div>
            <button @click="openAddModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl shadow-md transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Voucher Type
            </button>
        </div>
    </div>

    <!-- Flash Success Message -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center gap-3 shadow-xs">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Voucher Types</p>
            <p class="text-2xl font-black text-slate-900 mt-1">{{ $totalCount }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-emerald-200 shadow-xs">
            <p class="text-[11px] font-bold text-emerald-500 uppercase tracking-wider">Active Types</p>
            <p class="text-2xl font-black text-emerald-700 mt-1">{{ $activeCount }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-rose-200 shadow-xs">
            <p class="text-[11px] font-bold text-rose-500 uppercase tracking-wider">Inactive Types</p>
            <p class="text-2xl font-black text-rose-700 mt-1">{{ $inactiveCount }}</p>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('voucher-types.index') }}" class="flex items-center gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Code, Display Name, Prefix, or Description..." class="w-full px-3.5 py-2 text-xs border border-slate-250 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a38c29]">
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
                Search
            </button>
            @if(request('search'))
            <a href="{{ route('voucher-types.index') }}" class="px-3 py-2 text-xs text-slate-500 hover:text-slate-700 font-bold">Reset</a>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[10px] font-black uppercase tracking-wider text-left">
                        <th class="px-4 py-3.5">ID</th>
                        <th class="px-4 py-3.5">CODE</th>
                        <th class="px-4 py-3.5">DISPLAY NAME</th>
                        <th class="px-4 py-3.5">PREFIX</th>
                        <th class="px-4 py-3.5">DESCRIPTION</th>
                        <th class="px-4 py-3.5 text-center">STATUS</th>
                        <th class="px-4 py-3.5 text-right pr-4">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($voucherTypes as $v)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3.5 font-bold text-slate-400 font-mono">#{{ $v->id }}</td>
                        <td class="px-4 py-3.5 font-bold font-mono text-[#a38c29]">{{ $v->code }}</td>
                        <td class="px-4 py-3.5 font-semibold text-slate-900">{{ $v->name }}</td>
                        <td class="px-4 py-3.5 font-mono">
                            <span class="px-2 py-1 bg-slate-100 rounded text-slate-700 font-bold border border-slate-200 text-[11px]">{{ $v->prefix }}</span>
                        </td>
                        <td class="px-4 py-3.5 text-slate-500 max-w-xs truncate">{{ $v->description ?: '—' }}</td>
                        <td class="px-4 py-3.5 text-center">
                            @if($v->is_active)
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                            @else
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-500 border border-slate-200">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-right pr-4 whitespace-nowrap">
                            <div class="inline-flex items-center justify-end gap-1.5">
                                <button type="button" @click="openViewModalFn({{ json_encode($v) }})" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-xs" title="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <button type="button" @click="openEditModalFn({{ json_encode($v) }})" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-xs" title="Edit Voucher Type">
                                    <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-slate-400 italic">No voucher types configured.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($voucherTypes->hasPages())
        <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
            {{ $voucherTypes->links() }}
        </div>
        @endif
    </div>

    <!-- View Modal -->
    <div x-show="openViewModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.outside="openViewModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">VOUCHER TYPES MASTER</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">VOUCHER TYPE DETAILS</h3>
                </div>
                <button type="button" @click="openViewModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <div class="p-6 space-y-3.5 text-xs">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">SYSTEM CODE</span>
                    <span class="font-bold font-mono text-[#a38c29]" x-text="viewVoucher.code"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">DISPLAY NAME</span>
                    <span class="font-bold text-slate-900" x-text="viewVoucher.name"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">PREFIX</span>
                    <span class="font-bold font-mono bg-slate-100 px-2.5 py-1 rounded border border-slate-200 text-slate-800" x-text="viewVoucher.prefix"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">DESCRIPTION</span>
                    <span class="font-bold text-slate-700 text-right max-w-[200px]" x-text="viewVoucher.description || '—'"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">ACTIVE STATUS</span>
                    <span class="font-bold" :class="viewVoucher.is_active ? 'text-emerald-600' : 'text-slate-500'" x-text="viewVoucher.is_active ? 'Active' : 'Inactive'"></span>
                </div>
                <div class="flex justify-end pt-3">
                    <button type="button" @click="openViewModal = false" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">CLOSE</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div x-show="openAddModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.outside="openAddModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">VOUCHER TYPES MASTER</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">ADD VOUCHER TYPE</h3>
                </div>
                <button type="button" @click="openAddModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <form action="{{ route('voucher-types.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">VOUCHER CODE (UNIQUE) <span class="text-rose-500 font-bold">*</span></label>
                    <input type="text" name="code" required placeholder="e.g., PETTY_CASH_CONTRA" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold uppercase text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DISPLAY NAME <span class="text-rose-500 font-bold">*</span></label>
                    <input type="text" name="name" required placeholder="e.g., Petty Cash Contra Transfer" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">PREFIX <span class="text-rose-500 font-bold">*</span></label>
                    <input type="text" name="prefix" required placeholder="e.g., JV-PC" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold uppercase text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DESCRIPTION</label>
                    <textarea name="description" rows="2" placeholder="Generated on..." class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none"></textarea>
                </div>
                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_active" id="vt_add_active" value="1" checked class="w-4 h-4 rounded border-slate-300 text-[#a38c29] focus:ring-[#a38c29]">
                    <label for="vt_add_active" class="text-xs font-bold text-slate-700">Active Voucher Type Status</label>
                </div>
                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="openAddModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">SAVE VOUCHER TYPE</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="openEditModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.outside="openEditModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">VOUCHER TYPES MASTER</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">EDIT VOUCHER TYPE</h3>
                </div>
                <button type="button" @click="openEditModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <form :action="'/voucher-types/' + editVoucher.id" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">VOUCHER CODE (UNIQUE) <span class="text-rose-500 font-bold">*</span></label>
                    <input type="text" name="code" x-model="editVoucher.code" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold uppercase text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DISPLAY NAME <span class="text-rose-500 font-bold">*</span></label>
                    <input type="text" name="name" x-model="editVoucher.name" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">PREFIX <span class="text-rose-500 font-bold">*</span></label>
                    <input type="text" name="prefix" x-model="editVoucher.prefix" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold uppercase text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DESCRIPTION</label>
                    <textarea name="description" x-model="editVoucher.description" rows="2" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none"></textarea>
                </div>
                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_active" id="vt_edit_active" value="1" :checked="editVoucher.is_active" class="w-4 h-4 rounded border-slate-300 text-[#a38c29] focus:ring-[#a38c29]">
                    <label for="vt_edit_active" class="text-xs font-bold text-slate-700">Active Voucher Type Status</label>
                </div>
                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="openEditModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">UPDATE VOUCHER TYPE</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="openDeleteModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.outside="openDeleteModal = false">
            <div class="bg-rose-950 p-5 text-white flex items-center justify-between border-b border-rose-900">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-rose-900/40 text-rose-200 text-[9px] font-black uppercase tracking-wider rounded border border-rose-800 mb-1">CONFIRMATION</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">DELETE VOUCHER TYPE</h3>
                </div>
                <button type="button" @click="openDeleteModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <div class="p-6 space-y-4 text-center">
                <p class="text-xs font-semibold text-slate-600">Are you sure you want to delete <span class="font-bold text-slate-900" x-text="deleteVoucher.name"></span>?</p>
                <form :action="'/voucher-types/' + deleteVoucher.id" method="POST" class="flex justify-center gap-3 pt-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="openDeleteModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">CONFIRM DELETE</button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-erp-layout>
