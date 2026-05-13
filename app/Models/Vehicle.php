<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_type',
        'model',
        'plate_number',
        'color',
        'year',
        'engine_number',
        'chassis_number',
        'status',
    ];

    /**
     * ============= RELATIONSHIPS =============
     */

    /**
     * Get the owner of this vehicle
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all service requests for this vehicle
     */
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    /**
     * ============= SCOPES =============
     */

    /**
     * Get only active vehicles
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * ============= ACCESSORS/MUTATORS =============
     */

    /**
     * Get formatted vehicle display name
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->model} ({$this->plate_number})";
    }
}
