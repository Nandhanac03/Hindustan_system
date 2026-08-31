<x-erp-layout title="Chart of Accounts Master">
<div class="space-y-6 p-6" x-data="{
    openAddModal: false,
    openEditModal: false,
    openDeleteModal: false,
    openViewModal: false,
    viewAccount: { id: null, account_code: '', account_name: '', account_type: 'ASSET', is_active: true },
    editAccount: { id: null, account_code: '', account_name: '', account_type: 'ASSET', is_active: true },
    deleteAccount: { id: null, account_name: '' },
    initView(acc) {
        this.viewAccount = { ...acc };
        this.openViewModal = true;
    },
    initEdit(acc) {
        this.editAccount = { ...acc };
        this.openEditModal = true;
    },
    initDelete(acc) {
        this.deleteAccount = { ...acc };
        this.openDeleteModal = true;
    }
}">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-amber-500/10 text-amber-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Chart of Accounts Master</h1>
                    <p class="text-xs text-slate-500 font-medium">Manage accounting head categories (Assets, Liabilities, Revenue & Expenses)</p>
                </div>
            </div>
        </div>
        <div>
            <button @click="openAddModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl shadow-md transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Account Head
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Accounts</p>
            <p class="text-2xl font-black text-slate-900 mt-1">{{ $totalAccounts }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-blue-200 shadow-xs">
            <p class="text-[11px] font-bold text-blue-500 uppercase tracking-wider">Assets</p>
            <p class="text-2xl font-black text-blue-700 mt-1">{{ $assetCount }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-amber-200 shadow-xs">
            <p class="text-[11px] font-bold text-amber-500 uppercase tracking-wider">Liabilities</p>
            <p class="text-2xl font-black text-amber-700 mt-1">{{ $liabilityCount }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-emerald-200 shadow-xs">
            <p class="text-[11px] font-bold text-emerald-500 uppercase tracking-wider">Revenue</p>
            <p class="text-2xl font-black text-emerald-700 mt-1">{{ $revenueCount }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-rose-200 shadow-xs">
            <p class="text-[11px] font-bold text-rose-500 uppercase tracking-wider">Expenses</p>
            <p class="text-2xl font-black text-rose-700 mt-1">{{ $expenseCount }}</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('chart-of-accounts.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[240px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Code or Account Name..." class="w-full px-3.5 py-2 text-xs border border-slate-250 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a38c29]">
            </div>
            <div class="w-48">
                <select name="account_type" class="w-full px-3 py-2 text-xs border border-slate-250 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a38c29]">
                    <option value="">All Account Types</option>
                    <option value="ASSET" {{ request('account_type') === 'ASSET' ? 'selected' : '' }}>ASSET</option>
                    <option value="LIABILITY" {{ request('account_type') === 'LIABILITY' ? 'selected' : '' }}>LIABILITY</option>
                    <option value="REVENUE" {{ request('account_type') === 'REVENUE' ? 'selected' : '' }}>REVENUE</option>
                    <option value="EXPENSE" {{ request('account_type') === 'EXPENSE' ? 'selected' : '' }}>EXPENSE</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
                Filter Results
            </button>
            @if(request('search') || request('account_type'))
            <a href="{{ route('chart-of-accounts.index') }}" class="px-3 py-2 text-xs text-slate-500 hover:text-slate-700 font-bold">Reset</a>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[10px] font-black uppercase tracking-wider text-left">
                        <th class="px-4 py-3.5">ACCOUNT CODE</th>
                        <th class="px-4 py-3.5">ACCOUNT NAME</th>
                        <th class="px-4 py-3.5">ACCOUNT TYPE</th>
                        <th class="px-4 py-3.5 text-center">STATUS</th>
                        <th class="px-4 py-3.5 text-right pr-4">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($accounts as $acc)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3.5 font-bold font-mono text-slate-800">
                            {{ $acc->account_code }}
                        </td>
                        <td class="px-4 py-3.5 font-semibold text-slate-900">
                            {{ $acc->account_name }}
                        </td>
                        <td class="px-4 py-3.5">
                            @if($acc->account_type === 'ASSET')
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded-full font-bold text-[10px]">ASSET</span>
                            @elseif($acc->account_type === 'LIABILITY')
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full font-bold text-[10px]">LIABILITY</span>
                            @elseif($acc->account_type === 'REVENUE')
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px]">REVENUE</span>
                            @else
                                <span class="px-2.5 py-1 bg-rose-100 text-rose-800 rounded-full font-bold text-[10px]">EXPENSE</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <form action="{{ route('chart-of-accounts.toggle-status', $acc->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" title="Click to toggle status" class="px-2.5 py-1 rounded-full text-[10px] font-bold cursor-pointer transition {{ $acc->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                    {{ $acc->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3.5 text-right pr-4">
                            <div class="inline-flex items-center justify-end gap-1.5">
                                <button @click="initView({ id: {{ $acc->id }}, account_code: '{{ addslashes($acc->account_code) }}', account_name: '{{ addslashes($acc->account_name) }}', account_type: '{{ $acc->account_type }}', is_active: {{ $acc->is_active ? 'true' : 'false' }} })" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <button @click="initEdit({ id: {{ $acc->id }}, account_code: '{{ addslashes($acc->account_code) }}', account_name: '{{ addslashes($acc->account_name) }}', account_type: '{{ $acc->account_type }}', is_active: {{ $acc->is_active ? 'true' : 'false' }} })" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm" title="Edit Account">
                                    <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-400 font-medium">
                            No Chart of Accounts found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($accounts->hasPages())
        <div class="px-4 py-3 bg-slate-50 border-t border-slate-200">
            {{ $accounts->links() }}
        </div>
        @endif
    </div>

    <!-- View Modal -->
    <div x-show="openViewModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden flex flex-col" @click.outside="openViewModal = false">
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">CHART OF ACCOUNTS</p>
                        <h2 class="text-lg font-extrabold text-white">Account Head Details</h2>
                    </div>
                    <button type="button" @click="openViewModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <div class="p-6 space-y-3.5 text-xs bg-white">
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">ACCOUNT CODE</span>
                    <span class="font-bold font-mono text-slate-900 text-sm" x-text="viewAccount.account_code"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">ACCOUNT NAME</span>
                    <span class="font-bold text-slate-900" x-text="viewAccount.account_name"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">ACCOUNT TYPE</span>
                    <span class="font-bold text-[#a38c29]" x-text="viewAccount.account_type"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">ACTIVE STATUS</span>
                    <span class="font-bold" :class="viewAccount.is_active ? 'text-emerald-600' : 'text-slate-500'" x-text="viewAccount.is_active ? 'Active' : 'Inactive'"></span>
                </div>
                <div class="flex justify-end pt-3">
                    <button type="button" @click="openViewModal = false" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase rounded-xl transition shadow-sm cursor-pointer">CLOSE</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div x-show="openAddModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden flex flex-col" @click.outside="openAddModal = false">
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">CHART OF ACCOUNTS</p>
                        <h2 class="text-lg font-extrabold text-white">Add New Account Head</h2>
                    </div>
                    <button type="button" @click="openAddModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <form action="{{ route('chart-of-accounts.store') }}" method="POST" class="flex flex-col">
                <div class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">ACCOUNT CODE <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="account_code" required placeholder="e.g., 1005" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">ACCOUNT NAME <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="account_name" required placeholder="e.g., Office Reserve Fund" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">ACCOUNT TYPE <span class="text-rose-500 font-bold">*</span></label>
                        <select name="account_type" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29]">
                            <option value="ASSET">ASSET</option>
                            <option value="LIABILITY">LIABILITY</option>
                            <option value="REVENUE">REVENUE</option>
                            <option value="EXPENSE">EXPENSE</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" name="is_active" id="add_is_active" value="1" checked class="w-4 h-4 rounded border-slate-300 text-[#a38c29] focus:ring-[#a38c29]">
                        <label for="add_is_active" class="text-xs font-bold text-slate-700">Active Account Status</label>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                    <button type="button" @click="openAddModal = false" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl uppercase transition shadow-sm cursor-pointer">SAVE ACCOUNT</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="openEditModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden flex flex-col" @click.outside="openEditModal = false">
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">CHART OF ACCOUNTS</p>
                        <h2 class="text-lg font-extrabold text-white">Edit Account Head</h2>
                    </div>
                    <button type="button" @click="openEditModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <form :action="'/chart-of-accounts/' + editAccount.id" method="POST" class="flex flex-col">
                <div class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">ACCOUNT CODE <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="account_code" x-model="editAccount.account_code" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">ACCOUNT NAME <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="account_name" x-model="editAccount.account_name" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">ACCOUNT TYPE <span class="text-rose-500 font-bold">*</span></label>
                        <select name="account_type" x-model="editAccount.account_type" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29]">
                            <option value="ASSET">ASSET</option>
                            <option value="LIABILITY">LIABILITY</option>
                            <option value="REVENUE">REVENUE</option>
                            <option value="EXPENSE">EXPENSE</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" :checked="editAccount.is_active" class="w-4 h-4 rounded border-slate-300 text-[#a38c29] focus:ring-[#a38c29]">
                        <label for="edit_is_active" class="text-xs font-bold text-slate-700">Active Account Status</label>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                    <button type="button" @click="openEditModal = false" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl uppercase transition shadow-sm cursor-pointer">UPDATE ACCOUNT</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="openDeleteModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden flex flex-col" @click.outside="openDeleteModal = false">
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-rose-950 to-rose-900 px-6 py-5 flex-shrink-0 border-b border-rose-800">
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-rose-300 text-[10px] font-semibold uppercase tracking-widest mb-1">CONFIRMATION</p>
                        <h2 class="text-lg font-extrabold text-white">Delete Account Head</h2>
                    </div>
                    <button type="button" @click="openDeleteModal = false" class="text-rose-200 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <div class="p-6 space-y-4 text-center bg-white">
                <p class="text-xs font-semibold text-slate-600">Are you sure you want to delete <span class="font-bold text-slate-900" x-text="deleteAccount.account_name"></span>?</p>
                <form :action="'/chart-of-accounts/' + deleteAccount.id" method="POST" class="flex justify-center gap-3 pt-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="openDeleteModal = false" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl uppercase transition shadow-sm cursor-pointer">CONFIRM DELETE</button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-erp-layout>
