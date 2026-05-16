<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accident;
use App\Models\Congestion;
use App\Models\User;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function generate()
    {
        $totalAccidents = Accident::count();
        $monthAccidents = Accident::whereBetween('reported_at', [now()->startOfMonth(), now()])->count();
        $totalCongestion = Congestion::count();
        $monthCongestion = Congestion::whereBetween('reported_at', [now()->startOfMonth(), now()])->count();
        $activeUsers = User::where('role', 'user')->count();
        $activeReporters = User::whereHas('accidents')->orWhereHas('congestionReports')->where('role', 'user')->count();

        return view('admin.reports.generate', compact(
            'totalAccidents',
            'monthAccidents',
            'totalCongestion',
            'monthCongestion',
            'activeUsers',
            'activeReporters'
        ));
    }

    public function statistics()
    {
        // 7-day statistics
        $accidentCount7d = Accident::whereBetween('reported_at', [now()->subDays(7), now()])->count();
        $congestionCount7d = Congestion::whereBetween('reported_at', [now()->subDays(7), now()])->count();
        $resolvedCount7d = Accident::where('status', 'resolved')
            ->whereBetween('resolved_at', [now()->subDays(7), now()])
            ->count() + 
            Congestion::where('status', 'resolved')
            ->whereBetween('resolved_at', [now()->subDays(7), now()])
            ->count();
        $activeUsers7d = User::whereHas('accidents', function($q) {
            $q->whereBetween('reported_at', [now()->subDays(7), now()]);
        })->orWhereHas('congestionReports', function($q) {
            $q->whereBetween('reported_at', [now()->subDays(7), now()]);
        })->count();

        $totalReports7d = $accidentCount7d + $congestionCount7d;
        $resolutionRate = $totalReports7d > 0 ? round(($resolvedCount7d / $totalReports7d) * 100, 1) : 0;

        // Severity breakdown
        $accidentLow = Accident::where('severity', 'low')->count();
        $accidentMed = Accident::where('severity', 'medium')->count();
        $accidentHigh = Accident::where('severity', 'high')->count();

        $congestionLow = Congestion::where('congestion_level', 'low')->count();
        $congestionMed = Congestion::where('congestion_level', 'medium')->count();
        $congestionHigh = Congestion::where('congestion_level', 'high')->count();
        $congestionSevere = Congestion::where('congestion_level', 'severe')->count();

        // Status breakdown
        $statusPending = Accident::where('status', 'pending')->count() + Congestion::where('status', 'pending')->count();
        $statusApproved = Accident::where('status', 'approved')->count() + Congestion::where('status', 'approved')->count();
        $statusResolved = Accident::where('status', 'resolved')->count() + Congestion::where('status', 'resolved')->count();

        // Trend data
        $trendLabels = [];
        $trendAccidents = [];
        $trendCongestion = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $trendLabels[] = $date->format('M d');
            $trendAccidents[] = Accident::whereDate('reported_at', $date)->count();
            $trendCongestion[] = Congestion::whereDate('reported_at', $date)->count();
        }

        // Previous week trend
        $accidentCount7dPrev = Accident::whereBetween('reported_at', [now()->subDays(14), now()->subDays(7)])->count();
        $congestionCount7dPrev = Congestion::whereBetween('reported_at', [now()->subDays(14), now()->subDays(7)])->count();

        $accidentTrend = $accidentCount7dPrev > 0 ? round((($accidentCount7d - $accidentCount7dPrev) / $accidentCount7dPrev) * 100, 1) : 0;
        $congestionTrend = $congestionCount7dPrev > 0 ? round((($congestionCount7d - $congestionCount7dPrev) / $congestionCount7dPrev) * 100, 1) : 0;

        $newUsers = User::where('role', 'user')->whereBetween('created_at', [now()->subDays(7), now()])->count();
return view('admin.reports.statistics', [
    'accidentCount7d' => $accidentCount7d,
    'congestionCount7d' => $congestionCount7d,
    'resolvedCount7d' => $resolvedCount7d,
    'activeUsers7d' => $activeUsers7d,
    'resolutionRate' => $resolutionRate,
    'accidentTrend' => $accidentTrend,
    'congestionTrend' => $congestionTrend,
    'accidentLow' => $accidentLow,
    'accidentMed' => $accidentMed,
    'accidentHigh' => $accidentHigh,
    'congestionLow' => $congestionLow,
    'congestionMed' => $congestionMed,
    'congestionHigh' => $congestionHigh,
    'congestionSevere' => $congestionSevere,
    'statusPending' => $statusPending,
    'statusApproved' => $statusApproved,
    'statusResolved' => $statusResolved,
    'trendLabels' => $trendLabels,
    'trendAccidents' => $trendAccidents,
    'trendCongestion' => $trendCongestion,
    'newUsers' => $newUsers,
    'topHotspots' => []
]);
        
    }

    public function edit($id)
    {
        // Try to find as accident first
        $report = Accident::find($id);
        if (!$report) {
            $report = Congestion::find($id);
        }

        if (!$report) {
            return redirect()->route('admin.reports.generate')->with('error', 'Report not found');
        }

        return view('admin.reports.edit', compact('report'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,approved,in_progress,resolved,rejected',
            'severity' => 'required|string',
            'admin_notes' => 'nullable|string',
            'completion' => 'nullable|integer|min:0|max:100',
        ]);

        // Try to find as accident first
        $report = Accident::find($id);
        if (!$report) {
            $report = Congestion::find($id);
        }

        if (!$report) {
            return redirect()->route('admin.reports.generate')->with('error', 'Report not found');
        }

        $report->update($validated);

        if ($validated['status'] === 'resolved') {
            $report->update(['resolved_at' => now()]);
        }

        return redirect()->route('admin.reports.generate')
            ->with('success', 'Report updated successfully!');
    }

    public function downloadAccidents(Request $request)
    {
        // Implementation for PDF/CSV export
        return redirect()->back()->with('info', 'Feature coming soon');
    }

    public function downloadCongestion(Request $request)
    {
        // Implementation for PDF/CSV export
        return redirect()->back()->with('info', 'Feature coming soon');
    }

    public function downloadUserActivity(Request $request)
    {
        // Implementation for user activity report
        return redirect()->back()->with('info', 'Feature coming soon');
    }

    public function exportExcel()
    {
        // Implementation for Excel export
        return redirect()->back()->with('info', 'Feature coming soon');
    }

    public function exportCsv()
    {
        // Implementation for CSV export
        return redirect()->back()->with('info', 'Feature coming soon');
    }
}
