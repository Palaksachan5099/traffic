@extends('layouts.app')

@section('title', 'Assignments')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-white">Accident assignments</h1>
        <p class="mt-2 text-slate-400">
            @if(auth()->user()->role === 'admin')
                All accidents with an assigned officer.
            @else
                Accidents assigned to you.
            @endif
        </p>
    </div>

    <div class="space-y-4">
        @forelse($accidents as $a)
            <article class="rounded-2xl border border-slate-800 bg-slate-900/50 p-5">
                <h2 class="text-lg font-semibold text-white">{{ $a->location }}</h2>
                <p class="mt-1 text-sm text-slate-400 capitalize">{{ $a->severity }} · {{ $a->status }}</p>
                <p class="mt-3 text-slate-300">{{ $a->description }}</p>
                @if(!empty($a->image_path))
                    <a href="{{ asset('storage/'.$a->image_path) }}" target="_blank" rel="noopener" class="mt-3 block">
                        <img src="{{ asset('storage/'.$a->image_path) }}" alt="Accident image" class="h-28 w-full max-w-xs rounded-xl border border-slate-700 object-cover">
                    </a>
                @endif
                @if($a->user)
                    <p class="mt-2 text-xs text-slate-500">Reported by {{ $a->user->name }}</p>
                @endif
                @if(auth()->user()->role === 'admin' && $a->assignedOfficer)
                    <p class="mt-2 text-xs text-indigo-300">Officer: {{ $a->assignedOfficer->name }}</p>
                @endif
                @if(auth()->user()->role === 'officer' && $a->status !== 'resolved')
                    <form action="{{ route('assignments.resolve', $a) }}" method="POST" class="mt-4">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="rounded-lg bg-emerald-600/90 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-500">
                            Mark as Resolved
                        </button>
                    </form>
                @endif
            </article>
        @empty
            <p class="text-slate-500">No assignments to show.</p>
        @endforelse
    </div>
</div>
@endsection
