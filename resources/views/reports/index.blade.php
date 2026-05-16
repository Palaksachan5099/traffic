@extends('layouts.app')

@section('title', 'My reports')

@section('content')
<div class="space-y-10">
    <div>
        <h1 class="text-3xl font-bold text-white">My reports</h1>
        <p class="mt-2 text-slate-400">Everything you submitted: accidents and congestion.</p>
    </div>

    <div class="grid gap-8 md:grid-cols-2">
        <section class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6">
            <h2 class="font-semibold text-amber-200">Accidents</h2>
            <ul class="mt-4 space-y-3 text-sm">
                @forelse($accidents as $a)
                    <li class="rounded-xl border border-slate-800 bg-slate-950/50 px-4 py-3">
                        <span class="font-medium text-white">{{ $a->location }}</span>
                        <p class="text-slate-500">{{ $a->reported_at?->diffForHumans() }} · {{ $a->status }}</p>
                    </li>
                @empty
                    <li class="text-slate-500">None.</li>
                @endforelse
            </ul>
        </section>
        <section class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6">
            <h2 class="font-semibold text-sky-200">Congestion</h2>
            <ul class="mt-4 space-y-3 text-sm">
                @forelse($congestion as $t)
                    <li class="rounded-xl border border-slate-800 bg-slate-950/50 px-4 py-3">
                        <a href="{{ route('congestion.show', $t) }}" class="font-medium text-sky-300 hover:text-sky-200">{{ $t->location }}</a>
                        <p class="text-slate-500">{{ $t->reported_at?->diffForHumans() }} · {{ $t->status }}</p>
                    </li>
                @empty
                    <li class="text-slate-500">None.</li>
                @endforelse
            </ul>
        </section>
    </div>
</div>
@endsection
