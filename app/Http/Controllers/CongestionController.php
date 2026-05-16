<?php

namespace App\Http\Controllers;

use App\Models\Congestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Congestion reports (Mongo collection traffic_reports) — same domain as TrafficReportController.
 */
class CongestionController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $public = Congestion::query()
            ->where('status', 'approved')
            ->latest('reported_at')
            ->limit(50)
            ->get();

        $mine = Congestion::query()
            ->where('user_id', $userId)
            ->latest('reported_at')
            ->limit(20)
            ->get();

        return view('congestion.index', compact('public', 'mine'));
    }

    public function store(Request $request): RedirectResponse
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

        return redirect()->back()->with('success', 'Congestion report submitted for review.');
    }

    public function show(Congestion $trafficReport): View
    {
        return view('congestion.show', ['report' => $trafficReport]);
    }
}
