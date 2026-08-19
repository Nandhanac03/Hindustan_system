<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Floor;
use App\Models\UnitType;
use App\Models\Customer;
use App\Models\Sale;

class UnitMatrixController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::orderBy('name')->get();
        $selectedProjectId = $request->input('project', $request->input('project_id', Project::where('is_active', true)->value('id') ?? ($projects->first()?->id)));

        $project = Project::find($selectedProjectId) ?? $projects->first();

        if (!$project) {
            abort(404, 'No active project found for this system.');
        }

        $floors = Floor::where('project_id', $project->id)->orderBy('floor_number')->get();
        $unitTypes = UnitType::where('is_active', true)
            ->where(function ($q) use ($project) {
                $q->whereNull('project_id')->orWhere('project_id', $project->id);
            })
            ->get();
        $customers = Customer::orderBy('name')->get();

        $floorMatrix = [];
        $parkingRows = [];
        $matrixColumns = [];

        $selectedCustomerId = $request->input('customer_id');
        $selectedCustomerIds = is_array($selectedCustomerId) ? $selectedCustomerId : ($selectedCustomerId ? [$selectedCustomerId] : []);

        $matrixFloors = Floor::where('project_id', $project->id)
            ->orderBy('floor_number', 'desc')
            ->with(['units' => function($q) use ($selectedCustomerIds) {
                $q->with(['booking', 'unitType', 'sale.customer', 'saleUnits.sale.customer'])
                  ->orderBy('door_no');
                if (!empty($selectedCustomerIds)) {
                    $q->where(function($uq) use ($selectedCustomerIds) {
                        $uq->whereHas('sale', function ($sq) use ($selectedCustomerIds) {
                            $sq->whereIn('customer_id', $selectedCustomerIds)
                               ->where('status', 'active');
                        })->orWhereHas('saleUnits.sale', function ($sq) use ($selectedCustomerIds) {
                            $sq->whereIn('customer_id', $selectedCustomerIds)
                               ->where('status', 'active');
                        });
                    });
                }
            }])
            ->get();

        $regularFloors = [];
        foreach ($matrixFloors as $floor) {
            $isParking = false;
            if (stripos($floor->name, 'parking') !== false || stripos($floor->name, 'basement') !== false) {
                $isParking = true;
            } else {
                $parkingUnitsCount = $floor->units->where('unit_type_id', 5)->count();
                if ($floor->units->isNotEmpty() && $parkingUnitsCount === $floor->units->count()) {
                    $isParking = true;
                }
            }

            if ($isParking) {
                $rowUnits = $floor->units->sortBy('door_no')->values();
                $parkingRows[] = [
                    'floor'        => $floor,
                    'display_name' => $floor->name,
                    'units'        => $rowUnits,
                ];
            } else {
                $regularFloors[] = $floor;
            }
        }

        $allDoorNos = collect();
        foreach ($regularFloors as $floor) {
            foreach ($floor->units as $unit) {
                $allDoorNos->push($unit->door_no);
            }
        }
        
        $matrixColumns = $allDoorNos->unique()->sortBy(function($doorNo) {
            return [strlen($doorNo), $doorNo];
        })->values()->toArray();

        foreach ($regularFloors as $floor) {
            $unitsByDoor = $floor->units->keyBy('door_no');
            $cols = [];
            foreach ($matrixColumns as $doorNo) {
                $cols[$doorNo] = $unitsByDoor->get($doorNo);
            }

            $floorMatrix[] = [
                'floor'        => $floor,
                'display_name' => $floor->name,
                'columns'      => $cols,
            ];
        }

        $parkingRows = array_map(function($pRow, $index) {
            $pRow['display_name'] = 'P' . ($index + 1);
            return $pRow;
        }, $parkingRows, array_keys($parkingRows));

        $salesList = Sale::with([
            'customer', 
            'unit.unitType', 
            'unit.floor', 
            'project', 
            'broker', 
            'saleUnits.unit.unitType', 
            'saleUnits.unit.floor',
            'extraWorks'
        ])
        ->where('project_id', $project->id)
        ->where('status', 'active')
        ->when(!empty($selectedCustomerIds), fn($q) => $q->whereIn('customer_id', $selectedCustomerIds))
        ->orderByDesc('sale_date')
        ->get();

        return view('unit-matrix.index', compact('project', 'projects', 'floorMatrix', 'parkingRows', 'matrixColumns', 'floors', 'unitTypes', 'customers', 'salesList'));
    }
}
