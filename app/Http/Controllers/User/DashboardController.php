<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Accident;
use App\Models\AccidentHotspot;
use App\Models\Congestion;
use App\Support\MongoAvailability;
use Illuminate\Support\Collection;
use Throwable;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (($user->role ?? 'user') === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        try {
            $userIds = array_values(array_unique([
                (string) $user->getKey(),
                $user->getKey(),
            ], SORT_REGULAR));

            $myAccidents = Accident::whereIn('user_id', $userIds)->latest()->take(10)->get();
            $myTrafficReports = Congestion::whereIn('user_id', $userIds)->latest()->take(10)->get();
            $nearbyIncidents = Accident::where('status', 'approved')
                ->whereNotNull('coordinates')
                ->latest('reported_at')
                ->limit(25)
                ->get();
            $hotspots = AccidentHotspot::query()
                ->active()
                ->latest()
                ->limit(25)
                ->get();
        } catch (Throwable $exception) {
            if (! MongoAvailability::isConnectionError($exception)) {
                throw $exception;
            }

            $myAccidents = new Collection();
            $myTrafficReports = new Collection();
            $nearbyIncidents = new Collection();
            $hotspots = new Collection();

            session()->flash('status', 'Database is temporarily unavailable. Showing an empty dashboard.');
        }

        return view('user.dashboard', compact('myAccidents', 'myTrafficReports', 'nearbyIncidents', 'hotspots'));
    }
}
