<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Include route files
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/staff.php';
require __DIR__.'/customer.php';

// Home and public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function () {
    return view('User.about');
});

// Public service request submission (for both guests and logged-in users)
Route::post('/service-request', [
    App\Http\Controllers\Customer\ServiceRequestController::class, 'store'
])->name('service-request.store');

// Main redirect based on role
Route::get('/dashboard', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'staff') return redirect()->route('staff.dashboard');
        return redirect()->route('customer.dashboard');
    }
    return redirect()->route('login');
})->name('dashboard');
