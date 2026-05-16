@extends('layouts.app')

@section('title', 'Admin')

@section('content')
<div class="space-y-10">
    <header class="flex flex-wrap items-end justify-between gap-4 border-b border-slate-800/80 pb-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-amber-500/90">Operations</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">Admin control center</h1>
            <p class="mt-2 max-w-xl text-slate-400">Accidents, congestion reports, officer assignments, and curated accident hotspots.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="rounded-xl border border-slate-600 px-4 py-2 text-sm font-medium text-slate-300 transition hover:border-slate-500 hover:bg-slate-900 hover:text-white">Public map</a>
    </header>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-6">
        <div class="rounded-2xl border border-slate-800 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-lg">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Accidents</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-white">{{ $totalAccidents }}</p>
        </div>
        <div class="rounded-2xl border border-amber-500/25 bg-gradient-to-br from-amber-950/50 to-slate-950 p-5 shadow-lg shadow-amber-900/10">
            <p class="text-xs font-medium uppercase tracking-wide text-amber-200/80">Pending accidents</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-amber-100">{{ $pendingAccidents }}</p>
        </div>
        <div class="rounded-2xl border border-slate-800 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-lg">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Congestion reports</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-white">{{ $totalCongestion }}</p>
        </div>
        <div class="rounded-2xl border border-sky-500/25 bg-gradient-to-br from-sky-950/50 to-slate-950 p-5 shadow-lg">
            <p class="text-xs font-medium uppercase tracking-wide text-sky-200/80">Pending congestion</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-sky-100">{{ $pendingCongestion }}</p>
        </div>
        <div class="rounded-2xl border border-rose-500/20 bg-gradient-to-br from-rose-950/40 to-slate-950 p-5 shadow-lg">
            <p class="text-xs font-medium uppercase tracking-wide text-rose-200/80">Active hotspots</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-rose-100">{{ $activeHotspots }}</p>
        </div>
        <div class="rounded-2xl border border-slate-800 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-lg">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">End users</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-white">{{ $totalUsers }}</p>
            <p class="mt-1 text-xs text-slate-500">Role: user</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
            <h2 class="text-lg font-semibold text-white">Accidents per day</h2>
            <p class="text-xs text-slate-500">Last 7 days</p>
            <div class="mt-4 h-56">
                <canvas id="accidentChart"></canvas>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
            <h2 class="text-lg font-semibold text-white">Congestion per day</h2>
            <p class="text-xs text-slate-500">Last 7 days (reports filed)</p>
            <div class="mt-4 h-56">
                <canvas id="congestionChart"></canvas>
            </div>
        </div>
    </div>

    @include('admin.partials.hotspots-panel', ['hotspots' => $hotspots])

    <div class="space-y-8">
        <section>
            <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-white">
                <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                Accident queue
            </h2>
            @include('admin.partials.accident-table', ['accidents' => $accidents, 'officers' => $officers])
        </section>
        <section>
            <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-white">
                <span class="h-2 w-2 rounded-full bg-sky-400"></span>
                Congestion queue
            </h2>
            @include('admin.partials.congestion-table', ['congestionReports' => $congestionReports])
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const accLabels = {!! json_encode(array_keys($accidentsPerDay->toArray())) !!};
    const accValues = {!! json_encode(array_values($accidentsPerDay->toArray())) !!};
    const conLabels = {!! json_encode(array_keys($congestionPerDay->toArray())) !!};
    const conValues = {!! json_encode(array_values($congestionPerDay->toArray())) !!};

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { color: '#94a3b8' } } },
        scales: {
            x: { ticks: { color: '#64748b' }, grid: { color: 'rgba(148,163,184,0.08)' } },
            y: { ticks: { color: '#64748b' }, grid: { color: 'rgba(148,163,184,0.08)' }, beginAtZero: true }
        }
    };

    const a = document.getElementById('accidentChart');
    if (a) {
        new Chart(a, {
            type: 'line',
            data: {
                labels: accLabels.length ? accLabels : ['—'],
                datasets: [{
                    label: 'Accidents',
                    data: accValues.length ? accValues : [0],
                    borderColor: 'rgb(251, 191, 36)',
                    backgroundColor: 'rgba(251, 191, 36, 0.12)',
                    fill: true,
                    tension: 0.35,
                }]
            },
            options: commonOptions
        });
    }

    const c = document.getElementById('congestionChart');
    if (c) {
        new Chart(c, {
            type: 'bar',
            data: {
                labels: conLabels.length ? conLabels : ['—'],
                datasets: [{
                    label: 'Congestion reports',
                    data: conValues.length ? conValues : [0],
                    backgroundColor: 'rgba(56, 189, 248, 0.35)',
                    borderColor: 'rgb(56, 189, 248)',
                    borderWidth: 1,
                }]
            },
            options: commonOptions
        });
    }
})();
</script>
@endpush
