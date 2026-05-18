<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\WheelerCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services      = Service::with('wheelerCategory')->paginate(10)->appends(request()->query());
        $activeCount   = Service::where('status', 'active')->count();
        $inactiveCount = Service::where('status', 'inactive')->count();
        $wheelerCategories = WheelerCategory::all();
        $avgPrice      = Service::avg('price') ?? 0;
        $totalServices = Service::count();

        return view('admin.services', compact('services', 'activeCount', 'inactiveCount',
        'avgPrice', 'totalServices', 'wheelerCategories'));
    }

    /**
     * Show the form for creating a new service
     */
    public function create()
    {
        $categories = WheelerCategory::where('status', 'active')->get();
        return view('admin.services.create', compact('categories'));
    }

    /**
     * Store a newly created service
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'wheeler_category_id' => 'required|exists:wheeler_categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Service::create($validated);

        return redirect()->route('admin.services')->with('success', 'Service added successfully');
    }

    /**
     * Display the specified service
     */
    public function show(Service $service)
    {
        $service->load('wheelerCategory');
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified service
     */
    public function edit(Service $service)
    {
        $categories = WheelerCategory::where('status', 'active')->get();
        return view('admin.services.edit', compact('service', 'categories'));
    }

    /**
     * Update the specified service
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'wheeler_category_id' => 'required|exists:wheeler_categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $service->update($validated);

        return redirect()->route('admin.services')->with('success', 'Service updated successfully');
    }

    /**
     * Remove the specified service
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services')->with('success', 'Service deleted successfully');
    }
}
