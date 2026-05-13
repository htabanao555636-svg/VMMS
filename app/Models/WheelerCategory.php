<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WheelerCategory extends Model
{
    use HasFactory;

    protected $table = 'wheeler_categories';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    /**
     * ============= RELATIONSHIPS =============
     */

    /**
     * Get all services in this category
     */
    public function services()
    {
        return $this->hasMany(Service::class, 'wheeler_category_id');
    }

    /**
     * ============= SCOPES =============
     */

    /**
     * Get only active wheeler categories
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
