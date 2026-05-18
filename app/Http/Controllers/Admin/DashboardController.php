<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WheelerCategory;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Payment;
use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with statistics
     */
    public function index()
    {
        // KPI Cards
        $totalCategories = WheelerCategory::count();

        // FIX: Count from Mechanic model, not User
        $activeMechanics = Mechanic::where('status', 'active')->count();

        $availableServices = Service::where('status', 'active')->count();

        $completedRequests = ServiceRequest::where('status', 'completed')->count();

        $totalRequests = ServiceRequest::count();

        $newThisWeek = ServiceRequest::whereBetween('created_at', [
            now()->startOfWeek(), now()->endOfWeek()
        ])->count();

        // Revenue This Month — sum only verified payments
        $revenueThisMonth = Payment::where('status', 'verified')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Revenue Last Month
        $revenueLastMonth = Payment::where('status', 'verified')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');

        // Recent Service Requests
        $recentRequests = ServiceRequest::with(['vehicle', 'services', 'mechanic'])
            ->latest()
            ->paginate(5);
            

        // Top Services by Actual Revenue This Month
        $topServices = DB::table('services')
            ->join('service_service_request', 'services.id', '=', 'service_service_request.service_id')
            ->join('service_requests', 'service_service_request.service_request_id', '=', 'service_requests.id')
            ->join('payments', 'service_requests.id', '=', 'payments.service_request_id')
            ->where('payments.status', 'verified')
            ->whereMonth('payments.created_at', Carbon::now()->month)
            ->whereYear('payments.created_at', Carbon::now()->year)
            ->select('services.name', DB::raw('SUM(payments.amount) as total'))
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('total')
            ->take(5)
            ->get()
            ->pluck('total', 'name');

        $maxServiceRevenue = $topServices->max() ?: 1;

        // Lists for KPI modals
        $categoriesList = WheelerCategory::withCount('services')->get();

        // FIX: Pull from Mechanic model with correct fields
        $mechanicsList = Mechanic::where('status', 'active')
            ->get(['id', 'name', 'email', 'phone', 'specialization']);

        $servicesList = Service::with('wheelerCategory')->where('status', 'active')->get();

        $completedList = ServiceRequest::where('status', 'completed')
            ->with(['user', 'services', 'mechanicAssignments.mechanic'])
            ->latest('completed_date')->take(20)->get();

        $allRequestsList = ServiceRequest::with(['user', 'services'])
            ->latest()->take(20)->get();

        $inProgressList = ServiceRequest::where('status', 'in_progress')
            ->with(['user', 'services', 'mechanicAssignments.mechanic'])
            ->latest()->get();

        $pendingPaymentList = ServiceRequest::where('status', 'completed')
            ->with(['user', 'services', 'payments'])
            ->latest()->get()
            ->filter(function ($sr) {
                $verified = $sr->payments->where('status', 'verified')->sum('amount');
                return $sr->total_amount > 0 && $verified < $sr->total_amount;
            })->values();

        $revenueBreakdown = DB::table('services')
            ->join('service_service_request', 'services.id', '=', 'service_service_request.service_id')
            ->join('service_requests', 'service_service_request.service_request_id', '=', 'service_requests.id')
            ->join('payments', 'service_requests.id', '=', 'payments.service_request_id')
            ->where('payments.status', 'verified')
            ->whereMonth('payments.created_at', Carbon::now()->month)
            ->whereYear('payments.created_at', Carbon::now()->year)
            ->select('services.name', DB::raw('SUM(payments.amount) as total'))
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('total')
            ->get();

        return view('admin.dashboard', compact(
            'totalCategories',
            'activeMechanics',
            'availableServices',
            'completedRequests',
            'totalRequests',
            'newThisWeek',
            'revenueThisMonth',
            'revenueLastMonth',
            'recentRequests',
            'topServices',
            'maxServiceRevenue',
            'categoriesList',
            'mechanicsList',
            'servicesList',
            'completedList',
            'allRequestsList',
            'inProgressList',
            'pendingPaymentList',
            'revenueBreakdown'
        ));
    }
}
