<?php

use App\Helpers\StatusHelper;

/**
 * Get Font Awesome icon for service request status
 */
if (!function_exists('getStatusIcon')) {
    function getStatusIcon($status)
    {
        return StatusHelper::getStatusIcon($status);
    }
}

/**
 * Get badge color for service request status
 */
if (!function_exists('getStatusColor')) {
    function getStatusColor($status)
    {
        return StatusHelper::getStatusColor($status);
    }
}

/**
 * Get human readable status label
 */
if (!function_exists('getStatusLabel')) {
    function getStatusLabel($status)
    {
        return StatusHelper::getStatusLabel($status);
    }
}
