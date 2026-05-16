<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accident;
use App\Models\AccidentHotspot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccidentHotspotController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'radius_meters' => 'nullable|integer|min:50|max:50000',
            'risk_level' => 'required|in:low,medium,high,critical',
            'active' => 'sometimes|boolean',
        ]);

        $radius = (int) ($data['radius_meters'] ?? 250);

        AccidentHotspot::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'center' => ['lat' => (float) $data['lat'], 'lng' => (float) $data['lng']],
            'radius_meters' => $radius,
            'risk_level' => $data['risk_level'],
            'accident_count' => $this->countAccidentsNear((float) $data['lat'], (float) $data['lng'], $radius),
            'active' => $request->boolean('active', true),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return back()->with('success', 'Hotspot created.');
    }

    public function update(Request $request, AccidentHotspot $hotspot): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'lat' => 'sometimes|required|numeric',
            'lng' => 'sometimes|required|numeric',
            'radius_meters' => 'nullable|integer|min:50|max:50000',
            'risk_level' => 'sometimes|required|in:low,medium,high,critical',
            'active' => 'sometimes|boolean',
        ]);

        if (isset($data['lat'], $data['lng'])) {
            $data['center'] = ['lat' => (float) $data['lat'], 'lng' => (float) $data['lng']];
            unset($data['lat'], $data['lng']);
        }

        $data['updated_by'] = auth()->id();
        $hotspot->fill($data);

        $c = $hotspot->center ?? [];
        $r = (int) ($hotspot->radius_meters ?? 250);
        if (! empty($c['lat']) && ! empty($c['lng'])) {
            $hotspot->accident_count = $this->countAccidentsNear((float) $c['lat'], (float) $c['lng'], $r);
        }

        $hotspot->save();

        return back()->with('success', 'Hotspot updated.');
    }

    public function destroy(AccidentHotspot $hotspot): RedirectResponse
    {
        $hotspot->delete();

        return back()->with('success', 'Hotspot removed.');
    }

    private function countAccidentsNear(float $lat, float $lng, int $radiusMeters): int
    {
        $deg = $radiusMeters / 111_000;

        return Accident::query()
            ->where('status', 'approved')
            ->get()
            ->filter(function (Accident $a) use ($lat, $lng, $deg) {
                $c = $a->coordinates ?? [];
                if (! isset($c['lat'], $c['lng'])) {
                    return false;
                }

                return abs((float) $c['lat'] - $lat) <= $deg && abs((float) $c['lng'] - $lng) <= $deg;
            })
            ->count();
    }
}
