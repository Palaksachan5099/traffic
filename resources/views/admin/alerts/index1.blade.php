@extends('layouts.app')

@section('title', 'Requests Queue')

@section('content')
<div class="space-y-6">
    <header class="border-b border-slate-800/80 pb-5">
        <p class="text-xs font-semibold uppercase tracking-widest text-amber-500/90">Admin Dashboard</p>
        <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">Requests Queue</h1>
        <p class="mt-2 text-slate-400">Focused queue view for pending user-submitted accidents and congestion reports.</p>
    </header>

    <div class="grid gap-6 lg:grid-cols-2">
        <section class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
            <h2 class="text-lg font-semibold text-white">Pending Accident Requests</h2>
            <p class="mt-2 text-sm text-slate-400">{{ $pendingAccidents->count() }} request(s) awaiting action.</p>
        </section>
        <section class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
            <h2 class="text-lg font-semibold text-white">Pending Congestion Reports</h2>
            <p class="mt-2 text-sm text-slate-400">{{ $pendingCongestion->count() }} report(s) awaiting action.</p>
        </section>
    </div>

    <a href="{{ route('admin.alerts.index') }}" class="inline-flex rounded-lg border border-slate-600 px-4 py-2 text-sm font-medium text-slate-300 transition hover:border-slate-500 hover:bg-slate-900 hover:text-white">
        Open full management dashboard
    </a>
</div>
@endsection
