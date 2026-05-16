@extends('layouts.app')

@section('title', 'Live map & reports')

@section('content')
<div class="space-y-10">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-white">Incident center</h1>
        <p class="mt-2 max-w-2xl text-slate-400">Report accidents and congestion. The map refreshes automatically with approved incidents and your pending reports.</p>
    </div>

    <div class="grid gap-8 lg:grid-cols-2">
        <div class="space-y-6">
            <section class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6 shadow-xl shadow-black/20 backdrop-blur">
                <h2 class="text-lg font-semibold text-white">Report accident</h2>
                <p class="mt-1 text-sm text-slate-400">Click the map to set coordinates, or use your current location.</p>
                <form method="POST" action="{{ route('accident.store') }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                    @csrf
                    @if($errors->any())
                        <div class="rounded-xl border border-rose-500/30 bg-rose-950/30 px-3 py-2 text-xs text-rose-200">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-500">Location label</label>
                        <input type="text" name="location" value="{{ old('location') }}" required
                            class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-600 ring-amber-500/40 transition focus:border-amber-500/50 focus:outline-none focus:ring-2"
                            placeholder="e.g. Ring Road, near Metro">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-500">Latitude</label>
                            <input type="text" id="accident-lat" name="lat" value="{{ old('lat') }}" required readonly
                                class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950/80 px-3 py-2 text-sm text-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-500">Longitude</label>
                            <input type="text" id="accident-lng" name="lng" value="{{ old('lng') }}" required readonly
                                class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950/80 px-3 py-2 text-sm text-slate-300">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-500">Description</label>
                        <textarea name="description" rows="3" required
                            class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-600 focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40"
                            placeholder="What happened?">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-500">Severity</label>
                        <select name="severity" class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-2.5 text-white focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                            <option value="low" @selected(old('severity') === 'low')>Low</option>
                            <option value="medium" @selected(old('severity', 'medium') === 'medium')>Medium</option>
                            <option value="high" @selected(old('severity') === 'high')>High</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-500">Accident image (optional)</label>
                        <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                            class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-300 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-800 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-slate-200 hover:file:bg-slate-700">
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 px-4 py-3 font-semibold text-slate-950 shadow-lg shadow-orange-500/25 transition hover:from-amber-400 hover:to-orange-500">
                        Submit accident report
                    </button>
                </form>
            </section>

            <section class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6 shadow-xl shadow-black/20 backdrop-blur">
                <h2 class="text-lg font-semibold text-white">Report traffic congestion</h2>
                <p class="mt-1 text-sm text-slate-400">Uses the same map pin as above.</p>
                <form method="POST" action="{{ route('traffic.store') }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-500">Location</label>
                        <input type="text" name="location" required
                            class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-600 focus:border-sky-500/50 focus:outline-none focus:ring-2 focus:ring-sky-500/40"
                            placeholder="Road or junction">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-500">Latitude</label>
                            <input type="text" id="traffic-lat" name="lat" required readonly
                                class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950/80 px-3 py-2 text-sm text-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-500">Longitude</label>
                            <input type="text" id="traffic-lng" name="lng" required readonly
                                class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950/80 px-3 py-2 text-sm text-slate-300">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-500">Cause / notes</label>
                        <textarea name="cause" rows="2" required class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-2.5 text-white focus:border-sky-500/50 focus:outline-none focus:ring-2 focus:ring-sky-500/40"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-500">Congestion</label>
                            <select name="congestion_level" class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-2.5 text-white focus:border-sky-500/50 focus:outline-none focus:ring-2 focus:ring-sky-500/40">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="severe">Severe</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-500">Delay (min)</label>
                            <input type="number" name="delay_minutes" min="0" max="600" value="0"
                                class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-2.5 text-white focus:border-sky-500/50 focus:outline-none focus:ring-2 focus:ring-sky-500/40">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-500">Congestion image (optional)</label>
                        <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                            class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-300 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-800 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-slate-200 hover:file:bg-slate-700">
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>
                    <button type="submit" class="w-full rounded-xl border border-sky-500/40 bg-sky-600/20 px-4 py-3 font-semibold text-sky-100 transition hover:bg-sky-600/35">
                        Submit traffic report
                    </button>
                </form>
            </section>
        </div>

        <div class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-lg font-semibold text-white">Live map</h2>
                <div class="flex flex-wrap gap-2">
                    <button type="button" id="btn-locate" class="rounded-lg border border-slate-600 bg-slate-800 px-3 py-1.5 text-xs font-medium text-slate-200 hover:bg-slate-700">
                        My location
                    </button>
                    <span id="map-updated" class="rounded-lg bg-slate-800 px-3 py-1.5 text-xs text-slate-400"></span>
                </div>
            </div>
            <div id="map" class="h-[min(520px,70vh)] w-full overflow-hidden rounded-2xl border border-slate-800 shadow-2xl shadow-black/40 ring-1 ring-white/5"></div>
            <p class="text-xs text-slate-500">
                <span class="inline-block h-2 w-2 rounded-full bg-amber-400 align-middle"></span> Approved accident
                <span class="ml-3 inline-block h-2 w-2 rounded-full bg-violet-400 align-middle"></span> Your pending
                <span class="ml-3 inline-block h-2 w-2 rounded-full bg-rose-400 align-middle"></span> Admin hotspot zone
            </p>
            <section class="rounded-2xl border border-slate-800 bg-slate-900/40 p-4">
                <h3 class="font-semibold text-white">Admin hotspot zones</h3>
                <ul class="mt-3 space-y-2 text-sm">
                    @forelse($hotspots as $zone)
                        <li class="rounded-xl border border-slate-800 bg-slate-950/50 px-3 py-2">
                            <p class="font-medium text-slate-200">{{ $zone->title }}</p>
                            <p class="text-xs text-slate-500 capitalize">
                                Risk: {{ $zone->risk_level }} · Radius: {{ $zone->radius_meters ?? 250 }} m
                            </p>
                        </li>
                    @empty
                        <li class="text-slate-500">No active hotspot zones yet.</li>
                    @endforelse
                </ul>
            </section>
        </div>
    </div>

    <div class="grid gap-8 md:grid-cols-2">
        <section class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6">
            <h3 class="font-semibold text-white">Your recent accidents</h3>
            <ul class="mt-4 space-y-3 text-sm">
                @forelse($myAccidents as $a)
                    <li class="flex flex-col gap-2 rounded-xl border border-slate-800 bg-slate-950/50 px-4 py-3">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-slate-200">{{ $a->location }}</span>
                            <span class="rounded-full text-xs font-medium px-2 py-1 {{ 
                                ($a->status === 'resolved' ? 'bg-green-500/20 text-green-200' :
                                ($a->status === 'in_progress' ? 'bg-blue-500/20 text-blue-200' :
                                ($a->status === 'approved' ? 'bg-amber-500/20 text-amber-200' :
                                ($a->status === 'pending' ? 'bg-yellow-500/20 text-yellow-200' :
                                'bg-red-500/20 text-red-200'))))
                            }}">{{ ucfirst(str_replace('_', ' ', $a->status)) }}</span>
                        </div>
                        <span class="text-slate-500">{{ $a->reported_at?->diffForHumans() }} · <span class="capitalize">{{ $a->severity }}</span></span>
                        @if($a->status === 'in_progress' || $a->status === 'resolved')
                            <div class="mt-1">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs text-slate-400">Progress:</span>
                                    <span class="text-xs text-slate-300 font-medium">{{ $a->completion ?? 0 }}%</span>
                                </div>
                                <div class="h-1.5 bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-amber-500 to-orange-600 rounded-full transition-all" style="width: {{ $a->completion ?? 0 }}%"></div>
                                </div>
                                @if($a->admin_notes)
                                    <p class="text-xs text-slate-400 mt-2">Admin notes: <span class="text-slate-300">{{ $a->admin_notes }}</span></p>
                                @endif
                            </div>
                        @endif
                    </li>
                @empty
                    <li class="text-slate-500">No accident reports yet.</li>
                @endforelse
            </ul>
        </section>
        <section class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6">
            <h3 class="font-semibold text-white">Your traffic reports</h3>
            <ul class="mt-4 space-y-3 text-sm">
                @forelse($myTrafficReports as $t)
                    <li class="flex flex-col gap-2 rounded-xl border border-slate-800 bg-slate-950/50 px-4 py-3">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-slate-200">{{ $t->location }}</span>
                            <span class="rounded-full text-xs font-medium px-2 py-1 {{ 
                                ($t->status === 'resolved' ? 'bg-green-500/20 text-green-200' :
                                ($t->status === 'in_progress' ? 'bg-blue-500/20 text-blue-200' :
                                ($t->status === 'approved' ? 'bg-sky-500/20 text-sky-200' :
                                ($t->status === 'pending' ? 'bg-yellow-500/20 text-yellow-200' :
                                'bg-red-500/20 text-red-200'))))
                            }}">{{ ucfirst(str_replace('_', ' ', $t->status)) }}</span>
                        </div>
                        <span class="text-slate-500">{{ $t->reported_at?->diffForHumans() }} · <span class="capitalize">{{ $t->congestion_level }}</span></span>
                        @if($t->status === 'in_progress' || $t->status === 'resolved')
                            <div class="mt-1">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs text-slate-400">Progress:</span>
                                    <span class="text-xs text-slate-300 font-medium">{{ $t->completion ?? 0 }}%</span>
                                </div>
                                <div class="h-1.5 bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-sky-500 to-cyan-600 rounded-full transition-all" style="width: {{ $t->completion ?? 0 }}%"></div>
                                </div>
                                @if($t->admin_notes)
                                    <p class="text-xs text-slate-400 mt-2">Admin notes: <span class="text-slate-300">{{ $t->admin_notes }}</span></p>
                                @endif
                            </div>
                        @endif
                    </li>
                @empty
                    <li class="text-slate-500">No traffic reports yet.</li>
                @endforelse
            </ul>
        </section>
    </div>
