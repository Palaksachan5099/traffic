<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accident;
use App\Models\Congestion;
use Illuminate\Http\Request;

class AlertsController extends Controller
{
    private function dashboardData(): array
    {
        $pendingAccidents = Accident::where('status', 'pending')->latest('reported_at')->with('user')->get();
        $pendingCongestion = Congestion::where('status', 'pending')->latest('reported_at')->with('user')->get();
        $inProgressAccidents = Accident::where('status', 'in_progress')->latest('reported_at')->with(['user', 'assignedOfficer'])->get();
        $inProgressCongestion = Congestion::where('status', 'in_progress')->latest('reported_at')->with('user')->get();
        $completedAccidents = Accident::where('status', 'resolved')->latest('resolved_at')->with(['user', 'assignedOfficer'])->limit(20)->get();
        $completedCongestion = Congestion::where('status', 'resolved')->latest('resolved_at')->with('user')->limit(20)->get();

        return compact(
            'pendingAccidents',
            'pendingCongestion',
            'inProgressAccidents',
            'inProgressCongestion',
            'completedAccidents',
            'completedCongestion'
        );
    }

    public function index()
    {
        return view('admin.alerts.index', $this->dashboardData());
    }

    public function index1()
    {
        return view('admin.alerts.index1', $this->dashboardData());
    }

    public function index2()
    {
        return view('admin.alerts.index2', $this->dashboardData());
    }

    public function statistics()
    {
        $stats = [
            'pendingAccidents' => Accident::where('status', 'pending')->count(),
            'pendingCongestion' => Congestion::where('status', 'pending')->count(),
            'inProgressAccidents' => Accident::where('status', 'in_progress')->count(),
            'inProgressCongestion' => Congestion::where('status', 'in_progress')->count(),
            'resolvedAccidents' => Accident::where('status', 'resolved')->count(),
            'resolvedCongestion' => Congestion::where('status', 'resolved')->count(),
        ];

        return view('admin.alerts.statistics', $stats);
    }

    public function create()
    {
        return view('admin.alerts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:accident,congestion,road_closure,maintenance,general',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'severity' => 'required|in:low,medium,high,critical',
            'duration' => 'nullable|integer|min:0',
            'radius' => 'nullable|numeric|min:0',
            'send_notification' => 'nullable|boolean',
        ]);

        // Store alert in database or send notification
        // This would require a separate Alert model
        // For now, we'll just redirect back with success message
        
        return redirect()->route('admin.alerts.index')
            ->with('success', 'Alert created successfully!');
    }

    public function updateStatus(Request $request, string $type, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,in_progress,resolved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
            'completion' => 'nullable|integer|min:0|max:100',
        ]);

        $model = $type === 'accident' ? Accident::class : Congestion::class;
        abort_unless(in_array($type, ['accident', 'congestion'], true), 404);

        $report = $model::findOrFail($id);
        $update = [
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'completion' => $validated['completion'] ?? null,
        ];

        if ($validated['status'] === 'resolved') {
            $update['resolved_at'] = now();
            $update['completion'] = 100;
        }

        $report->update($update);

        return back()->with('success', 'Report status updated successfully.');
    }
}
