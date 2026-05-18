<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WheelerCategory;
use Illuminate\Http\Request;

class WheelerCategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index(Request $request)
    {
        $wheelerCategories = WheelerCategory::paginate(15)->appends(request()->query());
        $activeCount       = WheelerCategory::where('status', 'active')->count();
        $inactiveCount     = WheelerCategory::where('status', 'inactive')->count();

        return view('admin.category', compact('wheelerCategories', 'activeCount', 'inactiveCount'));
    }

    public function storeWheelerCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:wheeler_categories',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        WheelerCategory::create($validated);

        return redirect()->route('admin.categories')->with('success', 'Wheeler category added successfully');
    }

    /**
     * Update the specified wheeler category
     */
    public function updateWheelerCategory(Request $request, WheelerCategory $wheelerCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:wheeler_categories,name,' . $wheelerCategory->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $wheelerCategory->update($validated);

        return redirect()->route('admin.categories')->with('success', 'Wheeler category updated successfully');
    }

    /**
     * Remove the specified wheeler category
     */
    public function destroyWheelerCategory(WheelerCategory $wheelerCategory)
    {
        $wheelerCategory->delete();
        return redirect()->route('admin.categories')->with('success', 'Wheeler category deleted successfully');
    }
}