</div>
@endsection

@push('scripts')
@php
    $hotspotsForMap = ($hotspots ?? collect())->map(function ($h) {
        return [
            'title' => $h->title,
            'description' => $h->description,
            'risk_level' => $h->risk_level,
            'radius_meters' => (int) ($h->radius_meters ?? 250),
            'lat' => (float) data_get($h->center, 'lat', 0),
            'lng' => (float) data_get($h->center, 'lng', 0),
        ];
    })->values()->all();
@endphp
<script>
(function () {
    const defaultCenter = [28.6139, 77.2090];
    const map = L.map('map', { scrollWheelZoom: true }).setView(defaultCenter, 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let pin = null;
    const markersLayer = L.layerGroup().addTo(map);
    const hotspotsLayer = L.layerGroup().addTo(map);
    const hotspots = @json($hotspotsForMap);

    function setPin(lat, lng) {
        if (pin) map.removeLayer(pin);
        pin = L.marker([lat, lng]).addTo(map);
        document.getElementById('accident-lat').value = lat.toFixed(6);
        document.getElementById('accident-lng').value = lng.toFixed(6);
        document.getElementById('traffic-lat').value = lat.toFixed(6);
        document.getElementById('traffic-lng').value = lng.toFixed(6);
    }

    map.on('click', function (e) {
        setPin(e.latlng.lat, e.latlng.lng);
    });

    document.getElementById('btn-locate').addEventListener('click', function () {
        if (!navigator.geolocation) return;
        navigator.geolocation.getCurrentPosition(function (pos) {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            map.setView([lat, lng], 14);
            setPin(lat, lng);
        });
    });

    function severityColor(sev, mine, status) {
        if (mine && status === 'pending') return '#a78bfa';
        const m = { low: '#22c55e', medium: '#eab308', high: '#f97316', severe: '#ef4444' };
        return m[sev] || '#fbbf24';
    }

    async function loadMarkers() {
        const res = await fetch("{{ route('map.accidents') }}", {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!res.ok) return;
        const json = await res.json();
        markersLayer.clearLayers();
        (json.data || []).forEach(function (row) {
            const c = severityColor(row.severity, row.mine, row.status);
            const circle = L.circleMarker([row.lat, row.lng], {
                radius: 8,
                color: '#0f172a',
                weight: 2,
                fillColor: c,
                fillOpacity: 0.9
            });
            circle.bindPopup(
                '<strong>' + (row.location || 'Incident') + '</strong><br>' +
                (row.description || '') + '<br><span style="opacity:.8">' + row.status + ' · ' + row.severity + '</span>'
            );
            markersLayer.addLayer(circle);
        });
        const el = document.getElementById('map-updated');
        if (el && json.updated_at) el.textContent = 'Updated ' + new Date(json.updated_at).toLocaleTimeString();
    }

    function hotspotColor(risk) {
        const m = { low: '#22c55e', medium: '#eab308', high: '#f97316', critical: '#ef4444' };
        return m[risk] || '#fb7185';
    }

    function drawHotspots() {
        hotspotsLayer.clearLayers();
        (hotspots || []).forEach(function (zone) {
            if (!zone.lat || !zone.lng) return;
            const color = hotspotColor(zone.risk_level);
            const circle = L.circle([zone.lat, zone.lng], {
                radius: zone.radius_meters || 250,
                color: color,
                weight: 2,
                fillColor: color,
                fillOpacity: 0.15,
            });
            circle.bindPopup(
                '<strong>' + (zone.title || 'Hotspot zone') + '</strong><br>' +
                ((zone.description || '') ? (zone.description + '<br>') : '') +
                '<span style="opacity:.8">Risk: ' + (zone.risk_level || 'medium') + '</span>'
            );
            hotspotsLayer.addLayer(circle);
        });
    }

    drawHotspots();
    loadMarkers();
    setInterval(loadMarkers, 45000);

    @if($nearbyIncidents->isEmpty())
        setPin(defaultCenter[0], defaultCenter[1]);
    @else
        @php $first = $nearbyIncidents->first(); $c = $first->coordinates ?? []; @endphp
        @if(!empty($c['lat']) && !empty($c['lng']))
            map.setView([{{ $c['lat'] }}, {{ $c['lng'] }}], 12);
        @endif
    @endif
})();
</script>
@endpush
