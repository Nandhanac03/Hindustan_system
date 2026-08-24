<x-erp-layout title="Document Explorer" headerTitle="Document Management Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="{ 
    searchQuery: new URLSearchParams(window.location.search).get('search') || '',
    showUploadModal: false,
    selectedTargetId: '',
    selectedDocType: '',
    docTitle: '',
    docDescription: '',
    fileName: '',
    
    handleFileSelect(e) {
        if(e.target.files.length > 0) {
            this.fileName = e.target.files[0].name;
            if(!this.docTitle) {
                this.docTitle = this.fileName.split('.').slice(0, -1).join('.');
            }
        }
    }
}">

    {{-- Top Action Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">
                Document Management System (DMS)
            </h1>
            <p class="text-xs text-slate-500 mt-1">Organize, secure, and track business documents, blueprints, contracts, and tax certificates.</p>
        </div>

        <button @click="showUploadModal = true; selectedTargetId = ''; selectedDocType = ''; docTitle = ''; docDescription = ''; fileName = '';" 
                class="btn-ripple inline-flex items-center gap-2 px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wide">
            <i data-lucide="upload" class="w-3.5 h-3.5"></i>
            Upload Document
        </button>
    </div>

    {{-- Status Alerts --}}
    @if(session('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-250 text-emerald-800 text-xs font-bold rounded-xl uppercase tracking-wide">
            {{ session('status') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-250 text-rose-800 text-xs font-bold rounded-xl uppercase tracking-wide">
            {{ session('error') }}
        </div>
    @endif

    {{-- Statistics & Overview Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-primary-50 rounded-xl text-primary-600">
                <i data-lucide="files" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Documents</p>
                <h3 class="text-xl font-bold text-slate-800 mt-0.5">{{ $documents->count() }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-amber-50 rounded-xl text-amber-600">
                <i data-lucide="hard-drive" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Storage Used</p>
                <h3 class="text-xl font-bold text-slate-800 mt-0.5">
                    @php
                        $totalBytes = $documents->sum('file_size');
                        $units = ['B', 'KB', 'MB', 'GB'];
                        for ($i = 0; $totalBytes >= 1024 && $i < count($units) - 1; $i++) {
                            $totalBytes /= 1024;
                        }
                        echo round($totalBytes, 2) . ' ' . $units[$i];
                    @endphp
                </h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                <i data-lucide="shield-check" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Storage Status</p>
                <h3 class="text-sm font-bold text-emerald-600 uppercase mt-1">Encrypted & Secured</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-purple-50 rounded-xl text-purple-600">
                <i data-lucide="user-check" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Access Scope</p>
                <h3 class="text-sm font-bold text-slate-700 uppercase mt-1">Tenant Level Scoped</h3>
            </div>
        </div>
    </div>

    {{-- Category Tabs --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-1.5 shadow-sm flex flex-wrap gap-1">
        <a href="{{ route('dms.index', ['category' => 'customer']) }}" 
           class="flex-1 min-w-[120px] text-center px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ $category === 'customer' ? 'bg-[#a38c29] text-white shadow-sm' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
            Customer Docs
        </a>
        <a href="{{ route('dms.index', ['category' => 'property']) }}" 
           class="flex-1 min-w-[120px] text-center px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ $category === 'property' ? 'bg-[#a38c29] text-white shadow-sm' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
            Property / Unit Docs
        </a>
        <a href="{{ route('dms.index', ['category' => 'project']) }}" 
           class="flex-1 min-w-[120px] text-center px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ $category === 'project' ? 'bg-[#a38c29] text-white shadow-sm' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
            Project Docs
        </a>
        <a href="{{ route('dms.index', ['category' => 'legal_vendor']) }}" 
           class="flex-1 min-w-[120px] text-center px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ $category === 'legal_vendor' ? 'bg-[#a38c29] text-white shadow-sm' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
            Legal & Vendor Docs
        </a>
    </div>

    {{-- File Browser Workspace --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        {{-- Toolbar --}}
        <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="relative flex-1 max-w-md">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input x-model="searchQuery" 
                       type="text" 
                       placeholder="Search by title, description or file name..." 
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/50 focus:bg-white transition">
            </div>
            
            <div class="text-[10px] font-bold text-slate-400 uppercase">
                Showing {{ $documents->count() }} Files inside <span class="text-primary-700 font-extrabold">{{ str_replace('_', ' & ', $category) }}</span>
            </div>
        </div>

        {{-- Table Grid --}}
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-[10px] font-bold uppercase text-slate-500 tracking-wider">
                        <th class="px-6 py-3.5">Document Details</th>
                        <th class="px-6 py-3.5">Category Type</th>
                        <th class="px-6 py-3.5">Associated Entity</th>
                        <th class="px-6 py-3.5">File Info</th>
                        <th class="px-6 py-3.5">Uploaded By</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-150">
                    @forelse($documents as $doc)
                        @php
                            $linkedEntityName = 'N/A';
                            $linkedEntityType = 'Unknown';
                            if ($doc->documentable) {
                                if ($doc->documentable_type === 'App\Models\Customer') {
                                    $linkedEntityType = 'Customer';
                                    $linkedEntityName = $doc->documentable->name;
                                } elseif ($doc->documentable_type === 'App\Models\Unit') {
                                    $linkedEntityType = 'Unit';
                                    $linkedEntityName = $doc->documentable->formatted_name;
                                } elseif ($doc->documentable_type === 'App\Models\Project') {
                                    $linkedEntityType = 'Project';
                                    $linkedEntityName = $doc->documentable->name;
                                } elseif ($doc->documentable_type === 'App\Models\Payee') {
                                    $linkedEntityType = $doc->documentable->type ?? 'Payee';
                                    $linkedEntityName = $doc->documentable->name;
                                }
                            }
                            
                            // Map icon based on mime type
                            $icon = 'file';
                            $iconColor = 'text-slate-400';
                            if (Str::contains($doc->mime_type, 'pdf')) {
                                $icon = 'file-text';
                                $iconColor = 'text-rose-500';
                            } elseif (Str::contains($doc->mime_type, 'image')) {
                                $icon = 'image';
                                $iconColor = 'text-emerald-500';
                            } elseif (Str::contains($doc->mime_type, ['msword', 'officedocument'])) {
                                $icon = 'file-box';
                                $iconColor = 'text-blue-500';
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors" 
                            x-show="searchQuery === '' || 
                                    '{{ strtolower($doc->title) }}'.includes(searchQuery.toLowerCase()) || 
                                    '{{ strtolower($doc->description) }}'.includes(searchQuery.toLowerCase()) || 
                                    '{{ strtolower($doc->file_name) }}'.includes(searchQuery.toLowerCase()) ||
                                    '{{ strtolower($linkedEntityName) }}'.includes(searchQuery.toLowerCase())">
                            <td class="px-6 py-4.5">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-slate-100 rounded-lg flex-shrink-0 {{ $iconColor }}">
                                        <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-800 tracking-tight leading-snug">{{ $doc->title }}</h4>
                                        @if($doc->description)
                                            <p class="text-[10px] text-slate-400 font-semibold mt-0.5 line-clamp-1">{{ $doc->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4.5">
                                <span class="badge border bg-primary-50 text-primary-800 border-primary-200 text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-md">
                                    {{ $doc->document_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4.5">
                                <div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ $linkedEntityType }}</div>
                                    <div class="text-xs font-bold text-slate-700 mt-0.5">{{ $linkedEntityName }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4.5">
                                <div class="text-xs font-bold text-slate-700">{{ $doc->formatted_size }}</div>
                                <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ strtoupper(explode('/', $doc->mime_type)[1] ?? 'unknown') }}</div>
                            </td>
                            <td class="px-6 py-4.5">
                                <div class="text-xs font-bold text-slate-700">{{ $doc->uploader->name ?? 'System' }}</div>
                                <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $doc->created_at->format('d-M-Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('dms.download', $doc->id) }}" 
                                       class="p-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 rounded-lg hover:text-slate-800 transition" 
                                       title="Download File">
                                        <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                    </a>
                                    
                                    <form action="{{ route('dms.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('⚠️ Are you sure you want to permanently delete this document? This cannot be undone.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 bg-slate-50 hover:bg-rose-50 border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-250 rounded-lg transition" 
                                                title="Delete File">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                    <div class="p-4 bg-slate-100 rounded-full text-slate-400 mb-3">
                                        <i data-lucide="folder-search" class="w-8 h-8"></i>
                                    </div>
                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wide">No Documents Found</h4>
                                    <p class="text-[10px] text-slate-450 mt-1 leading-normal">There are no documents uploaded in this category yet. Click upload to store files.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- File Upload Modal --}}
    <div x-show="showUploadModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-transition.opacity 
         style="display: none;">
        
        <div @click.outside="showUploadModal = false" 
             class="bg-white w-full max-w-lg rounded-2xl border border-slate-200 shadow-xl overflow-hidden flex flex-col"
             x-transition.scale>
            
            {{-- Modal Header --}}
            <div class="px-6 py-4.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Upload & Secure Document</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5 uppercase font-bold">Category: {{ str_replace('_', ' & ', $category) }}</p>
                </div>
                <button @click="showUploadModal = false" class="text-slate-400 hover:text-slate-600 transition">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            {{-- Modal Body --}}
            <form action="{{ route('dms.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 text-left">
                @csrf
                <input type="hidden" name="category" value="{{ $category }}">
                
                @if($category === 'customer')
                    <input type="hidden" name="documentable_type" value="App\Models\Customer">
                @elseif($category === 'property')
                    <input type="hidden" name="documentable_type" value="App\Models\Unit">
                @elseif($category === 'project')
                    <input type="hidden" name="documentable_type" value="App\Models\Project">
                @elseif($category === 'legal_vendor')
                    <input type="hidden" name="documentable_type" value="App\Models\Payee">
                @endif

                {{-- Associated Entity Target --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">
                        Select Target Entity <span class="text-rose-500">*</span>
                    </label>
                    <select name="documentable_id" 
                            x-model="selectedTargetId"
                            required 
                            class="w-full px-3.5 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/50 focus:bg-white transition cursor-pointer">
                        <option value="">-- Choose Target entity --</option>
                        @foreach($selectableTargets as $target)
                            <option value="{{ $target['id'] }}">{{ $target['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Document Subtype --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">
                        Document Type <span class="text-rose-500">*</span>
                    </label>
                    <select name="document_type" 
                            x-model="selectedDocType"
                            required 
                            class="w-full px-3.5 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/50 focus:bg-white transition cursor-pointer">
                        <option value="">-- Choose document type --</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Document Title --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">
                        Document Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           x-model="docTitle"
                           required 
                           placeholder="Enter document title (e.g., Booking Agreement - Block A)"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/50 focus:bg-white transition">
                </div>

                {{-- Document Description --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">
                        Description / Remarks
                    </label>
                    <textarea name="description" 
                              x-model="docDescription"
                              rows="3" 
                              placeholder="Add any extra notes or remarks here..."
                              class="w-full px-3.5 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/50 focus:bg-white transition"></textarea>
                </div>

                {{-- File Input --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">
                        Choose Document File <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative w-full border-2 border-dashed border-slate-250 rounded-xl bg-slate-50/50 hover:bg-slate-50 transition cursor-pointer flex flex-col items-center justify-center p-6">
                        <input type="file" 
                               name="file" 
                               required 
                               @change="handleFileSelect"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <i data-lucide="cloud-upload" class="w-8 h-8 text-slate-400 mb-2"></i>
                        <p class="text-xs font-bold text-slate-600" x-text="fileName ? fileName : 'Drag & drop file or click to browse'"></p>
                        <p class="text-[9px] text-slate-400 font-bold uppercase mt-1">Accepts PDF, Image, DOCX, ZIP (Max 15MB)</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                    <button type="button" 
                            @click="showUploadModal = false" 
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-150 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wide transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold uppercase tracking-wide transition shadow-md shadow-[#a38c29]/20">
                        Upload & Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
</x-erp-layout>
