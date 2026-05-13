<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'mechanic_id',
        'status',
        'assigned_at',
        'started_at',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * ============= RELATIONSHIPS =============
     */

    /**
     * Get the service request this assignment belongs to
     */
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Get the mechanic assigned to this task
     */
    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }

    /**
     * ============= SCOPES =============
     */

    /**
     * Get active assignments
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['assigned', 'working']);
    }

    /**
     * Get completed assignments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * ============= HELPERS =============
     */

    /**
     * Start working on the assignment
     */
    public function start(): void
    {
        $this->update([
            'status' => 'working',
            'started_at' => now(),
        ]);
    }

    /**
     * Complete the assignment
     */
    public function complete(?string $notes = null): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'notes' => $notes,
        ]);
    }

    /**
     * Cancel the assignment
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}
