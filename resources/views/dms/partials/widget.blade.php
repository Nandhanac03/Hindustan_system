@php
    $documents = $model->documents()->orderBy('created_at', 'desc')->get();
    
    $documentTypes = [
        'customer' => ['Booking Form', 'Signed Agreement', 'ID Proof (PAN/Aadhaar)', 'Payment Receipt', 'Possession Letter', 'Other'],
        'property' => ['Unit Floor Plan', 'Sale Deed', 'Allotment Letter', 'Handover Certificate', 'Other'],
        'project' => ['Approved Building Plan', 'Environmental Clearance', 'RERA Certificate', 'Land Title Deed', 'Other'],
        'legal_vendor' => ['Supplier Contract', 'Contractor Agreement', 'Statutory Tax Certificate', 'Other'],
    ];
    
    $subTypes = $documentTypes[$category] ?? ['Other'];
    $modelClass = get_class($model);
@endphp

<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 mt-6" 
     x-data="{ 
         showUploadForm: false,
         fileName: '',
         docTitle: '',
         handleWidgetFileSelect(e) {
             if(e.target.files.length > 0) {
                 this.fileName = e.target.files[0].name;
                 if(!this.docTitle) {
                     this.docTitle = this.fileName.split('.').slice(0, -1).join('.');
                 }
             }
         }
     }">
    
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
        <div class="flex items-center gap-2">
            <div class="p-1.5 bg-primary-50 rounded-lg text-[#a38c29]">
                <i data-lucide="files" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Documents Locker</h3>
                <p class="text-[9px] text-slate-400 font-bold uppercase mt-0.5">Scored files: {{ $documents->count() }}</p>
            </div>
        </div>
        
        <button type="button" 
                @click="showUploadForm = !showUploadForm" 
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-lg text-[10px] font-bold transition uppercase tracking-wide">
            <span x-text="showUploadForm ? 'Close Upload' : 'Upload File'"></span>
        </button>
    </div>

    {{-- Inline Upload Form --}}
    <div x-show="showUploadForm" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="mb-6 p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3.5 text-left" 
         style="display: none;">
        
        <h4 class="text-[10px] font-bold text-slate-700 uppercase tracking-wide">Add New Document</h4>
        
        <form action="{{ route('dms.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <input type="hidden" name="category" value="{{ $category }}">
            <input type="hidden" name="documentable_type" value="{{ $modelClass }}">
            <input type="hidden" name="documentable_id" value="{{ $model->id }}">

            <div class="space-y-1.5">
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Document Type *</label>
                <select name="document_type" 
                        required 
                        class="w-full px-3 py-1.5 bg-white border border-slate-250 rounded-lg text-xs font-semibold text-slate-800 cursor-pointer">
                    <option value="">-- Choose type --</option>
                    @foreach($subTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Document Title *</label>
                <input type="text" 
                       name="title" 
                       x-model="docTitle"
                       required 
                       placeholder="Enter title"
                       class="w-full px-3 py-1.5 bg-white border border-slate-250 rounded-lg text-xs font-semibold text-slate-800">
            </div>

            <div class="space-y-1.5 md:col-span-2">
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Description / Notes</label>
                <input type="text" 
                       name="description" 
                       placeholder="Optional notes"
                       class="w-full px-3 py-1.5 bg-white border border-slate-250 rounded-lg text-xs font-semibold text-slate-800">
            </div>

            <div class="space-y-1.5 md:col-span-2">
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Choose File *</label>
                <div class="relative w-full border border-dashed border-slate-300 rounded-lg bg-white hover:bg-slate-50 transition cursor-pointer flex flex-col items-center justify-center py-4 px-3">
                    <input type="file" 
                           name="file" 
                           required 
                           @change="handleWidgetFileSelect"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <i data-lucide="upload" class="w-5 h-5 text-slate-400 mb-1"></i>
                    <p class="text-[10px] font-bold text-slate-600" x-text="fileName ? fileName : 'Click to select file'"></p>
                    <p class="text-[8px] text-slate-450 uppercase mt-0.5">PDF, Images, DOCX (Max 15MB)</p>
                </div>
            </div>

            <div class="md:col-span-2 flex items-center justify-end gap-2 pt-2">
                <button type="submit" 
                        class="px-3.5 py-1.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-lg text-[10px] font-bold uppercase tracking-wide transition shadow-sm">
                    Upload & Attach
                </button>
            </div>
        </form>
    </div>

    {{-- Documents List Table --}}
    @if($documents->count() > 0)
        <div class="overflow-hidden border border-slate-150 rounded-xl">
            <table class="w-full border-collapse text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-[9px] font-bold uppercase text-slate-400 tracking-wider border-b border-slate-150">
                        <th class="px-4 py-2.5">Title</th>
                        <th class="px-4 py-2.5">Type</th>
                        <th class="px-4 py-2.5">Uploaded</th>
                        <th class="px-4 py-2.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-150">
                    @foreach($documents as $doc)
                        @php
                            $icon = 'file';
                            $iconColor = 'text-slate-400';
                            if (Str::contains($doc->mime_type, 'pdf')) {
                                $icon = 'file-text';
                                $iconColor = 'text-rose-500';
                            } elseif (Str::contains($doc->mime_type, 'image')) {
                                $icon = 'image';
                                $iconColor = 'text-emerald-500';
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="{{ $iconColor }}"><i data-lucide="{{ $icon }}" class="w-4 h-4"></i></span>
                                    <div>
                                        <span class="font-bold text-slate-800 block">{{ $doc->title }}</span>
                                        @if($doc->description)
                                            <span class="text-[9px] text-slate-450 block leading-tight">{{ $doc->description }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge border bg-slate-50 text-slate-600 border-slate-200 text-[8px] font-extrabold uppercase px-1.5 py-0.5 rounded">
                                    {{ $doc->document_type }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-[10px] text-slate-600 font-bold">{{ $doc->uploader->name ?? 'System' }}</div>
                                <div class="text-[8px] text-slate-400">{{ $doc->created_at->format('d-M-y H:i') }}</div>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('dms.download', $doc->id) }}" 
                                       class="p-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 rounded-md transition" 
                                       title="Download">
                                        <i data-lucide="download" class="w-3 h-3"></i>
                                    </a>
                                    
                                    <form action="{{ route('dms.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Confirm permanent deletion?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-1.5 bg-slate-50 hover:bg-rose-50 border border-slate-200 text-slate-400 hover:text-rose-600 rounded-md transition" 
                                                title="Delete">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-6 border border-dashed border-slate-200 rounded-xl bg-slate-50/50">
            <i data-lucide="folder" class="w-6 h-6 text-slate-400 mx-auto mb-1"></i>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">No Documents Attached</p>
        </div>
    @endif
</div>
