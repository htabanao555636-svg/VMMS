<?php

namespace App\Helpers;

class PaymentStatusHelper
{
    /**
     * Get Font Awesome icon for payment status
     */
    public static function getPaymentStatusIcon($status)
    {
        return match ($status) {
            'downpayment_pending' => 'hourglass-start',
            'downpayment_verified' => 'check-circle',
            'balance_pending' => 'hourglass-half',
            'fully_paid' => 'check-double',
            'rejected' => 'times-circle',
            'unpaid' => 'ban',
            default => 'question-circle',
        };
    }

    /**
     * Get badge color for payment status
     */
    public static function getPaymentStatusColor($status)
    {
        return match ($status) {
            'downpayment_pending' => 'warning',      // Yellow
            'downpayment_verified' => 'info',        // Blue
            'balance_pending' => 'secondary',        // Gray
            'fully_paid' => 'success',               // Green
            'rejected' => 'danger',                  // Red
            'unpaid' => 'dark',                      // Dark
            default => 'secondary',
        };
    }

    /**
     * Get human-readable label for payment status
     */
    public static function getPaymentStatusLabel($status)
    {
        return match ($status) {
            'downpayment_pending' => 'Downpayment Pending',
            'downpayment_verified' => 'Downpayment Verified',
            'balance_pending' => 'Balance Payment Pending',
            'fully_paid' => 'Fully Paid',
            'rejected' => 'Rejected',
            'unpaid' => 'Unpaid',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    /**
     * Get available actions for a payment status
     */
    public static function getAvailableActions($status)
    {
        return match ($status) {
            'downpayment_pending' => ['verify', 'reject'],
            'downpayment_verified' => ['collect_balance'],
            'balance_pending' => ['verify', 'reject'],
            'fully_paid' => ['view'],
            'rejected' => ['view'],
            'unpaid' => ['verify', 'reject'],
            default => [],
        };
    }

    /**
     * Get action button configuration
     */
    public static function getActionButton($action)
    {
        return match ($action) {
            'verify' => [
                'label' => '✅ Verify',
                'class' => 'btn-success',
                'icon' => 'check',
            ],
            'reject' => [
                'label' => '❌ Reject',
                'class' => 'btn-danger',
                'icon' => 'times',
            ],
            'collect_balance' => [
                'label' => '💰 Collect Balance',
                'class' => 'btn-info',
                'icon' => 'money-bill',
            ],
            'view' => [
                'label' => '👁️ View Only',
                'class' => 'btn-secondary',
                'icon' => 'eye',
            ],
            default => [
                'label' => 'Action',
                'class' => 'btn-secondary',
                'icon' => 'question',
            ],
        };
    }

    /**
     * Get description for payment status
     */
    public static function getPaymentStatusDescription($status)
    {
        return match ($status) {
            'downpayment_pending' => 'Awaiting admin verification of downpayment proof',
            'downpayment_verified' => 'Downpayment verified. Ready for service assignment.',
            'balance_pending' => 'Awaiting admin verification of full payment proof',
            'fully_paid' => 'Payment completed. Service request is fully paid.',
            'rejected' => 'Payment proof was rejected. Please resubmit.',
            'unpaid' => 'No payment submitted yet',
            default => 'Unknown status',
        };
    }

    /**
     * Get next status in workflow
     */
    public static function getNextStatus($currentStatus, $isFullPayment = false)
    {
        return match ($currentStatus) {
            'downpayment_pending' => 'downpayment_verified',
            'downpayment_verified' => $isFullPayment ? 'balance_pending' : 'balance_pending',
            'balance_pending' => 'fully_paid',
            'rejected' => 'downpayment_pending', // Can resubmit
            default => null,
        };
    }
}
