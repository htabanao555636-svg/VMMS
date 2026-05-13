<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Fix: when logged-in user visits /login, redirect to their dashboard
        $middleware->redirectUsersTo(function () {
            if (auth()->check()) {
                return match (auth()->user()->role) {
                    'admin' => route('admin.dashboard'),
                    'staff' => route('staff.dashboard'),
                    'user'  => route('customer.dashboard'),
                    default => '/',
                };
            }
            return '/';
        });

        $middleware->alias([
            'admin'          => \App\Http\Middleware\EnsureAdmin::class,
            'staff'          => \App\Http\Middleware\EnsureStaff::class,
            'customer'       => \App\Http\Middleware\EnsureCustomer::class,
            'admin.or.staff' => \App\Http\Middleware\EnsureAdminOrStaff::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();