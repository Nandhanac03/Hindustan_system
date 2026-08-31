<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\DmsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DmsCategoryController extends Controller
{
    public function index()
    {
        $categories = DmsCategory::withCount('documentTypes')->orderBy('name')->get();
        return view('dms.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:dms_categories,code',
            'icon' => 'nullable|string|max:255',
        ]);

        if (empty($validated['code'])) {
            $validated['code'] = Str::slug($validated['name']);
        }

        DmsCategory::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'icon' => $validated['icon'] ?? 'document',
            'is_active' => true,
        ]);

        return redirect()->route('dms.categories.index')->with('success', 'Document Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $category = DmsCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:dms_categories,code,' . $category->id,
            'icon' => 'nullable|string|max:255',
        ]);

        $category->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'icon' => $validated['icon'] ?? 'document',
        ]);

        return redirect()->route('dms.categories.index')->with('success', 'Document Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = DmsCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('dms.categories.index')->with('success', 'Document Category deleted successfully.');
    }
}
