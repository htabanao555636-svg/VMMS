<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('message', 'Please log in to access this area.');
        }

        $user = Auth::user();

        if ($user->role !== 'admin') {
            return redirect('/')->with('error', 'You do not have permission to access the admin portal.');
        }

        return $next($request);
    }
}