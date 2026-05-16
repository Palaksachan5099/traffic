@extends('layouts.app')

@section('title', 'Completed Updates')

@section('content')
<div class="space-y-6">
    <header class="border-b border-slate-800/80 pb-5">
        <p class="text-xs font-semibold uppercase tracking-widest text-amber-500/90">Admin Dashboard</p>
        <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">Completed Updates</h1>
        <p class="mt-2 text-slate-400">Recent completions visible to users in their dashboard history.</p>
    </header>

    <section class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
        <h2 class="text-lg font-semibold text-white">Recently Completed</h2>
        <div class="mt-4 space-y-2">
            @forelse($completedAccidents as $accident)
                <div class="rounded-lg border border-green-500/20 bg-green-950/20 p-3 text-sm text-green-100">{{ $accident->location }} · Accident</div>
            @empty
                <p class="text-sm text-slate-400">No completed accident items yet.</p>
            @endforelse

            @forelse($completedCongestion as $report)
                <div class="rounded-lg border border-green-500/20 bg-green-950/20 p-3 text-sm text-green-100">{{ $report->location }} · Congestion</div>
            @empty
                <p class="text-sm text-slate-400">No completed congestion items yet.</p>
            @endforelse
        </div>
    </section>

    <a href="{{ route('admin.alerts.index') }}" class="inline-flex rounded-lg border border-slate-600 px-4 py-2 text-sm font-medium text-slate-300 transition hover:border-slate-500 hover:bg-slate-900 hover:text-white">
        Open full management dashboard
    </a>
</div>
@endsection
