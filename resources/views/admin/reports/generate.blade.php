@extends('layouts.app')

@section('title', 'Generate Reports')

@section('content')
<div class="space-y-8">
    <header class="border-b border-slate-800/80 pb-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-amber-500/90">Admin Panel</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">Generate Reports</h1>
            <p class="mt-2 max-w-2xl text-slate-400">Create comprehensive reports on accidents, traffic congestion, and user activities for analysis and planning.</p>
        </div>
        <div class="mt-3 flex flex-wrap gap-2">
            <a href="{{ route('admin.dashboard') }}" class="rounded-lg border border-slate-600 px-3 py-1.5 text-xs font-medium text-slate-300 hover:bg-slate-900">Admin dashboard</a>
            <a href="{{ route('admin.alerts.index') }}" class="rounded-lg border border-slate-600 px-3 py-1.5 text-xs font-medium text-slate-300 hover:bg-slate-900">Manage requests</a>
            <a href="{{ route('admin.alerts.statistics') }}" class="rounded-lg border border-slate-600 px-3 py-1.5 text-xs font-medium text-slate-300 hover:bg-slate-900">Alert statistics</a>
        </div>
    </header>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Quick Stats -->
        <div class="rounded-2xl border border-slate-800 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-lg">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Total Accidents</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-white">{{ $totalAccidents ?? 0 }}</p>
            <p class="mt-1 text-xs text-slate-400">This month: {{ $monthAccidents ?? 0 }}</p>
        </div>

        <div class="rounded-2xl border border-sky-500/25 bg-gradient-to-br from-sky-950/50 to-slate-950 p-5 shadow-lg">
            <p class="text-xs font-medium uppercase tracking-wide text-sky-200/80">Congestion Reports</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-sky-100">{{ $totalCongestion ?? 0 }}</p>
            <p class="mt-1 text-xs text-slate-400">This month: {{ $monthCongestion ?? 0 }}</p>
        </div>

        <div class="rounded-2xl border border-amber-500/20 bg-gradient-to-br from-amber-950/40 to-slate-950 p-5 shadow-lg">
            <p class="text-xs font-medium uppercase tracking-wide text-amber-200/80">Active Users</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-amber-100">{{ $activeUsers ?? 0 }}</p>
            <p class="mt-1 text-xs text-slate-400">Reporters this month: {{ $activeReporters ?? 0 }}</p>
        </div>
    </div>

    <!-- Report Generation Options -->
    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Accident Report -->
        <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6 shadow-xl shadow-black/20 backdrop-blur">
            <div class="mb-4 flex items-center gap-3">
                <div class="h-12 w-12 rounded-lg bg-amber-600/20 flex items-center justify-center">
                    <span class="text-xl">🚗</span>
                </div>
                <div>
                    <h3 class="font-semibold text-white">Accident Report</h3>
                    <p class="text-xs text-slate-400">Detailed analysis of traffic accidents</p>
                </div>
            </div>
            <form action="{{ route('admin.reports.download-accidents') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium uppercase tracking-wide text-slate-500 mb-2">Date Range</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="date" name="start_date" value="{{ old('start_date', now()->subDays(30)->format('Y-m-d')) }}" 
                            class="rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                        <input type="date" name="end_date" value="{{ old('end_date', now()->format('Y-m-d')) }}"
                            class="rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium uppercase tracking-wide text-slate-500 mb-2">Severity Filter</label>
                    <select name="severity" class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                        <option value="">All Severities</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <button type="submit" class="w-full rounded-lg bg-amber-600 px-4 py-2.5 font-semibold text-white shadow-lg shadow-amber-600/25 transition hover:bg-amber-500">
                    Generate PDF Report
                </button>
            </form>
        </div>

        <!-- Congestion Report -->
        <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6 shadow-xl shadow-black/20 backdrop-blur">
            <div class="mb-4 flex items-center gap-3">
                <div class="h-12 w-12 rounded-lg bg-sky-600/20 flex items-center justify-center">
                    <span class="text-xl">🚦</span>
                </div>
                <div>
                    <h3 class="font-semibold text-white">Congestion Report</h3>
                    <p class="text-xs text-slate-400">Traffic and congestion patterns analysis</p>
                </div>
            </div>
            <form action="{{ route('admin.reports.download-congestion') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium uppercase tracking-wide text-slate-500 mb-2">Date Range</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="date" name="start_date" value="{{ old('start_date', now()->subDays(30)->format('Y-m-d')) }}"
                            class="rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                        <input type="date" name="end_date" value="{{ old('end_date', now()->format('Y-m-d')) }}"
                            class="rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium uppercase tracking-wide text-slate-500 mb-2">Congestion Level</label>
                    <select name="level" class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-sky-500/50 focus:outline-none focus:ring-2 focus:ring-sky-500/40">
                        <option value="">All Levels</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="severe">Severe</option>
                    </select>
                </div>
                <button type="submit" class="w-full rounded-lg bg-sky-600 px-4 py-2.5 font-semibold text-white shadow-lg shadow-sky-600/25 transition hover:bg-sky-500">
                    Generate PDF Report
                </button>
            </form>
        </div>
    </div>

    <!-- User Activity Report -->
    <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6 shadow-xl shadow-black/20 backdrop-blur">
        <div class="mb-4 flex items-center gap-3">
            <div class="h-12 w-12 rounded-lg bg-violet-600/20 flex items-center justify-center">
                <span class="text-xl">👥</span>
            </div>
            <div>
                <h3 class="font-semibold text-white">User Activity Report</h3>
                <p class="text-xs text-slate-400">Track user engagement and reporting patterns</p>
            </div>
        </div>
        <form action="{{ route('admin.reports.download-user-activity') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium uppercase tracking-wide text-slate-500 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date', now()->subDays(30)->format('Y-m-d')) }}"
                        class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-violet-500/50 focus:outline-none focus:ring-2 focus:ring-violet-500/40">
                </div>
                <div>
                    <label class="block text-xs font-medium uppercase tracking-wide text-slate-500 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date', now()->format('Y-m-d')) }}"
                        class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-violet-500/50 focus:outline-none focus:ring-2 focus:ring-violet-500/40">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full rounded-lg bg-violet-600 px-4 py-2.5 font-semibold text-white shadow-lg shadow-violet-600/25 transition hover:bg-violet-500">
                        Generate Report
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Export Options -->
    <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6 shadow-xl shadow-black/20 backdrop-blur">
        <h3 class="font-semibold text-white mb-4">Export Data</h3>
        <div class="flex flex-wrap gap-3">
            <form action="{{ route('admin.reports.export-excel') }}" method="POST">
                @csrf
                <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-500">
                    Export to Excel
                </button>
            </form>
            <form action="{{ route('admin.reports.export-csv') }}" method="POST">
                @csrf
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                    Export to CSV
                </button>
            </form>
            <a href="{{ route('admin.reports.statistics') }}" class="rounded-lg border border-slate-600 px-4 py-2 text-sm font-medium text-slate-300 transition hover:border-slate-500 hover:bg-slate-900 hover:text-white">
                View Statistics
            </a>
        </div>
    </div>
</div>
@endsection
