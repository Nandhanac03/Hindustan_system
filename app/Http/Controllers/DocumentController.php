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
        $selectedStatus = $request->get('status', 'active');
        $searchQuery = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Check if category exists
        if ($selectedCategory && !array_key_exists($selectedCategory, $categoriesInfo)) {
            $selectedCategory = null;
        }

        // Build DMS query
        $query = Document::where('system_id', $systemId)
            ->with(['documentable', 'uploader', 'referenceProject']);

        // Archive / Status Filtering
        if ($selectedStatus === 'archived') {
            $query->where('is_archived', true);
        } elseif ($selectedStatus === 'expiring_soon') {
            $query->where('is_archived', false)
                  ->whereNotNull('expiry_date')
                  ->where('expiry_date', '>=', now())
                  ->where('expiry_date', '<=', now()->addDays(30));
        } elseif ($selectedStatus === 'expired') {
            $query->where('is_archived', false)
                  ->whereNotNull('expiry_date')
                  ->where('expiry_date', '<', now());
        } elseif ($selectedStatus === 'all') {
            // Include both active and archived
        } else {
            // Default active only
            $query->where('is_archived', false);
        }

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

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Retrieve filtered list
        $documents = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        // 1. Stats Counters for top cards
        $categoryCounts = [];
        foreach ($categoriesInfo as $catKey => $info) {
            $categoryCounts[$catKey] = Document::where('system_id', $systemId)
                ->where('category', $catKey)
                ->where('is_archived', false)
                ->count();
        }

        // 2. Documents Expiring Soon (expiring in next 60 days, sorted by closest first)
        $expiringSoon = Document::where('system_id', $systemId)
            ->where('is_archived', false)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>=', now())
            ->with(['documentable', 'referenceProject'])
            ->orderBy('expiry_date')
            ->take(6)
            ->get();

        // 3. Total active and expired counts for status badges
        $totalActiveCount = Document::where('system_id', $systemId)->where('is_archived', false)->count();
        $totalArchivedCount = Document::where('system_id', $systemId)->where('is_archived', true)->count();
        $totalExpiringCount = Document::where('system_id', $systemId)
            ->where('is_archived', false)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>=', now())
            ->where('expiry_date', '<=', now()->addDays(30))
            ->count();
        $totalExpiredCount = Document::where('system_id', $systemId)
            ->where('is_archived', false)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now())
            ->count();

        // 4. Storage Overview calculation (using 100 GB as system default limit)
        $totalBytesUsed = (float) (Document::where('system_id', $systemId)->sum('file_size') ?? 0);
        $totalLimitGB = 100;
        $totalLimitBytes = (float) ($totalLimitGB * 1024 * 1024 * 1024);
        
        $usedPercentage = $totalLimitBytes > 0 ? min(round(($totalBytesUsed / $totalLimitBytes) * 100, 1), 100.0) : 0.0;
        
        $formattedUsed = $this->formatBytes($totalBytesUsed);
        $formattedLimit = $totalLimitGB . ' GB';
        $formattedAvailable = $this->formatBytes(max($totalLimitBytes - $totalBytesUsed, 0.0));

        $storageStats = [
            'used_percent' => $usedPercentage,
            'used' => $formattedUsed,
            'limit' => $formattedLimit,
            'available' => $formattedAvailable,
        ];

        // Load selects for Upload Document modal
        $projects = Project::where('system_id', $systemId)->orderBy('name')->get(['id', 'name']);
        $units = \App\Models\Unit::whereHas('project', function($q) use ($systemId) {
            $q->where('system_id', $systemId);
        })->orderBy('door_no')->get(['id', 'project_id', 'door_no']);
        $employees = Employee::where('system_id', $systemId)->orderBy('name')->get(['id', 'employee_id', 'name', 'department']);
        $contractors = Payee::where('system_id', $systemId)
            ->whereIn('type', ['Contractor', 'Supplier'])
            ->orderBy('name')
            ->get(['id', 'name', 'phone', 'email', 'type']);
        $partners = Payee::where('system_id', $systemId)
            ->whereIn('type', ['Partner', 'Investor'])
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('dms.index', compact(
            'documents',
            'categoriesInfo',
            'categoryCounts',
            'expiringSoon',
            'totalActiveCount',
            'totalArchivedCount',
            'totalExpiringCount',
            'totalExpiredCount',
            'storageStats',
            'projects',
            'units',
            'employees',
            'contractors',
            'partners',
            'selectedCategory',
            'selectedProject',
            'selectedDocType',
            'selectedStatus',
            'searchQuery',
            'dateFrom',
            'dateTo',
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
            'file' => 'required|file|max:102400', // Max 100MB
            
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
            'contractor_id' => 'nullable|integer',
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
        } elseif ($category === 'contractor') {
            $contractorId = $request->input('contractor_id');
            if ($contractorId) {
                $contractorInstance = Payee::where('system_id', $systemId)->whereIn('type', ['Contractor', 'Supplier'])->findOrFail($contractorId);
                $docableType = Payee::class;
                $docableId = $contractorInstance->id;
            }
            $projId = $request->input('project_id');
            if ($projId) {
                $projectInstance = Project::where('system_id', $systemId)->findOrFail($projId);
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
            'is_archived' => false,
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

    public function preview(int $id)
    {
        $user = Auth::user();
        $document = Document::where('system_id', $user->system_id)->findOrFail($id);

        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File not found on secure storage.');
        }

        $fileContent = Storage::disk('local')->get($document->file_path);
        $mimeType = $document->mime_type ?: Storage::disk('local')->mimeType($document->file_path);

        return response($fileContent, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $document->file_name . '"',
        ]);
    }

    public function archive(int $id)
    {
        $user = Auth::user();
        $document = Document::where('system_id', $user->system_id)->findOrFail($id);
        $document->update(['is_archived' => true]);

        return back()->with('status', '📦 Document moved to Archive successfully.');
    }

    public function unarchive(int $id)
    {
        $user = Auth::user();
        $document = Document::where('system_id', $user->system_id)->findOrFail($id);
        $document->update(['is_archived' => false]);

        return back()->with('status', '✅ Document restored from Archive to Active repository.');
    }

    public function destroy(int $id)
    {
        $user = Auth::user();
        $document = Document::where('system_id', $user->system_id)->findOrFail($id);

        if (Storage::disk('local')->exists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('status', '✅ Document permanently deleted.');
    }

    private function formatBytes(float|int|string|null $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max((float) ($bytes ?? 0), 0.0);
        $pow = floor(($bytes > 0 ? log($bytes) : 0) / log(1024));
        $pow = min((int) $pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
