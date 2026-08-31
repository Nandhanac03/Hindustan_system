<x-erp-layout title="Document Categories Master" headerTitle="Document Categories Directory">

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="dmsCategoryApp()">

        <!-- Breadcrumb & Top Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
            <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                <span class="text-slate-300">›</span>
                <span>Document Management</span>
                <span class="text-slate-300">›</span>
                <span class="text-[#a38c29] font-black">Categories Master</span>
            </div>

            <button @click="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Add Category</span>
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
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold shadow-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Summary Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Card 1: Total Categories --}}
            <div class="bg-white border-y border-r border-l-4 border-l-[#a38c29] border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Total Categories</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $categories->count() }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Total registered document categories</p>
                </div>
            </div>

            {{-- Card 2: Active Categories --}}
            <div class="bg-white border-y border-r border-l-4 border-l-emerald-500 border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Active Categories</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $categories->where('is_active', true)->count() }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Currently active and selectable</p>
                </div>
            </div>
        </div>

        <!-- Directory Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6 flex flex-col">
            
            <!-- Header -->
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                        Document Categories List
                    </h3>
                    <p class="text-[10px] font-bold text-slate-500 mt-1 pl-3">Define main repositories for uploading documents.</p>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto min-h-[400px]">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#a38c29] text-[10px] font-black text-white uppercase tracking-wider border-y border-[#8a7522]">
                        <tr>
                            <th class="px-5 py-3 w-16 text-center">ID</th>
                            <th class="px-5 py-3">Category Name</th>
                            <th class="px-5 py-3">Code / Slug</th>
                            <th class="px-5 py-3">Icon Name</th>
                            <th class="px-5 py-3 text-center">Types Count</th>
                            <th class="px-5 py-3 text-right w-28">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-50 transition group">
                                <td class="px-5 py-3 text-center text-xs font-bold text-slate-400">
                                    {{ str_pad((string)$category->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-5 py-3 text-xs font-black text-slate-800 uppercase tracking-wide">
                                    {{ $category->name }}
                                </td>
                                <td class="px-5 py-3 text-xs font-mono text-slate-600">
                                    {{ $category->code }}
                                </td>
                                <td class="px-5 py-3 text-xs text-slate-600">
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 font-mono text-[10px]">
                                            {{ $category->icon ?? 'document' }}
                                        </span>
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center text-xs font-bold text-slate-800">
                                    {{ $category->document_types_count }}
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <button @click="openEditModal({{ $category->toJson() }})" class="p-2 rounded-xl transition-colors inline-flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-100" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                        <form action="{{ route('dms.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Category and all its associated Types?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl transition-colors inline-flex items-center justify-center bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-100" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    <p class="text-sm font-bold text-slate-500">No Document Categories Found</p>
                                    <p class="text-xs mt-1">Add a new category to get started.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Category Modal -->
        <div x-show="modals.add.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="closeAddModal()">
                <div class="px-6 py-4 bg-slate-900 flex items-center justify-between">
                    <h3 class="text-xs font-black uppercase tracking-wider text-white">Add Document Category</h3>
                    <button @click="closeAddModal()" class="text-white/60 hover:text-white transition">✕</button>
                </div>
                <form action="{{ route('dms.categories.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Category Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Project Documents" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-xl outline-none transition-all text-xs font-semibold text-slate-800">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Code / Slug (Optional)</label>
                        <input type="text" name="code" placeholder="e.g. project (auto-generated if left blank)" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-xl outline-none transition-all text-xs font-semibold text-slate-800">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Icon Identifier (Optional)</label>
                        <input type="text" name="icon" placeholder="e.g. office-building, scale, briefcase" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-xl outline-none transition-all text-xs font-semibold text-slate-800">
                    </div>
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="closeAddModal()" class="px-4 py-2 border border-slate-200 text-slate-650 text-xs font-bold rounded-xl uppercase tracking-wider hover:bg-slate-50 transition">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20">Save Category</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Category Modal -->
        <div x-show="modals.edit.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="closeEditModal()">
                <div class="px-6 py-4 bg-slate-900 flex items-center justify-between">
                    <h3 class="text-xs font-black uppercase tracking-wider text-white">Edit Document Category</h3>
                    <button @click="closeEditModal()" class="text-white/60 hover:text-white transition">✕</button>
                </div>
                <form :action="editUrl" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Category Name *</label>
                        <input type="text" name="name" x-model="forms.edit.name" required placeholder="e.g. Project Documents" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-xl outline-none transition-all text-xs font-semibold text-slate-800">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Code / Slug *</label>
                        <input type="text" name="code" x-model="forms.edit.code" required placeholder="e.g. project" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-xl outline-none transition-all text-xs font-semibold text-slate-800">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Icon Identifier (Optional)</label>
                        <input type="text" name="icon" x-model="forms.edit.icon" placeholder="e.g. office-building" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-xl outline-none transition-all text-xs font-semibold text-slate-800">
                    </div>
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="closeEditModal()" class="px-4 py-2 border border-slate-200 text-slate-650 text-xs font-bold rounded-xl uppercase tracking-wider hover:bg-slate-50 transition">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20">Update Category</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function dmsCategoryApp() {
            return {
                modals: {
                    add: { open: false },
                    edit: { open: false }
                },
                forms: {
                    edit: { id: '', name: '', code: '', icon: '' }
                },
                editUrl: '',
                openAddModal() {
                    this.modals.add.open = true;
                },
                closeAddModal() {
                    this.modals.add.open = false;
                },
                openEditModal(category) {
                    this.forms.edit = {
                        id: category.id,
                        name: category.name,
                        code: category.code,
                        icon: category.icon
                    };
                    this.editUrl = `{{ url('dms/categories') }}/${category.id}`;
                    this.modals.edit.open = true;
                },
                closeEditModal() {
                    this.modals.edit.open = false;
                }
            }
        }
    </script>

</x-erp-layout>
