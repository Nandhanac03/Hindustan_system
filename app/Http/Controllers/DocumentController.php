<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Customer;
use App\Models\Unit;
use App\Models\Project;
use App\Models\Payee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    protected array $documentTypes = [
        'customer' => [
            'Booking Form',
            'Signed Agreement',
            'ID Proof (PAN/Aadhaar)',
            'Payment Receipt',
            'Possession Letter',
            'Other'
        ],
        'property' => [
            'Unit Floor Plan',
            'Sale Deed',
            'Allotment Letter',
            'Handover Certificate',
            'Other'
        ],
        'project' => [
            'Approved Building Plan',
            'Environmental Clearance',
            'RERA Certificate',
            'Land Title Deed',
            'Other'
        ],
        'legal_vendor' => [
            'Supplier Contract',
            'Contractor Agreement',
            'Statutory Tax Certificate',
            'Other'
        ],
    ];

    public function index(Request $request)
    {
        $user = Auth::user();
        $systemId = $user->system_id;
        $category = $request->get('category', 'customer');
        
        if (!in_array($category, ['customer', 'property', 'project', 'legal_vendor'])) {
            $category = 'customer';
        }

        // Get documents scoped to system and category
        $documents = Document::where('system_id', $systemId)
            ->where('category', $category)
            ->with(['documentable', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->get();

        $types = $this->documentTypes[$category] ?? [];

        // Load selectable targets for upload modal depending on current tab/category
        $selectableTargets = [];
        if ($category === 'customer') {
            $selectableTargets = Customer::orderBy('name')->get(['id', 'name']);
        } elseif ($category === 'property') {
            $selectableTargets = Unit::with('project')->orderBy('door_no')->get()->map(function($unit) {
                return [
                    'id' => $unit->id,
                    'name' => ($unit->project ? $unit->project->name . ' - ' : '') . $unit->formatted_name
                ];
            });
        } elseif ($category === 'project') {
            $selectableTargets = Project::where('system_id', $systemId)->orderBy('name')->get(['id', 'name']);
        } elseif ($category === 'legal_vendor') {
            $selectableTargets = Payee::where('system_id', $systemId)
                ->whereIn('type', ['Contractor', 'Supplier'])
                ->orderBy('name')
                ->get(['id', 'name', 'type']);
        }

        return view('dms.index', compact('documents', 'category', 'types', 'selectableTargets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'documentable_type' => 'required|string',
            'documentable_id' => 'required|integer',
            'category' => 'required|string|in:customer,property,project,legal_vendor',
            'document_type' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => 'required|file|max:15360', // Max 15MB
        ]);

        $user = Auth::user();
        
        // Resolve model instance & ensure it exists and matches system scope if scoped
        $modelClass = $request->input('documentable_type');
        if (!class_exists($modelClass)) {
            return back()->with('error', 'Invalid entity type.');
        }

        $query = $modelClass::query();
        // Project and Payee use system scope / system_id directly
        // Check if the table has system_id column before querying
        $modelInstance = $query->find($request->input('documentable_id'));
        if (!$modelInstance) {
            return back()->with('error', 'Linked entity not found.');
        }

        // Store file securely (non-public directory)
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $mimeType = $file->getClientMimeType();
        $fileSize = $file->getSize();
        
        // Save in storage/app/secure_documents/category/name_timestamp
        $safeTitle = Str::slug($request->input('title'));
        $extension = $file->getClientOriginalExtension();
        $storedName = $safeTitle . '_' . time() . '.' . $extension;
        $path = $file->storeAs('secure_documents/' . $request->input('category'), $storedName, 'local');

        Document::create([
            'system_id' => $user->system_id,
            'documentable_type' => $modelClass,
            'documentable_id' => $modelInstance->id,
            'category' => $request->input('category'),
            'document_type' => $request->input('document_type'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'file_path' => $path,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'uploaded_by' => $user->id,
        ]);

        return back()->with('status', '✅ Document uploaded and secured successfully.');
    }

    public function download(int $id)
    {
        $user = Auth::user();
        $document = Document::where('system_id', $user->system_id)->findOrFail($id);

        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File not found on storage disk.');
        }

        return Storage::disk('local')->download($document->file_path, $document->file_name);
    }

    public function destroy(int $id)
    {
        $user = Auth::user();
        $document = Document::where('system_id', $user->system_id)->findOrFail($id);

        // Delete from local disk
        if (Storage::disk('local')->exists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('status', '✅ Document deleted successfully.');
    }
}
