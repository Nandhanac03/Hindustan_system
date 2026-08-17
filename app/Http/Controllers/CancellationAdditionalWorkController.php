<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CancellationAdditionalWorkController extends Controller
{
    public function index()
    {
        $cancellationCharges = \App\Models\Sale::where('status', 'cancelled')
            ->with(['customer', 'unit'])
            ->get();

        $additionalWorks = \App\Models\SaleExtraWork::with(['sale.customer', 'sale.unit'])->get();

        return view('cancellation-additional-work.index', compact('cancellationCharges', 'additionalWorks'));
    }
}
