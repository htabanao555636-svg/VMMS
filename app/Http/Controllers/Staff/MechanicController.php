<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Mechanic;
use Illuminate\Http\Request;

class MechanicController extends Controller
{
    /**
     * Display all mechanics (read-only for staff)
     */
    public function index()
    {
        $mechanics = Mechanic::paginate(10)->appends(request()->query());
        return view('staff.mechanics', compact('mechanics'));
    }

    /**
     * Display a single mechanic (read-only for staff)
     */
    public function show(Mechanic $mechanic)
    {
        return view('staff.mechanics.show', compact('mechanic'));
    }
}
