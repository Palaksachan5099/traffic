<?php

namespace App\Http\Controllers;

use App\Models\Accident;
use App\Models\Congestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Unified “alerts” feed: accidents + congestion (traffic) incidents.
 */
class AlertController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $alerts = $this->collectAlerts();

        if ($request->wantsJson()) {
            return response()->json(['data' => $alerts]);
        }

        return view('alerts.index', ['alerts' => $alerts]);
    }

    public function show(Request $request, string $type, string $id): JsonResponse|View
    {
        $type = strtolower($type);

        if ($type === 'accident') {
            $model = Accident::query()->findOrFail($id);
            $payload = $this->formatAccident($model);
        } elseif ($type === 'congestion') {
            $model = Congestion::query()->findOrFail($id);
            $payload = $this->formatCongestion($model);
        } else {
            abort(404);
        }

        if ($request->wantsJson()) {
            return response()->json($payload);
        }

        return view('alerts.show', ['type' => $type, 'record' => $model, 'payload' => $payload]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function collectAlerts(): array
    {
        $out = [];

        foreach (Accident::query()->whereIn('status', ['approved', 'pending'])->latest('reported_at')->limit(100)->get() as $a) {
            $out[] = $this->formatAccident($a);
        }

        foreach (Congestion::query()->whereIn('status', ['approved', 'pending'])->latest('reported_at')->limit(100)->get() as $t) {
            $out[] = $this->formatCongestion($t);
        }

        usort($out, fn ($x, $y) => strcmp($y['reported_at'] ?? '', $x['reported_at'] ?? ''));

        return $out;
    }

    private function formatAccident(Accident $a): array
    {
        $c = $a->coordinates ?? [];

        return [
            'kind' => 'accident',
            'id' => (string) $a->getKey(),
            'title' => $a->location,
            'description' => $a->description,
            'severity' => $a->severity,
            'status' => $a->status,
            'lat' => $c['lat'] ?? null,
            'lng' => $c['lng'] ?? null,
            'reported_at' => $a->reported_at?->toIso8601String(),
        ];
    }

    private function formatCongestion(Congestion $t): array
    {
        $c = $t->coordinates ?? [];

        return [
            'kind' => 'congestion',
            'id' => (string) $t->getKey(),
            'title' => $t->location,
            'description' => $t->cause,
            'congestion_level' => $t->congestion_level,
            'status' => $t->status,
            'lat' => $c['lat'] ?? null,
            'lng' => $c['lng'] ?? null,
            'reported_at' => $t->reported_at?->toIso8601String(),
        ];
    }
}
