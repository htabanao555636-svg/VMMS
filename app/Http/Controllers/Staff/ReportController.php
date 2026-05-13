<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display report page
     */
    public function index(Request $request)
    {
        // Get all service requests for reporting
        $query = ServiceRequest::with(['customer', 'vehicle', 'services', 'mechanic']);

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range if provided
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $serviceRequests = $query->orderBy('created_at', 'desc')->paginate(15);

        // Calculate summary stats
        $stats = [
            'total_requests' => ServiceRequest::count(),
            'completed_requests' => ServiceRequest::where('status', 'completed')->count(),
            'pending_requests' => ServiceRequest::where('status', 'pending')->count(),
            'in_progress_requests' => ServiceRequest::where('status', 'in_progress')->count(),
        ];

        return view('Staff.report', compact('serviceRequests', 'stats'));
    }
}
