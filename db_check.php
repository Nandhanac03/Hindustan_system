<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Receipt;
$r = Receipt::with(['customer', 'sale.project', 'sale.unit'])->first();
if ($r) {
    echo "ID: " . $r->id . "\n";
    echo "Customer: " . ($r->customer?->name ?? 'None') . "\n";
    echo "Project: " . ($r->sale?->project?->name ?? 'None') . "\n";
    echo "Unit: " . ($r->sale?->unit?->door_no ?? 'None') . "\n";
} else {
    echo "No receipts found.\n";
}
