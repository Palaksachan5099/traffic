@extends('layouts.app')

@section('title', 'Alert Statistics')

@section('content')
<div class="space-y-8">
    <header class="border-b border-slate-800/80 pb-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-amber-500/90">Admin Alerts</p>
        <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">Request Processing Statistics</h1>
        <p class="mt-2 max-w-2xl text-slate-400">Track how user-submitted problems move from pending to completion.</p>
    </header>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-yellow-500/20 bg-yellow-950/10 p-5">
            <p class="text-xs uppercase tracking-wide text-yellow-200/80">Pending</p>
            <p class="mt-2 text-2xl font-bold text-yellow-100">{{ ($pendingAccidents ?? 0) + ($pendingCongestion ?? 0) }}</p>
        </div>
        <div class="rounded-2xl border border-blue-500/20 bg-blue-950/10 p-5">
            <p class="text-xs uppercase tracking-wide text-blue-200/80">In Progress</p>
            <p class="mt-2 text-2xl font-bold text-blue-100">{{ ($inProgressAccidents ?? 0) + ($inProgressCongestion ?? 0) }}</p>
        </div>
        <div class="rounded-2xl border border-green-500/20 bg-green-950/10 p-5">
            <p class="text-xs uppercase tracking-wide text-green-200/80">Resolved</p>
            <p class="mt-2 text-2xl font-bold text-green-100">{{ ($resolvedAccidents ?? 0) + ($resolvedCongestion ?? 0) }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
        <h2 class="text-lg font-semibold text-white">Breakdown</h2>
        <ul class="mt-4 space-y-2 text-sm text-slate-300">
            <li>Pending accidents: {{ $pendingAccidents ?? 0 }}</li>
            <li>Pending congestion: {{ $pendingCongestion ?? 0 }}</li>
            <li>In-progress accidents: {{ $inProgressAccidents ?? 0 }}</li>
            <li>In-progress congestion: {{ $inProgressCongestion ?? 0 }}</li>
            <li>Resolved accidents: {{ $resolvedAccidents ?? 0 }}</li>
            <li>Resolved congestion: {{ $resolvedCongestion ?? 0 }}</li>
        </ul>
    </div>
</div>
@endsection
