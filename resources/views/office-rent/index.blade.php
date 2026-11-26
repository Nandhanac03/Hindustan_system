<x-erp-layout title="Office Rent Management">
@php
    $rentMap = [];
    foreach ($rents as $r) {
        $rentMap[$r->id] = [
            'id' => $r->id,
            'office_location' => $r->office_location,
            'landlord_name' => $r->landlord_name,
            'rent_year' => (string) $r->rent_year,
            'rent_period_label' => $r->rent_period_label,
            'gross_rent_amount' => (float) $r->gross_rent_amount,
            'tds_amount' => (float) $r->tds_amount,
            'net_payable_amount' => (float) $r->net_payable_amount,
            'payment_status' => $r->payment_status,
            'voucher_date' => $r->voucher_date ? $r->voucher_date->format('d/m/Y') : '—',
            'voucher_no' => $r->journalVoucher?->voucher_no ?? '—',
            'payment_voucher_no' => $r->paymentVoucher?->voucher_no ?? null,
            'bank_name' => $r->companyBankAccount?->bank_name ?? null,
            'account_number' => $r->companyBankAccount?->account_number ?? null,
            'payment_date' => $r->payment_date ? $r->payment_date->format('d/m/Y') : '—',
            'payment_reference' => $r->payment_reference ?: '—',
            'remarks' => $r->remarks ?: '—',
        ];
    }
@endphp
<script>
    window.__officeRentMap = @json($rentMap);
