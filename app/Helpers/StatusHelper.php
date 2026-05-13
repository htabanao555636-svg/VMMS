<?php

namespace App\Helpers;

class StatusHelper
{
    /**
     * Get Font Awesome icon class for status
     */
    public static function getStatusIcon($status)
    {
        return match ($status) {
            'pending' => 'hourglass-start',
            'approved' => 'check-circle',
            'in_progress' => 'spinner',
            'completed' => 'check-double',
            'cancelled' => 'times-circle',
            // Payment statuses
            'downpayment_pending' => 'hourglass-start',
            'downpayment_verified' => 'check-circle',
            'balance_pending' => 'hourglass-half',
            'fully_paid' => 'check-double',
            'rejected' => 'times-circle',
            default => 'question-circle',
        };
    }

    /**
     * Get badge color class for status
     */
    public static function getStatusColor($status)
    {
        return match ($status) {
            'pending' => 'warning',
            'approved' => 'info',
            'in_progress' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            // Payment statuses
            'downpayment_pending' => 'warning',
            'downpayment_verified' => 'info',
            'balance_pending' => 'secondary',
            'fully_paid' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get human readable status label
     */
    public static function getStatusLabel($status)
    {
        return match ($status) {
            'pending' => 'Pending',
            'approved' => 'Approved',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            // Payment statuses
            'downpayment_pending' => 'Downpayment Pending',
            'downpayment_verified' => 'Downpayment Verified',
            'balance_pending' => 'Balance Pending',
            'fully_paid' => 'Fully Paid',
            'rejected' => 'Rejected',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }
}
