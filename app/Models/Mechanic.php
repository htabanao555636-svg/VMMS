<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'specialization',
        'certificate_path',
        'status',
        'date_added',
    ];

    protected $casts = [
        'date_added' => 'date',
    ];

    /**
     * Get all service requests assigned to this mechanic
     */
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    /**
     * Get all work assignments for this mechanic
     */
    public function assignments()
    {
        return $this->hasMany(RequestAssignment::class);
    }

    /**
     * Get active assignments
     */
    public function activeAssignments()
    {
        return $this->assignments()->active();
    }

    /**
     * Get completed assignments
     */
    public function completedAssignments()
    {
        return $this->assignments()->completed();
    }
}
