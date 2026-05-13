<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'wheeler_category_id',
        'price',
        'description',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

   
    public function wheelerCategory()
    {
        return $this->belongsTo(WheelerCategory::class);
    }

    public function category()
    {
    return $this->belongsTo(WheelerCategory::class, 'wheeler_category_id');
    }

    /**
     * Get all service requests for this service
     */
    public function serviceRequests()
    {
        return $this->belongsToMany(ServiceRequest::class, 'service_service_request');
    }
    /**
     * ============= SCOPES =============
     */

    /**
     * Get only active services
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get services by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Get services within a price range
     */
    public function scopePriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    /**
     * ============= ACCESSORS/MUTATORS =============
     */

    /**
     * Get formatted price with currency
     */
    public function getFormattedPriceAttribute(): string
    {
        return '₱' . number_format((float) $this->price ?? 0, 2);
    }}
