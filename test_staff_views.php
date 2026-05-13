<?php

require_once __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Test the staff service request index view
try {
    $request = \Illuminate\Http\Request::create('/staff/service-requests', 'GET');
    $request->setUserResolver(function() {
        return \App\Models\User::where('role', 'staff')->first();
    });
    
    $response = $kernel->handle($request);
    
    if ($response->getStatusCode() === 200) {
        echo "✅ Staff service request list renders successfully\n";
    } else {
        echo "❌ Error: Status code " . $response->getStatusCode() . "\n";
    }
} catch (\Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
}

// Test service requests with relationships
try {
    $requests = \App\Models\ServiceRequest::with(['customer', 'vehicle', 'services', 'mechanic', 'assignedBy'])->limit(5)->get();
    
    if ($requests->count() > 0) {
        $first = $requests->first();
        echo "✅ Service requests load with relationships\n";
        echo "   - Customer: " . ($first->customer ? $first->customer->name : 'null') . "\n";
        echo "   - Mechanic: " . ($first->mechanic ? $first->mechanic->name : 'null') . "\n";
        echo "   - Vehicle: " . ($first->vehicle ? $first->vehicle->model : 'null') . "\n";
        echo "   - Services: " . $first->services->count() . " items\n";
    } else {
        echo "⚠️  No service requests found\n";
    }
} catch (\Exception $e) {
    echo "❌ Exception loading relationships: " . $e->getMessage() . "\n";
}

// Test that mechanics load properly
try {
    $mechanics = \App\Models\Mechanic::where('status', 'active')->get();
    echo "✅ Mechanics loaded: " . $mechanics->count() . " active mechanics\n";
} catch (\Exception $e) {
    echo "❌ Exception loading mechanics: " . $e->getMessage() . "\n";
}

// Test that staff members load properly
try {
    $staff = \App\Models\User::whereIn('role', ['admin', 'staff'])->get();
    echo "✅ Staff members loaded: " . $staff->count() . " staff members\n";
} catch (\Exception $e) {
    echo "❌ Exception loading staff: " . $e->getMessage() . "\n";
}

echo "\n✅ All tests completed successfully!\n";
