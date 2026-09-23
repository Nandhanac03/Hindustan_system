<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CancellationAdditionalWorkController extends Controller
{
    public function index()
    {
        $cancellationCharges = \App\Models\Sale::where('status', 'cancelled')
            ->with(['customer', 'project', 'unit.unitType', 'unit.floor', 'saleUnits.unit.unitType', 'saleUnits.unit.floor'])
            ->get();

        $additionalWorks = \App\Models\SaleExtraWork::with(['sale.customer', 'sale.project', 'sale.unit.unitType', 'sale.unit.floor', 'sale.saleUnits.unit.unitType', 'sale.saleUnits.unit.floor'])->get();

        $projects = \App\Models\Project::orderBy('name')->get();

        return view('cancellation-additional-work.index', compact('cancellationCharges', 'additionalWorks', 'projects'));
    }
}
