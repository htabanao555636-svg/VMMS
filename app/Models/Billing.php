<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'customer_id',
        'total_amount',
        'downpayment_amount',
        'remaining_balance',
        'payment_status',
        'rejection_reason',
        'verified_by',
        'verified_at',
        'collected_by',
        'collected_at',
    ];

    protected $casts = [
        'total_amount'       => 'decimal:2',
        'downpayment_amount' => 'decimal:2',
        'remaining_balance'  => 'decimal:2',
        'verified_at'        => 'datetime',
        'collected_at'       => 'datetime',
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function collectedBy()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'unpaid');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'downpayment_pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('payment_status', 'downpayment_verified');
    }

    public function scopeBalancePending($query)
    {
        return $query->where('payment_status', 'balance_pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('payment_status', 'rejected');
    }

    public function scopeFullyPaid($query)
    {
        return $query->where('payment_status', 'fully_paid');
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Check if downpayment is verified
     */
    public function getIsVerifiedAttribute(): bool
    {
        return in_array($this->payment_status, ['downpayment_verified', 'fully_paid']);
    }

    /**
     * Check if fully paid
     */
    public function getIsFullyPaidAttribute(): bool
    {
        return $this->payment_status === 'fully_paid';
    }

    /**
     * Check if has remaining balance
     */
    public function getHasRemainingBalanceAttribute(): bool
    {
        return ($this->remaining_balance ?? 0) > 0;
    }

    /**
     * Get human readable payment status
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'unpaid'               => 'Unpaid',
            'downpayment_pending'  => 'Pending Verification',
            'downpayment_verified' => 'Downpayment Verified',
            'balance_pending'      => 'Balance Pending Verification',
            'rejected'             => 'Rejected',
            'fully_paid'           => 'Fully Paid',
            default                => 'Unknown',
        };
    }
}
