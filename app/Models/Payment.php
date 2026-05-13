<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Payment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'service_request_id',
        'amount',
        'payment_type',
        'status',
        'proof_image',
        'proof_notes',
        'payment_method',
        'reference_number',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    /**
     * ============= RELATIONSHIPS =============
     */

    /**
     * Get the service request this payment belongs to
     */
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Get the staff member who verified this payment
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * ============= SCOPES =============
     */

    /**
     * Get only verified payments
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Get only pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get downpayments
     */
    public function scopeDownpayments($query)
    {
        return $query->where('payment_type', 'downpayment');
    }

    /**
     * ============= HELPERS =============
     */

    /**
     * Mark payment as verified
     */
    public function verify(User $verifier, ?string $notes = null): void
    {
        $this->update([
            'status' => 'verified',
            'verified_by' => $verifier->id,
            'verified_at' => now(),
            'proof_notes' => $notes,
        ]);
    }

    /**
     * Mark payment as rejected
     */
    public function reject(?string $reason = null): void
    {
        $this->update([
            'status' => 'rejected',
            'proof_notes' => $reason,
        ]);
    }
}
