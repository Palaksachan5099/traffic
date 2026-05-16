@extends('layouts.app')

@section('title', 'Statistics & Analytics')

@section('content')
<div class="space-y-8">
    <header class="border-b border-slate-800/80 pb-6">
        <a href="{{ route('admin.reports.generate') }}" class="mb-4 inline-flex items-center text-sm text-slate-400 hover:text-slate-300">
            ← Back to Reports
        </a>
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-amber-500/90">Admin Panel</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">Statistics & Analytics</h1>
            <p class="mt-2 max-w-2xl text-slate-400">Real-time insights and trends in traffic incidents and user activities</p>
        </div>
    </header>

    <!-- Key Metrics -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-800 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-lg">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Accidents (7 days)</p>
            <p class="mt-2 text-3xl font-bold text-white">{{ $accidentCount7d ?? 0 }}</p>
            <p class="mt-1 text-xs {{ ($accidentTrend ?? 0) >= 0 ? 'text-red-400' : 'text-green-400' }}">
                {{ ($accidentTrend ?? 0) >= 0 ? '↑' : '↓' }} {{ abs($accidentTrend ?? 0) }}% vs previous week
            </p>
        </div>

        <div class="rounded-2xl border border-sky-500/25 bg-gradient-to-br from-sky-950/50 to-slate-950 p-5 shadow-lg">
            <p class="text-xs font-medium uppercase tracking-wide text-sky-200/80">Congestion (7 days)</p>
            <p class="mt-2 text-3xl font-bold text-sky-100">{{ $congestionCount7d ?? 0 }}</p>
            <p class="mt-1 text-xs {{ ($congestionTrend ?? 0) >= 0 ? 'text-red-400' : 'text-green-400' }}">
                {{ ($congestionTrend ?? 0) >= 0 ? '↑' : '↓' }} {{ abs($congestionTrend ?? 0) }}% vs previous week
            </p>
        </div>

        <div class="rounded-2xl border border-green-500/25 bg-gradient-to-br from-green-950/50 to-slate-950 p-5 shadow-lg">
            <p class="text-xs font-medium uppercase tracking-wide text-green-200/80">Resolved (7 days)</p>
            <p class="mt-2 text-3xl font-bold text-green-100">{{ $resolvedCount7d ?? 0 }}</p>
            <p class="mt-1 text-xs text-slate-400">{{ $resolutionRate ?? 0 }}% resolution rate</p>
        </div>

        <div class="rounded-2xl border border-amber-500/20 bg-gradient-to-br from-amber-950/40 to-slate-950 p-5 shadow-lg">
            <p class="text-xs font-medium uppercase tracking-wide text-amber-200/80">Active Users</p>
            <p class="mt-2 text-3xl font-bold text-amber-100">{{ $activeUsers7d ?? 0 }}</p>
            <p class="mt-1 text-xs text-slate-400">New users: {{ $newUsers ?? 0 }}</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Daily Incident Trend (30 days)</h2>
            <div class="h-64">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Incident Distribution</h2>
            <div class="h-64">
                <canvas id="distributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Severity Breakdown -->
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
            <h3 class="font-semibold text-white mb-4">Accident Severity</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-300">Low Severity</span>
                    <span class="font-semibold text-green-400">{{ $accidentLow ?? 0 }}</span>
                </div>
                <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500" style="width: {{ ($accidentLow ?? 0) / (($accidentLow ?? 0) + ($accidentMed ?? 0) + ($accidentHigh ?? 0) + 1) * 100 }}%"></div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <span class="text-sm text-slate-300">Medium Severity</span>
                    <span class="font-semibold text-yellow-400">{{ $accidentMed ?? 0 }}</span>
                </div>
                <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-yellow-500" style="width: {{ ($accidentMed ?? 0) / (($accidentLow ?? 0) + ($accidentMed ?? 0) + ($accidentHigh ?? 0) + 1) * 100 }}%"></div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <span class="text-sm text-slate-300">High Severity</span>
                    <span class="font-semibold text-red-400">{{ $accidentHigh ?? 0 }}</span>
                </div>
                <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-red-500" style="width: {{ ($accidentHigh ?? 0) / (($accidentLow ?? 0) + ($accidentMed ?? 0) + ($accidentHigh ?? 0) + 1) * 100 }}%"></div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
            <h3 class="font-semibold text-white mb-4">Congestion Levels</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-300">Low</span>
                    <span class="font-semibold text-green-400">{{ $congestionLow ?? 0 }}</span>
                </div>
                <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500" style="width: {{ ($congestionLow ?? 0) / (($congestionLow ?? 0) + ($congestionMed ?? 0) + ($congestionHigh ?? 0) + ($congestionSevere ?? 0) + 1) * 100 }}%"></div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <span class="text-sm text-slate-300">Medium</span>
                    <span class="font-semibold text-yellow-400">{{ $congestionMed ?? 0 }}</span>
                </div>
                <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-yellow-500" style="width: {{ ($congestionMed ?? 0) / (($congestionLow ?? 0) + ($congestionMed ?? 0) + ($congestionHigh ?? 0) + ($congestionSevere ?? 0) + 1) * 100 }}%"></div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <span class="text-sm text-slate-300">High</span>
                    <span class="font-semibold text-orange-400">{{ $congestionHigh ?? 0 }}</span>
                </div>
                <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-orange-500" style="width: {{ ($congestionHigh ?? 0) / (($congestionLow ?? 0) + ($congestionMed ?? 0) + ($congestionHigh ?? 0) + ($congestionSevere ?? 0) + 1) * 100 }}%"></div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <span class="text-sm text-slate-300">Severe</span>
                    <span class="font-semibold text-red-400">{{ $congestionSevere ?? 0 }}</span>
                </div>
                <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-red-500" style="width: {{ ($congestionSevere ?? 0) / (($congestionLow ?? 0) + ($congestionMed ?? 0) + ($congestionHigh ?? 0) + ($congestionSevere ?? 0) + 1) * 100 }}%"></div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
            <h3 class="font-semibold text-white mb-4">Report Status</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-300">Pending</span>
                    <span class="font-semibold text-yellow-400">{{ $statusPending ?? 0 }}</span>
                </div>
                <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-yellow-500" style="width: {{ ($statusPending ?? 0) / (($statusPending ?? 0) + ($statusApproved ?? 0) + ($statusResolved ?? 0) + 1) * 100 }}%"></div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <span class="text-sm text-slate-300">Approved</span>
                    <span class="font-semibold text-blue-400">{{ $statusApproved ?? 0 }}</span>
                </div>
                <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500" style="width: {{ ($statusApproved ?? 0) / (($statusPending ?? 0) + ($statusApproved ?? 0) + ($statusResolved ?? 0) + 1) * 100 }}%"></div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <span class="text-sm text-slate-300">Resolved</span>
                    <span class="font-semibold text-green-400">{{ $statusResolved ?? 0 }}</span>
                </div>
                <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500" style="width: {{ ($statusResolved ?? 0) / (($statusPending ?? 0) + ($statusApproved ?? 0) + ($statusResolved ?? 0) + 1) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Hotspots -->
    <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
        <h3 class="font-semibold text-white mb-4">Top Incident Hotspots</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-300">Location</th>
                        <th class="px-4 py-3 text-right font-semibold text-slate-300">Incidents</th>
                        <th class="px-4 py-3 text-right font-semibold text-slate-300">Avg Severity</th>
                        <th class="px-4 py-3 text-right font-semibold text-slate-300">Resolution Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($topHotspots ?? [] as $hotspot)
                        <tr class="hover:bg-slate-800/30">
                            <td class="px-4 py-3 text-slate-200">{{ $hotspot['location'] ?? 'Unknown' }}</td>
                            <td class="px-4 py-3 text-right text-slate-300">{{ $hotspot['count'] ?? 0 }}</td>
                            <td class="px-4 py-3 text-right text-slate-300">{{ round($hotspot['severity'] ?? 0, 1) }}</td>
                            <td class="px-4 py-3 text-right text-slate-300">{{ $hotspot['avg_resolution'] ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-slate-400">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($trendLabels ?? []) !!},
            datasets: [
                {
                    label: 'Accidents',
                    data: {!! json_encode($trendAccidents ?? []) !!},
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Congestion',
                    data: {!! json_encode($trendCongestion ?? []) !!},
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14, 165, 233, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { color: '#94a3b8' }
                }
            },
            scales: {
                x: {
                    ticks: { color: '#64748b' },
                    grid: { color: 'rgba(148,163,184,0.08)' }
                },
                y: {
                    ticks: { color: '#64748b' },
                    grid: { color: 'rgba(148,163,184,0.08)' },
                    beginAtZero: true
                }
            }
        }
    });

    // Distribution Chart
    const distCtx = document.getElementById('distributionChart').getContext('2d');
    new Chart(distCtx, {
        type: 'doughnut',
        data: {
            labels: ['Accidents', 'Congestion'],
            datasets: [{
                data: {!! json_encode([$accidentCount7d ?? 0, $congestionCount7d ?? 0]) !!},
                backgroundColor: ['#f59e0b', '#0ea5e9'],
                borderColor: ['#1f2937', '#1f2937'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { color: '#94a3b8' },
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
