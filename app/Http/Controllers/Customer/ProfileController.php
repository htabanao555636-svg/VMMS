<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display customer profile
     */
    public function index()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    /**
     * Update customer profile
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'required|string|max:20',
        ]);

        Auth::user()->update($validated);

        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully.');
    }
}
