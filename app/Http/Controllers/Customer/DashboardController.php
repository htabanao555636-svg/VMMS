<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\WheelerCategory;

class DashboardController extends Controller
{
    public function index()
    {
        $categories = WheelerCategory::where('status', 'active')
            ->orderBy('name')
            ->get();

        $services = Service::where('status', 'active')
            ->with('wheelerCategory')
            ->orderBy('name')
            ->get();

        return view('Customer.dashboard', compact('categories', 'services'));
    }

    public function getWheelerCategories()
    {
        $categories = WheelerCategory::where('status', 'active')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'categories' => $categories->map(function ($category) {
                return [
                    'id'          => $category->id,
                    'value'       => $category->id,
                    'label'       => $category->name,
                    'description' => $category->description,
                ];
            })
        ]);
    }
}