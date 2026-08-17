<?php

namespace App\Http\Controllers;

use App\Models\ChequeStatus;
use Illuminate\Http\Request;

class ChequeStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = ChequeStatus::orderBy('name')->get();
        return view('cheque-statuses.index', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cheque_statuses',
            'color_code' => 'nullable|string|max:50',
        ]);

        $validated['is_active'] = $request->has('is_active');

        ChequeStatus::create($validated);

        return redirect()->route('cheque-statuses.index')
            ->with('success', 'Cheque Status created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChequeStatus $chequeStatus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cheque_statuses,name,' . $chequeStatus->id,
            'color_code' => 'nullable|string|max:50',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $chequeStatus->update($validated);

        return redirect()->route('cheque-statuses.index')
            ->with('success', 'Cheque Status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChequeStatus $chequeStatus)
    {
        $chequeStatus->delete();

        return redirect()->route('cheque-statuses.index')
            ->with('success', 'Cheque Status deleted successfully.');
    }
}
