<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Staff\MechanicController;
use App\Http\Controllers\Admin\ServiceRequestController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WheelerCategoryController as CategoryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('staff')
    ->middleware(['auth', 'staff'])
    ->name('staff.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Service Requests
        Route::get('/service-requests', [ServiceRequestController::class, 'index'])->name('service-request');
        Route::get('/service-requests/create', [ServiceRequestController::class, 'create'])->name('service-request.create');
        Route::post('/service-requests', [ServiceRequestController::class, 'store'])->name('service-request.store');
        Route::get('/service-requests/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('service-request.show');
        Route::get('/service-requests/{serviceRequest}/edit', [ServiceRequestController::class, 'edit'])->name('service-request.edit');
        Route::match(['patch', 'put'], '/service-requests/{serviceRequest}', [ServiceRequestController::class, 'update'])->name('service-request.update');
        Route::match(['patch', 'put'], '/service-requests/{serviceRequest}/status', [ServiceRequestController::class, 'updateStatus'])->name('service-request.update-status');
        Route::post('/service-requests/{serviceRequest}/mechanic', [ServiceRequestController::class, 'assignMechanic'])->name('service-request.assign-mechanic');
        Route::delete('/service-requests/{serviceRequest}', [ServiceRequestController::class, 'destroy'])->name('service-request.destroy');

        // Mechanics (read-only)
        Route::get('/mechanics', [MechanicController::class, 'index'])->name('mechanics');
        Route::get('/mechanics/{mechanic}', [MechanicController::class, 'show'])->name('mechanics.show');

        // Categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
        Route::post('/categories', [CategoryController::class, 'storeWheelerCategory'])->name('wheeler-categories.store');
        Route::match(['patch', 'put'], '/categories/{category}', [CategoryController::class, 'updateWheelerCategory'])->name('wheeler-categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroyWheelerCategory'])->name('wheeler-categories.destroy');

        // Services
        Route::get('/services', [ServiceController::class, 'index'])->name('services');
        Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
        Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
        Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
        Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
        Route::match(['patch', 'put'], '/services/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

        // Billing
        Route::get('/billing', [BillingController::class, 'index'])->name('billing');
        Route::post('/billing/{payment}/verify', [BillingController::class, 'verifyPayment'])->name('billing.verify');
        Route::post('/billing/{payment}/reject', [BillingController::class, 'rejectPayment'])->name('billing.reject');
        Route::post('/billing/{serviceRequest}/mark-completed', [BillingController::class, 'markCompleted'])->name('billing.mark-completed');

        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::match(['patch', 'put'], '/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Report
        Route::get('/report', [ReportController::class, 'index'])->name('report');

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });