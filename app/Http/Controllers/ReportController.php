<?php

namespace App\Http\Controllers;

use App\Models\Accident;
use App\Models\Congestion;
use Illuminate\View\View;

/**
 * Combined reporting history for the signed-in user.
 */
class ReportController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();
        $userIds = array_values(array_unique([
            (string) $userId,
            $userId,
        ], SORT_REGULAR));

        $accidents = Accident::query()
            ->whereIn('user_id', $userIds)
            ->latest('reported_at')
            ->get();

        $congestion = Congestion::query()
            ->whereIn('user_id', $userIds)
            ->latest('reported_at')
            ->get();

        return view('reports.index', compact('accidents', 'congestion'));
    }
}
