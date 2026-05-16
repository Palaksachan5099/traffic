<?php

namespace App\Http\Controllers;

use App\Models\Congestion;
use Illuminate\Http\Request;

class TrafficReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required|string|max:500',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'cause' => 'required|string|max:1000',
            'congestion_level' => 'required|in:low,medium,high,severe',
            'delay_minutes' => 'nullable|integer|min:0|max:600',
            'image' => 'nullable|file|image|max:10240',
        ]);

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('reports/congestion', 'public')
            : null;

        Congestion::create([
            'user_id' => (string) auth()->id(),
            'location' => $request->location,
            'coordinates' => ['lat' => (float) $request->lat, 'lng' => (float) $request->lng],
            'cause' => $request->cause,
            'congestion_level' => $request->congestion_level,
            'delay_minutes' => $request->delay_minutes ?? 0,
            'image_path' => $imagePath,
            'status' => 'pending',
            'reported_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Traffic congestion reported. Awaiting admin review.');
    }

    public function approve(Congestion $trafficReport)
    {
        $trafficReport->update(['status' => 'approved']);

        return back()->with('success', 'Traffic report approved.');
    }

    public function resolve(Congestion $trafficReport)
    {
        $trafficReport->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Traffic report marked resolved.');
    }
}
