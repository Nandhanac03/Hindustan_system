<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\DmsCategory;
use App\Models\DmsDocumentType;
use Illuminate\Http\Request;

class DmsDocumentTypeController extends Controller
{
    public function index()
    {
        $categories = DmsCategory::orderBy('name')->get();
        $documentTypes = DmsDocumentType::with('category')->orderBy('name')->get();
        return view('dms.document-types.index', compact('documentTypes', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dms_category_id' => 'required|exists:dms_categories,id',
        ]);

        DmsDocumentType::create([
            'name' => $validated['name'],
            'dms_category_id' => $validated['dms_category_id'],
            'is_active' => true,
        ]);

        return redirect()->route('dms.document-types.index')->with('success', 'Document Type created successfully.');
    }

    public function update(Request $request, $id)
    {
        $documentType = DmsDocumentType::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dms_category_id' => 'required|exists:dms_categories,id',
        ]);

        $documentType->update([
            'name' => $validated['name'],
            'dms_category_id' => $validated['dms_category_id'],
        ]);

        return redirect()->route('dms.document-types.index')->with('success', 'Document Type updated successfully.');
    }

    public function destroy($id)
    {
        $documentType = DmsDocumentType::findOrFail($id);
        $documentType->delete();

        return redirect()->route('dms.document-types.index')->with('success', 'Document Type deleted successfully.');
    }
}
