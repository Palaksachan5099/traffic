<?php

namespace App\Http\Controllers;

use App\Models\Accident;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MapDataController extends Controller
{
    /**
     * JSON feed for the live map: approved incidents for everyone, plus current user's pending.
     */
    public function accidents(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $approved = Accident::query()
            ->where('status', 'approved')
            ->whereNotNull('coordinates')
            ->latest('reported_at')
            ->limit(200)
            ->get();

        $myPending = Accident::query()
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->whereNotNull('coordinates')
            ->get();

        $payload = $approved->concat($myPending)
            ->unique(fn (Accident $a) => (string) $a->getKey())
            ->values()
            ->map(function (Accident $a) use ($userId) {
            $coords = $a->coordinates ?? [];

            return [
                'id' => (string) $a->getKey(),
                'lat' => $coords['lat'] ?? null,
                'lng' => $coords['lng'] ?? null,
                'description' => $a->description,
                'severity' => $a->severity,
                'status' => $a->status,
                'location' => $a->location,
                'mine' => (string) ($a->user_id ?? '') === (string) ($userId ?? ''),
            ];
        })->filter(fn (array $row) => $row['lat'] !== null && $row['lng'] !== null)->values();

        return response()->json(['data' => $payload, 'updated_at' => now()->toIso8601String()]);
    }
}
