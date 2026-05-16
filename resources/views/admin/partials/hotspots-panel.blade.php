@php
    $riskStyles = [
        'low' => 'border-emerald-500/30 bg-emerald-950/30 text-emerald-200',
        'medium' => 'border-amber-500/30 bg-amber-950/30 text-amber-200',
        'high' => 'border-orange-500/30 bg-orange-950/30 text-orange-200',
        'critical' => 'border-rose-500/30 bg-rose-950/30 text-rose-200',
    ];
@endphp

<section class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-white">Accident hotspots</h2>
            <p class="mt-1 text-sm text-slate-400">Curated zones (shown on planning views). Counts ≈ approved accidents inside radius (planar estimate).</p>
        </div>
    </div>

    <div class="mt-6 grid gap-8 lg:grid-cols-2">
        <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-5">
            <h3 class="text-sm font-semibold text-white">Add hotspot</h3>
            <form action="{{ route('admin.hotspots.store') }}" method="POST" class="mt-4 space-y-3">
                @csrf
                <div>
                    <label class="text-xs uppercase text-slate-500">Title</label>
                    <input name="title" required class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white" placeholder="e.g. NH-1 / Ring Rd junction">
                </div>
                <div>
                    <label class="text-xs uppercase text-slate-500">Description</label>
                    <textarea name="description" rows="2" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white" placeholder="Why this zone matters"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs uppercase text-slate-500">Latitude</label>
                        <input name="lat" type="text" required class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 font-mono text-xs text-white">
                    </div>
                    <div>
                        <label class="text-xs uppercase text-slate-500">Longitude</label>
                        <input name="lng" type="text" required class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 font-mono text-xs text-white">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs uppercase text-slate-500">Radius (m)</label>
                        <input name="radius_meters" type="number" value="250" min="50" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white">
                    </div>
                    <div>
                        <label class="text-xs uppercase text-slate-500">Risk</label>
                        <select name="risk_level" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-400">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" name="active" value="1" checked class="rounded border-slate-600 bg-slate-900 text-amber-500 focus:ring-amber-500/50">
                    Active
                </label>
                <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-rose-600 to-orange-600 py-2.5 text-sm font-semibold text-white shadow-lg shadow-orange-900/20 hover:from-rose-500 hover:to-orange-500">Save hotspot</button>
            </form>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-800">
            <table class="min-w-full divide-y divide-slate-800 text-left text-sm">
                <thead class="bg-slate-900/90 text-xs uppercase tracking-wide text-slate-400">
                    <tr>
                        <th class="px-4 py-3">Zone</th>
                        <th class="px-4 py-3">Risk</th>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 bg-slate-950/40">
                    @forelse($hotspots as $hotspot)
                        @php $cen = $hotspot->center ?? []; $rs = $riskStyles[$hotspot->risk_level] ?? 'border-slate-700 bg-slate-900 text-slate-300'; @endphp
                        <tr class="hover:bg-slate-900/60">
                            <td class="px-4 py-3">
                                <p class="font-medium text-white">{{ $hotspot->title }}</p>
                                <p class="mt-0.5 font-mono text-[10px] text-slate-500">{{ $cen['lat'] ?? '—' }}, {{ $cen['lng'] ?? '—' }} · {{ $hotspot->radius_meters }}m</p>
                                <form action="{{ route('admin.hotspots.update', $hotspot) }}" method="POST" class="mt-2 flex flex-wrap items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="risk_level" class="rounded border border-slate-700 bg-slate-950 px-2 py-1 text-xs text-white">
                                        @foreach(['low','medium','high','critical'] as $lvl)
                                            <option value="{{ $lvl }}" @selected($hotspot->risk_level === $lvl)>{{ $lvl }}</option>
                                        @endforeach
                                    </select>
                                    <label class="flex items-center gap-1 text-xs text-slate-500">
                                        <input type="hidden" name="active" value="0">
                                        <input type="checkbox" name="active" value="1" @checked($hotspot->active) class="rounded border-slate-600 bg-slate-900">
                                        on
                                    </label>
                                    <button type="submit" class="rounded bg-slate-700 px-2 py-1 text-xs text-white hover:bg-slate-600">Update</button>
                                </form>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-lg border px-2 py-1 text-xs capitalize {{ $rs }}">{{ $hotspot->risk_level }}</span>
                            </td>
                            <td class="px-4 py-3 tabular-nums text-slate-300">{{ $hotspot->accident_count ?? 0 }}</td>
                            <td class="px-4 py-3 text-right">
                                <form action="{{ route('admin.hotspots.destroy', $hotspot) }}" method="POST" class="inline" onsubmit="return confirm('Delete this hotspot?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg border border-rose-500/40 px-2 py-1 text-xs text-rose-300 hover:bg-rose-950/50">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-slate-500">No hotspots yet — add one on the left.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
