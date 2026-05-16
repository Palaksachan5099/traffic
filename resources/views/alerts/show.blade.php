@extends('layouts.app')

@section('title', 'Alert detail')

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <a href="{{ route('alerts.index') }}" class="text-sm text-sky-400 hover:text-sky-300">← All alerts</a>
    <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
        <p class="text-xs font-semibold uppercase tracking-wide text-amber-400/90">{{ $type }}</p>
        <h1 class="mt-2 text-2xl font-bold text-white">{{ $payload['title'] ?? 'Alert' }}</h1>
        <dl class="mt-6 space-y-3 text-sm">
            <div class="flex justify-between gap-4 border-b border-slate-800 pb-2">
                <dt class="text-slate-500">Status</dt>
                <dd class="capitalize text-slate-200">{{ $payload['status'] ?? '—' }}</dd>
            </div>
            @if($type === 'accident')
                <div class="flex justify-between gap-4 border-b border-slate-800 pb-2">
                    <dt class="text-slate-500">Severity</dt>
                    <dd class="capitalize text-slate-200">{{ $payload['severity'] ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Description</dt>
                    <dd class="mt-1 text-slate-300">{{ $payload['description'] ?? '—' }}</dd>
                </div>
            @else
                <div class="flex justify-between gap-4 border-b border-slate-800 pb-2">
                    <dt class="text-slate-500">Congestion</dt>
                    <dd class="capitalize text-slate-200">{{ $payload['congestion_level'] ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Cause</dt>
                    <dd class="mt-1 text-slate-300">{{ $payload['description'] ?? '—' }}</dd>
                </div>
            @endif
            @if(!empty($payload['lat']) && !empty($payload['lng']))
                <div class="flex justify-between gap-4 pt-2">
                    <dt class="text-slate-500">Coordinates</dt>
                    <dd class="font-mono text-xs text-slate-400">{{ $payload['lat'] }}, {{ $payload['lng'] }}</dd>
                </div>
            @endif
        </dl>
    </div>
</div>
@endsection
