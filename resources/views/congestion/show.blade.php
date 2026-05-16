@extends('layouts.app')

@section('title', $report->location)

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <a href="{{ route('congestion.index') }}" class="text-sm text-sky-400 hover:text-sky-300">← Congestion</a>
    <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
        <h1 class="text-2xl font-bold text-white">{{ $report->location }}</h1>
        <p class="mt-2 text-sm capitalize text-slate-400">{{ $report->status }} · {{ $report->congestion_level }} congestion</p>
        <p class="mt-6 text-slate-300">{{ $report->cause }}</p>
        @php $c = $report->coordinates ?? []; @endphp
        @if(!empty($c['lat']) && !empty($c['lng']))
            <p class="mt-4 font-mono text-xs text-slate-500">{{ $c['lat'] }}, {{ $c['lng'] }}</p>
        @endif
    </div>
</div>
@endsection
