<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mechanic;
use Illuminate\Http\Request;

class MechanicController extends Controller
{
    public function index(Request $request)
    {
        $query = Mechanic::query();

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name',           'like', "%{$term}%")
                  ->orWhere('email',        'like', "%{$term}%")
                  ->orWhere('specialization','like', "%{$term}%")
                  ->orWhere('phone',        'like', "%{$term}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('specialization')) {
            $query->where('specialization', 'like', "%{$request->specialization}%");
        }

        $mechanics = $query->orderBy('date_added', 'desc')
                           ->paginate(10)
                           ->appends($request->query());

        // Distinct specializations for the filter dropdown
        $specializations = Mechanic::select('specialization')
                                   ->distinct()
                                   ->orderBy('specialization')
                                   ->pluck('specialization');

        return view('admin.mechanics', compact('mechanics', 'specializations'));
    }

    public function create()
    {
        return view('admin.mechanics.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:mechanics',
            'phone'            => 'required|string|max:20',
            'specialization'   => 'required|string|max:255',
            'certificate_path' => 'nullable|file|mimes:pdf,jpg,png',
            'status'           => 'required|in:active,inactive',
        ]);

        $validated['date_added'] = now()->toDateString();

        if ($request->hasFile('certificate_path')) {
            $validated['certificate_path'] = $request->file('certificate_path')
                ->store('certificates', 'public');
        }

        Mechanic::create($validated);

        return redirect()->route('admin.mechanics')->with('success', 'Mechanic added successfully');
    }

    public function show(Mechanic $mechanic)
    {
        return view('admin.mechanics.show', compact('mechanic'));
    }

    public function edit(Mechanic $mechanic)
    {
        return view('admin.mechanics.edit', compact('mechanic'));
    }

    public function update(Request $request, Mechanic $mechanic)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:mechanics,email,' . $mechanic->id,
            'phone'          => 'required|string|max:20',
            'specialization' => 'required|string|max:255',
            'status'         => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('certificate_path')) {
            $validated['certificate_path'] = $request->file('certificate_path')
                ->store('certificates', 'public');
        }

        $mechanic->update($validated);

        return redirect()->route('admin.mechanics')->with('success', 'Mechanic updated successfully');
    }

    public function destroy(Mechanic $mechanic)
    {
        $mechanic->delete();
        return redirect()->route('admin.mechanics')->with('success', 'Mechanic deleted successfully');
    }
}
