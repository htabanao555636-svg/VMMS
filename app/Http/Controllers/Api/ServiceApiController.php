<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\WheelerCategory;
use Illuminate\Http\JsonResponse;

class ServiceApiController extends Controller
{
    /**
     * Get all active services
     * GET /api/services
     */
    public function getServices(): JsonResponse
    {
        try {
            $services = Service::where('status', 'active')
                ->with('wheelerCategory')
                ->get()
                ->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'name' => $service->name,
                        'price' => (float) $service->price,
                        'description' => $service->description,
                        'wheeler_category_id' => $service->wheeler_category_id,
                        'wheeler_category_name' => $service->wheelerCategory?->name ?? 'General',
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $services,
                'count' => $services->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load services',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all active wheeler categories
     * GET /api/wheeler-categories
     */
    public function getWheelerCategories(): JsonResponse
    {
        try {
            $categories = WheelerCategory::where('status', 'active')
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'description' => $category->description,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $categories,
                'count' => $categories->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load wheeler categories',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get services filtered by wheeler category
     * GET /api/services/by-category/{categoryId}
     */
    public function getServicesByCategory($categoryId): JsonResponse
    {
        try {
            $services = Service::where('status', 'active')
                ->where('wheeler_category_id', $categoryId)
                ->get()
                ->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'name' => $service->name,
                        'price' => (float) $service->price,
                        'description' => $service->description,
                        'wheeler_category_id' => $service->wheeler_category_id,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $services,
                'count' => $services->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load services',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
