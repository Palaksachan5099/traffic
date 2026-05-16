<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accident;
use App\Models\AccidentHotspot;
use App\Models\Congestion;
use App\Models\User;
use App\Support\MongoAvailability;
use Illuminate\Support\Collection;
use Throwable;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $totalAccidents = Accident::count();
            $pendingAccidents = Accident::where('status', 'pending')->count();
            $totalCongestion = Congestion::count();
            $pendingCongestion = Congestion::where('status', 'pending')->count();
            $totalUsers = User::where('role', 'user')->count();
            $activeHotspots = AccidentHotspot::active()->count();

            $inProgressAccidents = Accident::where('status', 'in_progress')->latest('reported_at')->limit(50)->get();
            $inProgressCongestion = Congestion::where('status', 'in_progress')->latest('reported_at')->limit(50)->get();
            $completedAccidents = Accident::where('status', 'resolved')->latest('resolved_at')->limit(20)->get();
            $completedCongestion = Congestion::where('status', 'resolved')->latest('resolved_at')->limit(20)->get();

            $accidentsPerDay = Accident::whereBetween('reported_at', [now()->subDays(7), now()])
                ->get()
                ->filter(fn ($item) => $item->reported_at !== null)
                ->groupBy(fn ($item) => $item->reported_at->format('Y-m-d'))
                ->map->count();

            $congestionPerDay = Congestion::whereBetween('reported_at', [now()->subDays(7), now()])
                ->get()
                ->filter(fn ($item) => $item->reported_at !== null)
                ->groupBy(fn ($item) => $item->reported_at->format('Y-m-d'))
                ->map->count();

            $accidents = Accident::with('assignedOfficer')->latest('reported_at')->limit(100)->get();
            $congestionReports = Congestion::with('user')->latest('reported_at')->limit(100)->get();
            $officers = User::query()
                ->orderBy('name')
                ->get()
                ->filter(fn (User $user): bool => strtolower(trim((string) ($user->role ?? ''))) === 'officer')
                ->values();
            $hotspots = AccidentHotspot::query()->orderByDesc('active')->latest()->limit(40)->get();
        } catch (Throwable $exception) {
            if (! MongoAvailability::isConnectionError($exception)) {
                throw $exception;
            }

            $totalAccidents = 0;
            $pendingAccidents = new Collection();
            $totalCongestion = 0;
            $pendingCongestion = new Collection();
            $totalUsers = 0;
            $activeHotspots = 0;
            $accidentsPerDay = new Collection();
            $congestionPerDay = new Collection();
            $accidents = new Collection();
            $congestionReports = new Collection();
            $officers = new Collection();
            $hotspots = new Collection();
            $inProgressAccidents = new Collection();
            $inProgressCongestion = new Collection();
            $completedAccidents = new Collection();
            $completedCongestion = new Collection();

            session()->flash('status', 'Database is temporarily unavailable. Showing empty admin dashboard data.');
        }

        return view('admin.dashboard', compact(
            'totalAccidents',
            'pendingAccidents',
            'totalCongestion',
            'pendingCongestion',
            'totalUsers',
            'activeHotspots',
            'accidentsPerDay',
            'congestionPerDay',
            'accidents',
            'congestionReports',
            'officers',
            'hotspots',
            'inProgressAccidents',
            'inProgressCongestion',
            'completedAccidents',
            'completedCongestion'
        ));
    }
}
