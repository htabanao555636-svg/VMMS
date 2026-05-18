<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Mechanic;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the staff dashboard with statistics
     */
    public function index()
    {
        // Get today's date
        $today = Carbon::today();
        
        // Total Service Requests today
        $totalServiceRequestsToday = ServiceRequest::whereDate('created_at', $today)->count();
        
        // Pending Payments count (unpaid or downpayment_pending)
        $pendingPaymentsCount = ServiceRequest::whereIn('payment_status', [
            'unpaid',
            'downpayment_pending',
            'rejected',
        ])->count();
        
        // Verified Payments count
        $verifiedPaymentsCount = ServiceRequest::where('payment_status', 'downpayment_verified')->count();
        
        // Active Mechanics count
        $activeMechanicsCount = Mechanic::where('status', 'active')->count();

        return view('staff.dashboard', compact(
            'totalServiceRequestsToday',
            'pendingPaymentsCount',
            'verifiedPaymentsCount',
            'activeMechanicsCount'
        ));
    }
}
