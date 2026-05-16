<?php

namespace App\Http\Controllers;

use App\Models\Accident;
use Illuminate\Http\Request;

class AccidentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high',
            'image' => 'nullable|file|image|max:10240',
        ]);

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('reports/accidents', 'public')
            : null;

        Accident::create([
            'user_id' => (string) auth()->id(),
            'location' => $request->location,
            'coordinates' => ['lat' => $request->lat, 'lng' => $request->lng],
            'description' => $request->description,
            'severity' => $request->severity,
            'image_path' => $imagePath,
            'status' => 'pending',
            'reported_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Accident reported successfully. Awaiting admin approval.');
    }

    // Admin actions
    public function approve(Accident $accident)
    {
        $accident->update(['status' => 'approved']);
        return back()->with('success', 'Accident approved.');
    }

    public function resolve(Accident $accident)
    {
        $accident->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by_id' => auth()->id(),
            'resolved_by_role' => 'admin',
        ]);
        return back()->with('success', 'Accident marked as resolved.');
    }
}