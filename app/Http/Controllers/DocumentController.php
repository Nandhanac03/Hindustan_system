<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Employee;
use App\Models\Payee;
use App\Models\Project;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    private function getCategoriesInfo(): array
    {
        $categoriesInfo = [];
        $dmsCats = \App\Models\DmsCategory::with('documentTypes')->where('is_active', true)->get();
        foreach ($dmsCats as $cat) {
            $categoriesInfo[$cat->code] = [
                'id' => $cat->id,
                'label' => $cat->name,
                'icon' => $cat->icon ?? 'document',
                'types' => $cat->documentTypes->pluck('name')->toArray(),
            ];
        }
        return $categoriesInfo;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $systemId = $user->system_id;
        $system = System::findOrFail($systemId);

        $categoriesInfo = $this->getCategoriesInfo();

        // Filter parameters
        $selectedCategory = $request->get('category');
        $selectedProject = $request->get('project_id');
        $selectedDocType = $request->get('document_type');
        $searchQuery = $request->get('search');

        // Check if category exists
        if ($selectedCategory && !array_key_exists($selectedCategory, $categoriesInfo)) {
            $selectedCategory = null;
        }

        // Build DMS query
        $query = Document::where('system_id', $systemId)
            ->with(['documentable', 'uploader', 'referenceProject']);

        if ($selectedCategory) {
            $query->where('category', $selectedCategory);
        }

        if ($selectedProject) {
            $query->where(function ($q) use ($selectedProject) {
                $q->where('reference_project_id', $selectedProject)
                  ->orWhere(function ($sq) use ($selectedProject) {
                      $sq->where('documentable_type', Project::class)
                         ->where('documentable_id', $selectedProject);
                  });
            });
        }

        if ($selectedDocType) {
            $query->where('document_type', $selectedDocType);
        }

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                  ->orWhere('document_number', 'like', "%{$searchQuery}%")
                  ->orWhere('file_name', 'like', "%{$searchQuery}%");
            });
        }

        // Retrieve filtered list
        $documents = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // 1. Stats Counters for top cards
        $categoryCounts = [];
        foreach ($categoriesInfo as $catKey => $info) {
            $categoryCounts[$catKey] = Document::where('system_id', $systemId)
                ->where('category', $catKey)
                ->count();
        }

        // 2. Documents Expiring Soon (expiring in the future, sorted by closest first)
        $expiringSoon = Document::where('system_id', $systemId)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>=', now())
            ->with(['documentable', 'referenceProject'])
            ->orderBy('expiry_date')
            ->take(5)
            ->get();

        // 3. Recent Uploads
        $recentUploads = Document::where('system_id', $systemId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 4. Storage Overview calculation (using 100 GB as system default limit)
        $totalBytesUsed = Document::where('system_id', $systemId)->sum('file_size');
        $totalLimitGB = 100;
        $totalLimitBytes = $totalLimitGB * 1024 * 1024 * 1024;
        
        $usedPercentage = $totalLimitBytes > 0 ? min(round(($totalBytesUsed / $totalLimitBytes) * 100, 1), 100.0) : 0.0;
        
        $formattedUsed = $this->formatBytes($totalBytesUsed);
        $formattedLimit = $totalLimitGB . ' GB';
        $formattedAvailable = $this->formatBytes(max($totalLimitBytes - $totalBytesUsed, 0));

        $storageStats = [
            'used_percent' => $usedPercentage,
            'used' => $formattedUsed,
            'limit' => $formattedLimit,
            'available' => $formattedAvailable,
        ];

        // Load selects for Upload Document modal
        $projects = Project::where('system_id', $systemId)->orderBy('name')->get(['id', 'name']);
        $employees = Employee::where('system_id', $systemId)->orderBy('name')->get(['id', 'employee_id', 'name', 'department']);
        $partners = Payee::where('system_id', $systemId)
            ->whereIn('type', ['Partner', 'Investor'])
            ->orderBy('name')
            ->get(['id', 'name']);

        // categoriesInfo is already defined locally

        return view('dms.index', compact(
            'documents',
            'categoriesInfo',
            'categoryCounts',
            'expiringSoon',
            'recentUploads',
            'storageStats',
            'projects',
            'employees',
            'partners',
            'selectedCategory',
            'selectedProject',
            'selectedDocType',
            'searchQuery',
            'system'
        ));
    }

    public function store(Request $request)
    {
        $categoriesInfo = $this->getCategoriesInfo();
        $catsString = implode(',', array_keys($categoriesInfo));

        $request->validate([
            'category' => 'required|string|in:' . $catsString,
            'title' => 'required|string|max:255',
            'document_type' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => 'required|file|max:15360', // Max 15MB
            
            // Metadata fields
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'document_number' => 'nullable|string|max:255',
            'revision_number' => 'nullable|string|max:255',
            'drawing_type' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'legal_category' => 'nullable|string|max:255',
            'template_category' => 'nullable|string|max:255',
            'tower' => 'nullable|string|max:255',
            
            // Selector links
            'project_id' => 'nullable|integer',
            'employee_id' => 'nullable|integer',
            'partner_id' => 'nullable|integer',
        ]);

        $user = Auth::user();
        $systemId = $user->system_id;
        
        $category = $request->input('category');
        $docType = $request->input('document_type');
        
        // Resolve polymorphic entity association
        $docableType = System::class;
        $docableId = $systemId;
        $refProjectId = null;

        if ($category === 'project' || $category === 'drawings') {
            $projId = $request->input('project_id');
            if ($projId) {
                $projectInstance = Project::where('system_id', $systemId)->findOrFail($projId);
                $docableType = Project::class;
                $docableId = $projectInstance->id;
                $refProjectId = $projectInstance->id;
            }
        } elseif ($category === 'legal') {
            $projId = $request->input('project_id');
            if ($projId) {
                $projectInstance = Project::where('system_id', $systemId)->findOrFail($projId);
                $docableType = Project::class;
                $docableId = $projectInstance->id;
                $refProjectId = $projectInstance->id;
            } else {
                $docableType = System::class;
                $docableId = $systemId;
            }
        } elseif ($category === 'hr') {
            $empId = $request->input('employee_id');
            if ($empId) {
                $employeeInstance = Employee::where('system_id', $systemId)->findOrFail($empId);
                $docableType = Employee::class;
                $docableId = $employeeInstance->id;
            }
        } elseif ($category === 'partner') {
            $partId = $request->input('partner_id');
            if ($partId) {
                $partnerInstance = Payee::where('system_id', $systemId)->whereIn('type', ['Partner', 'Investor'])->findOrFail($partId);
                $docableType = Payee::class;
                $docableId = $partnerInstance->id;
            }
            $projId = $request->input('project_id');
            if ($projId) {
                $projectInstance = Project::where('system_id', $systemId)->findOrFail($projId);
                $refProjectId = $projectInstance->id;
            }
        }

        // Store file securely
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $mimeType = $file->getClientMimeType();
        $fileSize = $file->getSize();
        
        $safeTitle = Str::slug($request->input('title'));
        $extension = $file->getClientOriginalExtension();
        $storedName = $safeTitle . '_' . time() . '.' . $extension;
        $path = $file->storeAs('secure_documents/' . $category, $storedName, 'local');

        Document::create([
            'system_id' => $systemId,
            'documentable_type' => $docableType,
            'documentable_id' => $docableId,
            'category' => $category,
            'document_type' => $docType,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'file_path' => $path,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'uploaded_by' => $user->id,
            
            // Metadata columns
            'issue_date' => $request->input('issue_date'),
            'expiry_date' => $request->input('expiry_date'),
            'document_number' => $request->input('document_number'),
            'revision_number' => $request->input('revision_number'),
            'drawing_type' => $request->input('drawing_type'),
            'department' => $request->input('department'),
            'legal_category' => $request->input('legal_category'),
            'template_category' => $request->input('template_category'),
            'tower' => $request->input('tower'),
            'reference_project_id' => $refProjectId,
        ]);

        return back()->with('status', '✅ Document uploaded and indexed successfully.');
    }

    public function download(int $id)
    {
        $user = Auth::user();
        $document = Document::where('system_id', $user->system_id)->findOrFail($id);

        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File not found on secure storage.');
        }

        return Storage::disk('local')->download($document->file_path, $document->file_name);
    }

    public function destroy(int $id)
    {
        $user = Auth::user();
        $document = Document::where('system_id', $user->system_id)->findOrFail($id);

        if (Storage::disk('local')->exists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('status', '✅ Document deleted successfully.');
    }

    private function formatBytes(float $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