</script>
<div class="space-y-6 p-4 sm:p-6" style="font-family: 'Plus Jakarta Sans', sans-serif;" x-data="{
    openAdd: false,
    openView: false,
    openPay: false,
    openDeleteModal: false,
    viewItem: { id: null, office_location: '', landlord_name: '', rent_period_label: '', gross_rent_amount: 0, tds_amount: 0, net_payable_amount: 0, payment_status: '', voucher_date: '', voucher_no: '', payment_voucher_no: null, bank_name: null, account_number: null, payment_date: '', payment_reference: '', remarks: '' },
    search: '',
    filterLocation: 'all',
    filterStatus: 'all',
    filterYear: 'all',
    rentMap: window.__officeRentMap || {},
    get rentList() {
        return Object.values(this.rentMap);
    },
    get filteredRents() {
        return this.rentList.filter(r => this.matchesFilters(r.office_location, r.payment_status, r.rent_year, r.landlord_name, r.rent_period_label));
    },
    get filteredGross() {
        return this.filteredRents.reduce((sum, r) => sum + Number(r.gross_rent_amount || 0), 0);
    },
    get filteredPaid() {
        return this.filteredRents.filter(r => r.payment_status === 'Paid').reduce((sum, r) => sum + Number(r.net_payable_amount || 0), 0);
    },
    get filteredUnpaid() {
        return this.filteredRents.filter(r => r.payment_status === 'Unpaid').reduce((sum, r) => sum + Number(r.net_payable_amount || 0), 0);
    },
    deleteAction: '',
    deleteName: '',
    addForm: {
        office_location: 'Corporate Office',
        landlord_name: '',
        rent_month: '{{ now()->month }}',
        rent_year: '{{ now()->year }}',
        gross_rent_amount: '',
        payment_status: 'Paid',
        company_bank_account_id: '{{ $bankAccounts->first()?->id ?? '' }}',
        payment_reference: '',
        voucher_date: '{{ now()->toDateString() }}',
        remarks: ''
    },
    payItem: {
        id: null,
        office_location: '',
        landlord_name: '',
        rent_period_label: '',
        net_payable_amount: 0,
        company_bank_account_id: '{{ $bankAccounts->first()?->id ?? '' }}',
        payment_date: '{{ now()->toDateString() }}',
        payment_reference: '',
        remarks: ''
    },
    get netPayablePreview() {
        const gross = parseFloat(this.addForm.gross_rent_amount || 0);
        const tds = parseFloat(this.addForm.tds_amount || 0);
        return Math.max(0, gross - tds);
    },
    formatMoney(val) {
        return '₹' + Number(val || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    },
    initView(id) {
        const item = this.rentMap[id];
        if (item) {
            this.viewItem = item;
            this.openView = true;
        }
    },
    initPay(id) {
        const item = this.rentMap[id];
        if (item) {
            this.payItem = {
                id: item.id,
                office_location: item.office_location,
                landlord_name: item.landlord_name,
                rent_period_label: item.rent_period_label,
                net_payable_amount: item.net_payable_amount,
                company_bank_account_id: '{{ $bankAccounts->first()?->id ?? '' }}',
                payment_date: '{{ now()->toDateString() }}',
                payment_reference: '',
                remarks: ''
            };
            this.openPay = true;
        }
    },
    openDeleteConfirm(url, label) {
        this.deleteAction = url;
        this.deleteName = label || 'this rent entry';
        this.openDeleteModal = true;
    },
    resetFilters() {
        this.search = '';
        this.filterLocation = 'all';
        this.filterStatus = 'all';
        this.filterYear = 'all';
    },
    matchesFilters(location, status, year, landlord, period) {
        if (this.filterLocation !== 'all' && location !== this.filterLocation) return false;
        if (this.filterStatus !== 'all' && status !== this.filterStatus) return false;
        if (this.filterYear !== 'all' && String(year) !== String(this.filterYear)) return false;
        const q = (this.search || '').trim().toLowerCase();
        if (!q) return true;
        return (location || '').toLowerCase().includes(q)
            || (landlord || '').toLowerCase().includes(q)
            || (period || '').toLowerCase().includes(q);
    }
}">
    {{-- Header --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-[#a38c29]/15 border border-[#a38c29]/30 flex items-center justify-center text-[#a38c29]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900">Office Rent Management</h1>
                <p class="text-xs text-slate-500 mt-0.5">Book office rent expenses and disburse payments via company bank accounts.</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(auth()->user()->hasModulePermission('office_rent.manage', 'excel'))
            <a :href="'{{ route('office-rent.export') }}?location=' + encodeURIComponent(filterLocation) + '&status=' + encodeURIComponent(filterStatus) + '&year=' + encodeURIComponent(filterYear)" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold uppercase tracking-wider shadow-sm transition inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Export Excel</span>
            </a>
            @endif
            @if(auth()->user()->hasModulePermission('office_rent.manage', 'create'))
            <button type="button" @click="openAdd = true" class="px-4 py-2.5 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-extrabold uppercase tracking-wider shadow-sm transition cursor-pointer inline-flex items-center gap-1.5">
                <span>+ Add Office Rent</span>
            </button>
            @endif
        </div>
    </div>

    @if(session('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-bold">{{ $errors->first() }}</div>
    @endif

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between gap-2 mb-3">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Total Office Rent</span>
                <span class="text-[9px] text-[#8a7522] font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase">Total</span>
            </div>
            <div>
                <span class="text-2xl font-black text-slate-900 font-mono" x-text="formatMoney(filteredGross)">₹{{ number_format($totalGross, 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1 font-medium"><span x-text="filteredRents.length">{{ $rents->count() }}</span> Rent Entries</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between gap-2 mb-3">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Paid Net Rent</span>
                <span class="text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase">Disbursed</span>
            </div>
            <div>
                <span class="text-2xl font-black text-emerald-700 font-mono" x-text="formatMoney(filteredPaid)">₹{{ number_format($totalPaid, 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1 font-medium">Paid via Company Bank</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between gap-2 mb-3">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Unpaid Payable</span>
                <span class="text-[9px] text-rose-700 font-bold bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200 uppercase">Pending</span>
            </div>
            <div>
                <span class="text-2xl font-black text-rose-600 font-mono" x-text="formatMoney(filteredUnpaid)">₹{{ number_format($totalUnpaid, 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1 font-medium">Booked Office Rent Payable</p>
            </div>
        </div>
    </div>

    {{-- Filters (Zero Page Reload / Zero URL Change) --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 items-center">
            <div class="relative">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model="search" placeholder="Search office, landlord, period..." class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-700 focus:outline-none focus:border-[#a38c29]">
            </div>
            <div>
                <select x-model="filterLocation" class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-700 focus:outline-none focus:border-[#a38c29]">
                    <option value="all">All Office Locations</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc }}">{{ $loc }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select x-model="filterStatus" class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-700 focus:outline-none focus:border-[#a38c29]">
                    <option value="all">All Statuses</option>
                    <option value="Paid">Paid</option>
                    <option value="Unpaid">Unpaid Payable</option>
                </select>
            </div>
            <div>
                <select x-model="filterYear" class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-700 focus:outline-none focus:border-[#a38c29]">
                    <option value="all">All Years</option>
                    @foreach($years as $yr)
                        <option value="{{ $yr }}">{{ $yr }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="button" @click="resetFilters()" class="w-full px-4 py-2.5 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-extrabold uppercase tracking-wider shadow-xs transition inline-flex items-center justify-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Reset Filters</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Rent Register Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5 erp-table-header w-12 text-center">#</th>
                        <th class="px-4 py-3.5 erp-table-header">Voucher Date</th>
                        <th class="px-4 py-3.5 erp-table-header">Office Location</th>
                        <th class="px-4 py-3.5 erp-table-header">Landlord / Payee</th>
                        <th class="px-4 py-3.5 erp-table-header">Rent Period</th>
                        <th class="px-4 py-3.5 erp-table-header text-right">Rent Amount (₹)</th>
                        <th class="px-4 py-3.5 erp-table-header text-center">Status &amp; Bank</th>
                        <th class="px-4 py-3.5 erp-table-header text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($rents as $idx => $rent)
                        <tr x-show="matchesFilters({{ Js::from($rent->office_location) }}, {{ Js::from($rent->payment_status) }}, {{ Js::from((string)$rent->rent_year) }}, {{ Js::from($rent->landlord_name) }}, {{ Js::from($rent->rent_period_label) }})" class="hover:bg-slate-50/70">
                            <td class="px-4 py-3 text-center text-slate-400 font-bold">{{ $idx + 1 }}</td>
                            <td class="px-4 py-3 font-mono text-slate-600">
                                {{ $rent->voucher_date ? $rent->voucher_date->format('d/m/Y') : '—' }}
                                @if($rent->journalVoucher)
                                    <div class="text-[9px] text-slate-400">{{ $rent->journalVoucher->voucher_no }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-bold text-slate-900">{{ $rent->office_location }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $rent->landlord_name }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-700">{{ $rent->rent_period_label }}</td>
                            <td class="px-4 py-3 text-right font-mono font-extrabold text-slate-900">₹{{ number_format((float)$rent->gross_rent_amount, 2) }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $rent->payment_status === 'Paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $rent->payment_status === 'Paid' ? 'Paid' : 'Unpaid Payable' }}
                                </span>
                                @if($rent->payment_status === 'Paid' && $rent->companyBankAccount)
                                    <div class="text-[9px] text-slate-400 font-semibold mt-0.5 truncate max-w-[150px] mx-auto" title="{{ $rent->companyBankAccount->bank_name }}">
                                        {{ $rent->companyBankAccount->bank_name }}{{ $rent->payment_reference ? ' • ' . $rent->payment_reference : '' }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    @if(auth()->user()->hasModulePermission('office_rent.manage', 'view'))
                                    <button type="button" @click="initView({{ $rent->id }})" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="View Office Rent Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    @endif
                                    @if($rent->payment_status === 'Unpaid' && auth()->user()->hasModulePermission('office_rent.manage', 'edit'))
                                    <button type="button" @click="initPay({{ $rent->id }})" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-extrabold uppercase tracking-wider transition inline-flex items-center gap-1 shadow-xs cursor-pointer" title="Pay Unpaid Rent via Bank">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        <span>Pay</span>
                                    </button>
                                    @endif
                                    @if(auth()->user()->hasModulePermission('office_rent.manage', 'delete'))
                                    <button type="button" @click="openDeleteConfirm('{{ route('office-rent.destroy', $rent->id) }}', {{ Js::from($rent->office_location . ' - ' . $rent->rent_period_label) }})" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Delete Rent Entry">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-slate-400 italic">No office rent entries found.</td>
                        </tr>
                    @endforelse
                    @if($rents->isNotEmpty())
                        <tr x-show="filteredRents.length === 0" style="display: none;">
                            <td colspan="8" class="px-4 py-8 text-center text-slate-400 italic">No matching office rent entries found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Workflow A: Add Office Rent Entry Modal --}}
    <div x-show="openAdd" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none" @click.outside="openAdd = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">WORKFLOW A &bull; OFFICE RENT ENTRY</span>
                    <h3 class="font-black text-sm uppercase tracking-wider text-white">New Office Rent Entry</h3>
                </div>
                <button type="button" @click="openAdd = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <form action="{{ route('office-rent.store') }}" method="POST" class="p-6 space-y-4 text-xs font-sans max-h-[82vh] overflow-y-auto">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Office Location / Branch *</label>
                        <input type="text" name="office_location" list="office-location-options" required x-model="addForm.office_location" placeholder="e.g., Corporate Office, Branch Office" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                        <datalist id="office-location-options">
                            @foreach($locations as $loc)
                                <option value="{{ $loc }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Landlord / Payee Name *</label>
                        <input type="text" name="landlord_name" required x-model="addForm.landlord_name" placeholder="Enter landlord or payee name" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Rent Period &mdash; Month *</label>
                        <select name="rent_month" required x-model="addForm.rent_month" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Rent Period &mdash; Year *</label>
                        <input type="number" name="rent_year" required min="2000" max="2100" x-model="addForm.rent_year" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold font-mono text-slate-900">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Rent Amount (₹) *</label>
                        <input type="number" step="0.01" min="0.01" name="gross_rent_amount" required x-model="addForm.gross_rent_amount" placeholder="0.00" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold font-mono text-slate-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Voucher Date *</label>
                        <input type="date" name="voucher_date" required x-model="addForm.voucher_date" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1.5">Payment Status *</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition" :class="addForm.payment_status === 'Paid' ? 'border-[#a38c29] bg-amber-50/70' : 'border-slate-200 bg-slate-50'">
                                <input type="radio" name="payment_status" value="Paid" x-model="addForm.payment_status" class="accent-[#a38c29]">
                                <div>
                                    <div class="font-extrabold text-slate-900">Paid Immediately</div>
                                    <div class="text-[10px] text-slate-500">Credit 1001 Bank Balances now</div>
                                </div>
                            </label>
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition" :class="addForm.payment_status === 'Unpaid' ? 'border-[#a38c29] bg-amber-50/70' : 'border-slate-200 bg-slate-50'">
                                <input type="radio" name="payment_status" value="Unpaid" x-model="addForm.payment_status" class="accent-[#a38c29]">
                                <div>
                                    <div class="font-extrabold text-slate-900">Book as Unpaid Payable</div>
                                    <div class="text-[10px] text-slate-500">Credit 2010 Provisions &amp; Outstanding Payables</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <template x-if="addForm.payment_status === 'Paid'">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Payment Source (1001 Bank Balances) *</label>
                            <select name="company_bank_account_id" required x-model="addForm.company_bank_account_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                                @forelse($bankAccounts as $ba)
                                    <option value="{{ $ba->id }}">
                                        {{ $ba->bank_name }}{{ $ba->account_number ? ' (A/C ' . $ba->account_number . ')' : '' }} &mdash; Bal: ₹{{ number_format((float)$ba->current_balance, 2) }}
                                    </option>
                                @empty
                                    <option value="">No active company bank accounts found</option>
                                @endforelse
                            </select>
                        </div>
                    </template>
                    <template x-if="addForm.payment_status === 'Paid'">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Reference No. / UTR</label>
                            <input type="text" name="payment_reference" x-model="addForm.payment_reference" placeholder="e.g. UTR12345678 / CHQ-001" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                        </div>
                    </template>

                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Remarks</label>
                        <input type="text" name="remarks" x-model="addForm.remarks" placeholder="Optional narration or lease reference" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="openAdd = false" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold uppercase cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase tracking-wider cursor-pointer shadow-sm">Save Rent Entry</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Pay Unpaid Rent Modal --}}
    <div x-show="openPay" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none" @click.outside="openPay = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">SETTLE RENT PAYABLE</span>
                    <h3 class="font-black text-sm uppercase tracking-wider text-white">Pay Office Rent &mdash; <span x-text="payItem.office_location"></span></h3>
                </div>
                <button type="button" @click="openPay = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <form :action="'{{ url('/office-rent') }}/' + payItem.id + '/pay'" method="POST" class="p-6 space-y-4 text-xs font-sans">
                @csrf
                <div class="rounded-xl bg-slate-50 border border-slate-200 p-3.5 flex items-center justify-between">
                    <div>
                        <div class="font-extrabold text-slate-900" x-text="payItem.landlord_name"></div>
                        <div class="text-[10px] text-slate-500"><span x-text="payItem.office_location"></span> &bull; <span x-text="payItem.rent_period_label"></span></div>
                    </div>
                    <div class="text-right">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Net Payable</div>
                        <div class="text-lg font-black font-mono text-emerald-700" x-text="formatMoney(payItem.net_payable_amount)"></div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Payment Source (1001 Bank Balances) *</label>
                        <select name="company_bank_account_id" required x-model="payItem.company_bank_account_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                            @forelse($bankAccounts as $ba)
                                <option value="{{ $ba->id }}">
                                    {{ $ba->bank_name }}{{ $ba->account_number ? ' (A/C ' . $ba->account_number . ')' : '' }} &mdash; Bal: ₹{{ number_format((float)$ba->current_balance, 2) }}
                                </option>
                            @empty
                                <option value="">No active company bank accounts found</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Payment Date *</label>
                        <input type="date" name="payment_date" required x-model="payItem.payment_date" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Reference No. / UTR</label>
                        <input type="text" name="payment_reference" x-model="payItem.payment_reference" placeholder="e.g. UTR12345678" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="openPay = false" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold uppercase cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold uppercase tracking-wider cursor-pointer shadow-sm">Confirm &amp; Pay</button>
                </div>
            </form>
        </div>
    </div>

    {{-- View Office Rent Details Modal --}}
    <div x-show="openView" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none" @click.outside="openView = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1" x-text="viewItem.voucher_no"></span>
                    <h3 class="font-black text-sm uppercase tracking-wider text-white">Office Rent Details &mdash; <span x-text="viewItem.office_location"></span></h3>
                </div>
                <button type="button" @click="openView = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <div class="p-6 space-y-4 text-xs font-sans">
                <div class="grid grid-cols-2 gap-3 bg-slate-50 rounded-xl border border-slate-200 p-3.5">
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Office Location</div>
                        <div class="font-extrabold text-slate-900 mt-0.5" x-text="viewItem.office_location"></div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Landlord / Payee</div>
                        <div class="font-extrabold text-slate-900 mt-0.5" x-text="viewItem.landlord_name"></div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Rent Period</div>
                        <div class="font-bold text-slate-800 mt-0.5" x-text="viewItem.rent_period_label"></div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Voucher Date</div>
                        <div class="font-mono font-bold text-slate-800 mt-0.5" x-text="viewItem.voucher_date"></div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 overflow-hidden">
                    <div class="px-4 py-3 bg-[#2a2415] text-white flex items-center justify-between">
                        <span class="font-black uppercase tracking-wider text-xs">Rent Amount (4106)</span>
                        <span class="font-mono font-black text-sm text-[#f3e5ab]" x-text="formatMoney(viewItem.gross_rent_amount)"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 bg-slate-50 rounded-xl border border-slate-200 p-3.5">
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Payment Status</div>
                        <div class="mt-1">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold" :class="viewItem.payment_status === 'Paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'" x-text="viewItem.payment_status === 'Paid' ? 'Paid' : 'Unpaid Payable'"></span>
                        </div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Payment Date</div>
                        <div class="font-mono font-bold text-slate-800 mt-0.5" x-text="viewItem.payment_date"></div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Company Bank Account</div>
                        <div class="font-bold text-slate-800 mt-0.5" x-text="viewItem.bank_name ? (viewItem.bank_name + (viewItem.account_number ? ' (A/C ' + viewItem.account_number + ')' : '')) : '—'"></div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Reference No.</div>
                        <div class="font-mono font-bold text-slate-800 mt-0.5" x-text="viewItem.payment_reference"></div>
                    </div>
                    <template x-if="viewItem.payment_voucher_no">
                        <div class="col-span-2">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Settlement Voucher</div>
                            <div class="font-mono font-bold text-emerald-700 mt-0.5" x-text="viewItem.payment_voucher_no"></div>
                        </div>
                    </template>
                    <div class="col-span-2">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Remarks</div>
                        <div class="font-semibold text-slate-700 mt-0.5" x-text="viewItem.remarks"></div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="button" @click="openView = false" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase cursor-pointer">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="openDeleteModal" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none" @click.outside="openDeleteModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">CONFIRMATION</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">DELETE RENT ENTRY</h3>
                </div>
                <button type="button" @click="openDeleteModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <div class="p-6 space-y-4 text-center font-sans">
                <p class="text-xs font-semibold text-slate-600">Are you sure you want to delete <span class="font-bold text-slate-900" x-text="deleteName"></span>?</p>
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
