@extends('layouts.app')

@section('title', 'Manage Alerts')

@section('content')
<div class="space-y-8">
    <header class="flex flex-wrap items-end justify-between gap-3 border-b border-slate-800/80 pb-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-amber-500/90">Admin Dashboard</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">User Requests and Problems</h1>
            <p class="mt-2 max-w-2xl text-slate-400">Review requests, move work to in-progress, then mark complete. Status and notes are shown on the user dashboard.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.alerts.create') }}" class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-medium text-slate-950 transition hover:bg-amber-400">Create Alert</a>
            <a href="{{ route('admin.alerts.index1') }}" class="rounded-xl border border-slate-600 px-4 py-2 text-sm font-medium text-slate-300 transition hover:border-slate-500 hover:bg-slate-900 hover:text-white">Queue View</a>
            <a href="{{ route('admin.alerts.index2') }}" class="rounded-xl border border-slate-600 px-4 py-2 text-sm font-medium text-slate-300 transition hover:border-slate-500 hover:bg-slate-900 hover:text-white">Completed View</a>
            <a href="{{ route('admin.alerts.statistics') }}" class="rounded-xl border border-slate-600 px-4 py-2 text-sm font-medium text-slate-300 transition hover:border-slate-500 hover:bg-slate-900 hover:text-white">Statistics</a>
            <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-slate-600 px-4 py-2 text-sm font-medium text-slate-300 transition hover:border-slate-500 hover:bg-slate-900 hover:text-white">Back</a>
        </div>
    </header>

    <div class="grid gap-6 lg:grid-cols-2">
        <section class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
            <h2 class="text-lg font-semibold text-white">Pending Accident Requests</h2>
            <div class="mt-4 space-y-4">
                @forelse($pendingAccidents as $accident)
                    <div class="rounded-xl border border-slate-700 bg-slate-950/60 p-4">
                        <p class="font-medium text-white">{{ $accident->location }}</p>
                        <p class="mt-1 text-sm text-slate-300">{{ $accident->description }}</p>
                        <p class="mt-2 text-xs text-slate-400">From: {{ $accident->user->name ?? 'User' }} · {{ ucfirst($accident->severity) }}</p>
                        <div class="mt-3 flex gap-2">
                            <form method="POST" action="{{ route('admin.alerts.status.update', ['type' => 'accident', 'id' => $accident->id]) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="in_progress">
                                <input type="hidden" name="completion" value="25">
                                <button class="rounded-lg bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-500">Start Processing</button>
                            </form>
                            <form method="POST" action="{{ route('admin.alerts.status.update', ['type' => 'accident', 'id' => $accident->id]) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button class="rounded-lg border border-red-500/40 px-3 py-2 text-sm text-red-300 hover:bg-red-950/40">Reject</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">No pending accident requests.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
            <h2 class="text-lg font-semibold text-white">Pending Congestion Reports</h2>
            <div class="mt-4 space-y-4">
                @forelse($pendingCongestion as $report)
                    <div class="rounded-xl border border-slate-700 bg-slate-950/60 p-4">
                        <p class="font-medium text-white">{{ $report->location }}</p>
                        <p class="mt-1 text-sm text-slate-300">{{ $report->cause }}</p>
                        <p class="mt-2 text-xs text-slate-400">From: {{ $report->user->name ?? 'User' }} · {{ ucfirst($report->congestion_level) }}</p>
                        <div class="mt-3 flex gap-2">
                            <form method="POST" action="{{ route('admin.alerts.status.update', ['type' => 'congestion', 'id' => $report->id]) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="in_progress">
                                <input type="hidden" name="completion" value="25">
                                <button class="rounded-lg bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-500">Start Processing</button>
                            </form>
                            <form method="POST" action="{{ route('admin.alerts.status.update', ['type' => 'congestion', 'id' => $report->id]) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button class="rounded-lg border border-red-500/40 px-3 py-2 text-sm text-red-300 hover:bg-red-950/40">Reject</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">No pending congestion reports.</p>
                @endforelse
            </div>
        </section>
    </div>

    <section class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
        <h2 class="text-lg font-semibold text-white">In Progress</h2>
        <div class="mt-4 space-y-3">
            @foreach($inProgressAccidents as $accident)
                <form method="POST" action="{{ route('admin.alerts.status.update', ['type' => 'accident', 'id' => $accident->id]) }}" class="rounded-xl border border-slate-700 bg-slate-950/60 p-4">
                    @csrf
                    @method('PATCH')
                    <p class="font-medium text-white">{{ $accident->location }} <span class="text-xs text-slate-400">(Accident)</span></p>
                    <div class="mt-3 grid gap-2 md:grid-cols-3">
                        <input type="number" name="completion" min="0" max="100" value="{{ $accident->completion ?? 50 }}" class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white">
                        <input type="text" name="admin_notes" value="{{ $accident->admin_notes }}" placeholder="Admin update for user" class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white md:col-span-2">
                    </div>
                    <div class="mt-3 flex gap-2">
                        <button name="status" value="in_progress" class="rounded-lg border border-slate-600 px-3 py-2 text-sm text-slate-300 hover:bg-slate-800">Save Progress</button>
                        <button name="status" value="resolved" class="rounded-lg bg-green-600 px-3 py-2 text-sm text-white hover:bg-green-500">Mark Complete</button>
                    </div>
                </form>
            @endforeach
            @foreach($inProgressCongestion as $report)
                <form method="POST" action="{{ route('admin.alerts.status.update', ['type' => 'congestion', 'id' => $report->id]) }}" class="rounded-xl border border-slate-700 bg-slate-950/60 p-4">
                    @csrf
                    @method('PATCH')
                    <p class="font-medium text-white">{{ $report->location }} <span class="text-xs text-slate-400">(Congestion)</span></p>
                    <div class="mt-3 grid gap-2 md:grid-cols-3">
                        <input type="number" name="completion" min="0" max="100" value="{{ $report->completion ?? 50 }}" class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white">
                        <input type="text" name="admin_notes" value="{{ $report->admin_notes }}" placeholder="Admin update for user" class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white md:col-span-2">
                    </div>
                    <div class="mt-3 flex gap-2">
                        <button name="status" value="in_progress" class="rounded-lg border border-slate-600 px-3 py-2 text-sm text-slate-300 hover:bg-slate-800">Save Progress</button>
                        <button name="status" value="resolved" class="rounded-lg bg-green-600 px-3 py-2 text-sm text-white hover:bg-green-500">Mark Complete</button>
                    </div>
                </form>
            @endforeach
            @if($inProgressAccidents->isEmpty() && $inProgressCongestion->isEmpty())
                <p class="text-sm text-slate-400">No items currently in progress.</p>
            @endif
        </div>
    </section>

    <section class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
        <h2 class="text-lg font-semibold text-white">Recently Completed</h2>
        <div class="mt-4 space-y-2">
            @foreach($completedAccidents as $accident)
                <div class="rounded-lg border border-green-500/20 bg-green-950/20 p-3 text-sm text-green-100">{{ $accident->location }} · Accident · {{ $accident->resolved_at?->diffForHumans() }}</div>
            @endforeach
            @foreach($completedCongestion as $report)
                <div class="rounded-lg border border-green-500/20 bg-green-950/20 p-3 text-sm text-green-100">{{ $report->location }} · Congestion · {{ $report->resolved_at?->diffForHumans() }}</div>
            @endforeach
            @if($completedAccidents->isEmpty() && $completedCongestion->isEmpty())
                <p class="text-sm text-slate-400">No completed items yet.</p>
            @endif
        </div>
    </section>
</div>
@endsection
