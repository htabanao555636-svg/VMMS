<?php

use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\ServiceRequestController;
use App\Http\Controllers\Customer\BillingController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\CustomerController;
use Illuminate\Support\Facades\Route;

Route::prefix('customer')
    ->middleware(['auth', 'customer'])
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');

        // My Services, Payables, and Payment History
        Route::get('/services', [CustomerController::class, 'myServices'])->name('services');
        Route::get('/payables', [CustomerController::class, 'myPayables'])->name('payables');
        Route::get('/payments', [CustomerController::class, 'payments'])->name('payments');
        Route::post('/payables/pay', [CustomerController::class, 'payRemaining'])->name('payables.pay');
        Route::post('/services/pay-full', [CustomerController::class, 'payFull'])->name('services.pay-full');

        // Service Requests (Legacy - kept for backward compatibility)
        Route::get('/service-requests', [ServiceRequestController::class, 'index'])->name('service-request');
        Route::post('/service-requests', [ServiceRequestController::class, 'store'])->name('service-request.store');
        Route::get('/service-requests/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('service-request.show');
        Route::post('/service-requests/{serviceRequest}/upload-proof', [ServiceRequestController::class, 'uploadProof'])->name('service-request.upload-proof');
        Route::post('/service-requests/{serviceRequest}/upload-full-payment-proof', [ServiceRequestController::class, 'uploadFullPaymentProof'])->name('service-request.upload-full-payment-proof');

        // Billing / Payment Features (Legacy - kept for backward compatibility)
        Route::get('/billing', [BillingController::class, 'index'])->name('billing');
        Route::post('/service-requests/{serviceRequest}/resubmit', [ServiceRequestController::class, 'resubmitProof'])
            ->name('payments.resubmit');

        // Profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::match(['patch', 'put'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
    });

