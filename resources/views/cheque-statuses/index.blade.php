<x-erp-layout title="Cheque Status Master" headerTitle="Cheque Status Master Directory">

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="chequeStatusApp()">

        <!-- Breadcrumb & Top Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
            <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                <span class="text-slate-300">›</span>
                <span>Masters</span>
                <span class="text-slate-300">›</span>
                <span class="text-[#a38c29] font-black">Cheque Status Master</span>
            </div>

            <button @click="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Add Status</span>
            </button>
        </div>

        <!-- Alert Notifications -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:opacity-75 font-black text-sm">✕</button>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:opacity-75 font-black text-sm">✕</button>
            </div>
        @endif
        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold rounded-2xl shadow-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Summary Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Card 1: Total Statuses --}}
            <div class="bg-white border-y border-r border-l-4 border-l-[#a38c29] border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Total Statuses</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $statuses->count() }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Total registered statuses</p>
                </div>
            </div>

            {{-- Card 2: Active Statuses --}}
            <div class="bg-white border-y border-r border-l-4 border-l-emerald-500 border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Active Statuses</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $statuses->where('is_active', true)->count() }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Currently in use</p>
                </div>
            </div>
        </div>

        <!-- Directory Filter Bar & Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6 flex flex-col">
            
            <!-- Filters Header -->
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                        Cheque Status Master
                    </h3>
                    <p class="text-[10px] font-bold text-slate-500 mt-1 pl-3">Manage realization and processing statuses.</p>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto min-h-[400px]">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#a38c29] text-[10px] font-black text-white uppercase tracking-wider border-y border-[#8a7522]">
                        <tr>
                            <th class="px-5 py-3 w-16 text-center">ID</th>
                            <th class="px-5 py-3">Name</th>
                            <th class="px-5 py-3">Color Code</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right w-28">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($statuses as $status)
                            <tr class="hover:bg-slate-50 transition group">
                                <td class="px-5 py-3 text-center text-xs font-bold text-slate-400">
                                    {{ str_pad($status->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-5 py-3 text-xs font-black text-slate-800 uppercase tracking-wide">
                                    {{ $status->name }}
                                </td>
                                <td class="px-5 py-3">
                                    <span class="text-[10px] font-bold px-2 py-1 rounded-md bg-slate-100 text-slate-700 uppercase tracking-wider">
                                        {{ $status->color_code ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    @if($status->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-wider border border-emerald-200/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-wider border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <button @click="openEditModal({{ $status->toJson() }})" class="p-2 rounded-xl transition-colors inline-flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-100" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-slate-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    <p class="text-sm font-bold text-slate-500">No Cheque Statuses Found</p>
                                    <p class="text-xs mt-1">Add a new status to get started.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Modal using Alpine -->
        <div x-show="isModalOpen" 
             style="display: none;"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
             
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal()"></div>

            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 scale-95">
                 
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0 border-b border-slate-700">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Cheque Status Master</p>
                            <h2 class="text-lg font-extrabold text-white" x-text="editMode ? 'Edit Status' : 'Add New Status'"></h2>
                        </div>
                        <button type="button" @click="closeModal()" class="text-slate-400 hover:text-white transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <form :action="editMode ? '{{ url('cheque-statuses') }}/' + formData.id : '{{ route('cheque-statuses.store') }}'" method="POST" class="p-6 space-y-5">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 block mb-1.5">Status Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="formData.name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 block mb-1.5">Color Code</label>
                        <input type="text" name="color_code" x-model="formData.color_code" placeholder="e.g. emerald-500" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29] focus:bg-white transition-all">
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" id="is_active_check" x-model="formData.is_active" class="w-4 h-4 text-[#a38c29] bg-slate-50 border-slate-300 rounded focus:ring-[#a38c29]">
                        <label for="is_active_check" class="text-sm font-bold text-slate-700 cursor-pointer">Status Active</label>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" @click="closeModal()" class="px-5 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-50 rounded-xl transition">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20" x-text="editMode ? 'Update Status' : 'Save Status'"></button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="isDeleteModalOpen" 
             style="display: none;"
             class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
             
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeDeleteModal()"></div>

            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md relative z-10 overflow-hidden"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 scale-95">
                 
                <div class="relative bg-gradient-to-br from-[#2f2828] to-[#1a1818] px-6 py-5 border-b border-slate-800">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="inline-block px-2.5 py-0.5 rounded bg-rose-500/20 text-rose-300 text-[10px] font-black uppercase tracking-widest mb-1.5">Warning</span>
                            <h2 class="text-lg font-extrabold text-white uppercase tracking-wider">Delete Status</h2>
                        </div>
                        <button type="button" @click="closeDeleteModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition cursor-pointer">✕</button>
                    </div>
                </div>

                <div class="p-6">
                    <p class="text-sm text-slate-700 mb-4">Are you sure you want to delete status <strong class="font-black" x-text="statusToDelete ? statusToDelete.name : ''"></strong>?</p>
                    <p class="text-xs font-black text-slate-800 uppercase tracking-widest">This action cannot be undone.</p>
                </div>

                <div class="bg-slate-50 border-t border-slate-100 p-5 flex items-center justify-end gap-3">
                    <button type="button" @click="closeDeleteModal()" class="px-5 py-2.5 bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 rounded-xl transition shadow-sm">CANCEL</button>
                    
                    <form x-bind:action="statusToDelete ? '{{ url('cheque-statuses') }}/' + statusToDelete.id : '#'" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-md shadow-rose-600/20">CONFIRM DELETE</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function chequeStatusApp() {
            return {
                isModalOpen: false,
                isDeleteModalOpen: false,
                editMode: false,
                statusToDelete: null,
                formData: {
                    id: '',
                    name: '',
                    color_code: '',
                    is_active: true
                },
                openAddModal() {
                    this.editMode = false;
                    this.formData = { id: '', name: '', color_code: '', is_active: true };
                    this.isModalOpen = true;
                },
                openEditModal(status) {
                    this.editMode = true;
                    this.formData = { 
                        id: status.id, 
                        name: status.name, 
                        color_code: status.color_code, 
                        is_active: status.is_active == 1 
                    };
                    this.isModalOpen = true;
                },
                closeModal() {
                    this.isModalOpen = false;
                },
                openDeleteModal(status) {
                    this.statusToDelete = status;
                    this.isDeleteModalOpen = true;
                },
                closeDeleteModal() {
                    this.isDeleteModalOpen = false;
                    setTimeout(() => { this.statusToDelete = null; }, 300);
                }
            }
        }
    </script>
</x-erp-layout>
