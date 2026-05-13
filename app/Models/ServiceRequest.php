<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'mechanic_id',
        'assigned_by',
        'status',
        'request_type',
        'address',
        'vehicle_name',
        'vehicle_model',
        'vehicle_registration',
        'vehicle_type',
        'notes',
        'staff_notes',
        'requested_date',
        'completed_date',
        'assigned_at',
        'total_amount',
        'downpayment_amount',
        'remaining_balance',
        'downpayment_percentage',
        'proof_of_payment',
        'payment_status',
        'payment_type',
        'full_payment_proof',
    ];
    protected $casts = [
        'requested_date' => 'date',
        'completed_date' => 'date',
        'assigned_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'downpayment_amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
    ];

    /**
     * ============= RELATIONSHIPS =============
     */

    /**
     * Get the customer (user) who made this request
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Alias for customer - get the user who made this request
     */
    public function user()
    {
        return $this->customer();
    }

    /**
     * Get the vehicle for this service request
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the mechanic assigned to this request (if any)
     */
    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }

    /**
     * Get the staff member who assigned this request
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Get all services for this request (Many-to-Many)
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_service_request');
    }

    /**
     * Get all payments for this request
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all status history records for this request
     */
    public function statusHistory()
    {
        return $this->hasMany(RequestStatus::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get all mechanic assignments for this request
     */
    public function mechanicAssignments()
    {
        return $this->hasMany(RequestAssignment::class);
    }

    /**
     * ============= SCOPES =============
     */

    /**
     * Get pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Get in-progress requests
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Get completed requests
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get cancelled requests
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Get requests with pending downpayments
     */
    public function scopePendingDownpayment($query)
    {
        return $query->whereHas('payments', function ($subQuery) {
            $subQuery->where('payment_type', 'downpayment')
                    ->where('status', 'pending');
        });
    }

    /**
     * ============= ACCESSORS/MUTATORS =============
     */

    /**
     * Get payment status
     */
    public function getComputedPaymentStatusAttribute(): string
    {
        if (!$this->payments()->exists()) {
            return 'no_payments';
        }

        $downpayments = $this->payments()
            ->where('payment_type', 'downpayment')
            ->get();

        if ($downpayments->isEmpty()) {
            return 'pending';
        }

        $verified = $downpayments->where('status', 'verified')->isNotEmpty();
        $rejected = $downpayments->where('status', 'rejected')->isNotEmpty();

        if ($rejected) {
            return 'payment_rejected';
        }

        return $verified ? 'payment_verified' : 'payment_pending';
    }

    /**
     * Get total paid amount
     */
    public function getTotalPaidAttribute(): float
    {
        return $this->payments()
            ->where('status', 'verified')
            ->sum('amount');
    }

    /**
     * Check if downpayment is verified
     */
    public function getDownpaymentVerifiedAttribute(): bool
    {
        return $this->payments()
            ->where('payment_type', 'downpayment')
            ->where('status', 'verified')
            ->exists();
    }

    /**
     * ============= HELPERS =============
     */

    /**
     * Calculate and update total amount from selected services
     */
    public function calculateTotal(): void
    {
        $total = $this->services()->sum('price');
        
        $downpaymentAmount = $this->downpayment_percentage 
            ? $total * ($this->downpayment_percentage / 100)
            : 0;
        
        $remainingBalance = $total - $downpaymentAmount;

        $this->update([
            'total_amount' => $total,
            'downpayment_amount' => $downpaymentAmount,
            'remaining_balance' => $remainingBalance,
        ]);
    }

    /**
     * Update status with history
     */
    public function updateStatus(string $newStatus, ?User $changedBy = null, ?string $notes = null): void
    {
        // Record status change
        RequestStatus::create([
            'service_request_id' => $this->id,
            'status' => $newStatus,
            'notes' => $notes,
            'changed_by' => $changedBy?->id,
        ]);

        // Update the main status
        $this->update(['status' => $newStatus]);
    }

    /**
     * Assign mechanic
     */
    public function assignMechanic(Mechanic $mechanic, ?string $notes = null): RequestAssignment
    {
        return RequestAssignment::create([
            'service_request_id' => $this->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'assigned',
            'assigned_at' => now(),
            'notes' => $notes,
        ]);
    }

    /**
     * Create payment record
     */
    public function createPayment(float $amount, string $paymentType = 'downpayment', ?string $proofImage = null): Payment
    {
        return Payment::create([
            'service_request_id' => $this->id,
            'amount' => $amount,
            'payment_type' => $paymentType,
            'proof_image' => $proofImage,
            'status' => 'pending',
        ]);
    }

    /**
     * Get the latest status
     */
    public function getLatestStatus(): ?RequestStatus
    {
        return $this->statusHistory()->first();
    }

    /**
     * Check if all services are completed
     */
    public function areServicesCompleted(): bool
    {
        return $this->mechanicAssignments()
            ->where('status', 'completed')
            ->count() === $this->services()->count();
    }

    /**
     * ============= QR CODE METHODS =============
     */

    /**
     * Generate QR code data for this service request
     * Returns URL that customer can scan to access payment tracking
     */
    public function getQrCodeData(): string
    {
        $baseUrl = rtrim(config('app.url'), '/');
        return "{$baseUrl}/payment/{$this->id}";
    }

    /**
     * Generate QR code URL using QR code service
     * Can be displayed as an image
     */
    public function getQrCodeUrl(): string
    {
        // Return the uploaded payment QR code image
        return asset('images/payment-qr.jpg');
    }
}
