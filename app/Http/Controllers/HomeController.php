<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\WheelerCategory;


class HomeController extends Controller
{
    /**
     * Show the home page with services and categories
     */
    public function index()
    {
         $categories = WheelerCategory::where('status', 'active')
            ->orderBy('name')
            ->get();

             $services = Service::where('status', 'active')
        ->with('wheelerCategory')
        ->orderBy('name')
        ->get();

    return view('user.home', compact('categories', 'services'));

    }
        

    /**
     * Get wheeler categories as JSON (for dynamic form population)
     */
    public function getWheelerCategories()
    {
        $categories = WheelerCategory::where('status', 'active')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'categories' => $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'value' => $category->id,
                    'label' => $category->name,
                    'description' => $category->description
                ];
            })
        ]);
    }
}
