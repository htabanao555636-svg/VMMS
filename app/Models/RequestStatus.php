<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestStatus extends Model
{
    use HasFactory;

    protected $table = 'request_statuses';

    protected $fillable = [
        'service_request_id',
        'status',
        'notes',
        'changed_by',
    ];

    /**
     * ============= RELATIONSHIPS =============
     */

    /**
     * Get the service request this status belongs to
     */
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Get the staff member who changed the status
     */
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * ============= SCOPES =============
     */

    /**
     * Get the latest status for a request
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
