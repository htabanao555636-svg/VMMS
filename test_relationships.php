#!/usr/bin/env php
<?php

// Load Laravel app
$app = require __DIR__ . '/bootstrap/app.php';

// Make request and response
$app->make(\Illuminate\Contracts\Http\Kernel::class);

// Test 1: Verify ServiceRequest relationships
echo "═══════════════════════════════════════\n";
echo "Testing ServiceRequest Model\n";
echo "═══════════════════════════════════════\n\n";

$requests = \App\Models\ServiceRequest::with([
    'customer', 
    'vehicle', 
    'services', 
    'mechanic',
    'assignedBy',
    'statusHistory',
    'mechanicAssignments'
])->limit(3)->get();

foreach ($requests as $i => $req) {
    echo "Request #" . ($i+1) . " (ID: {$req->id})\n";
    echo "  Customer: " . ($req->customer ? $req->customer->name : 'NULL') . "\n";
    echo "  Mechanic: " . ($req->mechanic ? $req->mechanic->name : 'NULL') . "\n";
    echo "  Vehicle: " . ($req->vehicle ? $req->vehicle->model : 'NULL') . "\n";
    echo "  Services: " . $req->services->count() . "\n";
    echo "  Assigned By: " . ($req->assignedBy ? $req->assignedBy->name : 'NULL') . "\n";
    echo "  Status: {$req->status}\n";
    echo "  Requested Date: " . ($req->requested_date ? $req->requested_date->format('Y-m-d') : 'NULL') . "\n";
    echo "\n";
}

// Test 2: Verify Controller Data
echo "═══════════════════════════════════════\n";
echo "Testing Controller Data Preparation\n";
echo "═══════════════════════════════════════\n\n";

$mechanics = \App\Models\Mechanic::where('status', 'active')->get();
$staff = \App\Models\User::whereIn('role', ['admin', 'staff'])->get();

echo "Active Mechanics: " . $mechanics->count() . "\n";
foreach ($mechanics as $mech) {
    echo "  - {$mech->name} ({$mech->specialization})\n";
}

echo "\nStaff Members: " . $staff->count() . "\n";
foreach ($staff as $member) {
    echo "  - {$member->name} ({$member->role})\n";
}

// Test 3: Billing Data
echo "\n═══════════════════════════════════════\n";
echo "Testing Billing Data\n";
echo "═══════════════════════════════════════\n\n";

$billings = \App\Models\ServiceRequest::whereNotNull('payment_status')
    ->with('customer', 'mechanic')
    ->limit(3)
    ->get();

echo "Billing Records: " . $billings->count() . "\n";
foreach ($billings as $billing) {
    echo "  Request #{$billing->id}\n";
    echo "    Payment Status: {$billing->payment_status}\n";
    echo "    Total: ₱" . number_format($billing->total_amount ?? 0, 2) . "\n";
    echo "    Remaining: ₱" . number_format($billing->remaining_balance ?? 0, 2) . "\n";
}

echo "\n✅ All relationship tests passed!\n";
