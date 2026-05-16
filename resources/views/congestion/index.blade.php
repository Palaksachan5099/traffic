@extends('layouts.app')

@section('title', 'Congestion')

@section('content')
<div class="space-y-10">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">Congestion reports</h1>
            <p class="mt-2 text-slate-400">Approved reports are public below. Submit new reports from the <a href="{{ route('dashboard') }}" class="text-sky-400 hover:underline">map dashboard</a> or post here.</p>
        </div>
    </div>

    <section class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6">
        <h2 class="text-lg font-semibold text-white">Submit congestion (same as traffic report)</h2>
        <form method="POST" action="{{ route('congestion.store') }}" class="mt-4 grid gap-4 sm:grid-cols-2">
            @csrf
            <div class="sm:col-span-2">
                <label class="text-xs uppercase text-slate-500">Location</label>
                <input name="location" required class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-2 text-white">
            </div>
            <div>
                <label class="text-xs uppercase text-slate-500">Latitude</label>
                <input name="lat" type="text" required class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-2 text-white" placeholder="From map">
            </div>
            <div>
                <label class="text-xs uppercase text-slate-500">Longitude</label>
                <input name="lng" type="text" required class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-2 text-white">
            </div>
            <div class="sm:col-span-2">
                <label class="text-xs uppercase text-slate-500">Cause</label>
                <textarea name="cause" required rows="2" class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-2 text-white"></textarea>
            </div>
            <div>
                <label class="text-xs uppercase text-slate-500">Level</label>
                <select name="congestion_level" class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-2 text-white">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                    <option value="severe">Severe</option>
                </select>
            </div>
            <div>
                <label class="text-xs uppercase text-slate-500">Delay (min)</label>
                <input name="delay_minutes" type="number" value="0" min="0" class="mt-1 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-2 text-white">
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2 font-semibold text-white hover:bg-sky-500">Submit</button>
            </div>
        </form>
    </section>

    <section>
        <h2 class="mb-4 text-lg font-semibold text-white">Approved (recent)</h2>
        <ul class="space-y-2">
            @forelse($public as $r)
                <li class="rounded-xl border border-slate-800 bg-slate-950/50 px-4 py-3">
                    <a href="{{ route('congestion.show', $r) }}" class="font-medium text-sky-300 hover:text-sky-200">{{ $r->location }}</a>
                    <span class="text-slate-500"> · {{ $r->congestion_level }} · {{ $r->reported_at?->diffForHumans() }}</span>
                </li>
            @empty
                <li class="text-slate-500">None yet.</li>
            @endforelse
        </ul>
    </section>

    <section>
        <h2 class="mb-4 text-lg font-semibold text-white">Yours (recent)</h2>
        <ul class="space-y-2">
            @forelse($mine as $r)
                <li class="rounded-xl border border-slate-800 bg-slate-950/50 px-4 py-3">
                    <a href="{{ route('congestion.show', $r) }}" class="font-medium text-slate-200 hover:text-white">{{ $r->location }}</a>
                    <span class="text-slate-500"> · {{ $r->status }} · {{ $r->reported_at?->diffForHumans() }}</span>
                </li>
            @empty
                <li class="text-slate-500">You have not filed congestion reports.</li>
            @endforelse
        </ul>
    </section>
</div>
@endsection
