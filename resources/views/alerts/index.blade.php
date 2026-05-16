@extends('layouts.app')

@section('title', 'Alerts')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">Active alerts</h1>
            <p class="mt-2 text-slate-400">Accidents and congestion (pending and approved).</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-amber-300 hover:text-amber-200">Back to map</a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/40">
        <table class="min-w-full divide-y divide-slate-800 text-left text-sm">
            <thead class="bg-slate-900/80 text-xs uppercase tracking-wide text-slate-400">
                <tr>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">When</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($alerts as $row)
                    <tr class="text-slate-300 hover:bg-slate-900/60">
                        <td class="px-4 py-3 capitalize text-amber-200/90">{{ $row['kind'] }}</td>
                        <td class="px-4 py-3 font-medium text-white">{{ $row['title'] }}</td>
                        <td class="px-4 py-3 capitalize">{{ $row['status'] }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $row['reported_at'] ? \Illuminate\Support\Carbon::parse($row['reported_at'])->diffForHumans() : '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('alerts.show', ['type' => $row['kind'], 'id' => $row['id']]) }}" class="text-sm text-sky-400 hover:text-sky-300">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">No alerts.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
