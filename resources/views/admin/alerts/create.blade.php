@extends('layouts.app')

@section('title', 'Create Alert')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <header class="border-b border-slate-800/80 pb-6">
        <a href="{{ route('admin.alerts.index') }}" class="mb-4 inline-flex items-center text-sm text-slate-400 hover:text-slate-300">
            ← Back to Alerts
        </a>
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-amber-500/90">Admin Panel</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">Create New Alert</h1>
            <p class="mt-2 text-slate-400">Send a system-wide alert and guide users while they track request status on the user dashboard.</p>
        </div>
    </header>

    <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6 shadow-xl shadow-black/20 backdrop-blur">
        <form method="POST" action="{{ route('admin.alerts.store') }}" class="space-y-6">
            @csrf

            <!-- Alert Type -->
            <div>
                <label class="block text-sm font-semibold text-white mb-2">Alert Type</label>
                <select name="type" required class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-white focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                    <option value="">Select Alert Type</option>
                    <option value="accident">Accident Alert</option>
                    <option value="congestion">Congestion Alert</option>
                    <option value="road_closure">Road Closure</option>
                    <option value="maintenance">Road Maintenance</option>
                    <option value="general">General Notice</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div>
                <label class="block text-sm font-semibold text-white mb-2">Alert Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g., Major accident on Ring Road"
                    class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-600 focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                @error('title')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-white mb-2">Description</label>
                <textarea name="description" rows="4" required placeholder="Provide detailed information about the alert"
                    class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-600 focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Location -->
            <div>
                <label class="block text-sm font-semibold text-white mb-2">Location</label>
                <input type="text" name="location" value="{{ old('location') }}" placeholder="e.g., Ring Road near Metro Station"
                    class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-600 focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                @error('location')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Severity Level -->
            <div>
                <label class="block text-sm font-semibold text-white mb-2">Severity Level</label>
                <select name="severity" required class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-white focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                    <option value="critical">Critical</option>
                </select>
                @error('severity')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Estimated Duration -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-white mb-2">Expected Duration (minutes)</label>
                    <input type="number" name="duration" min="0" value="{{ old('duration', 60) }}"
                        class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-white focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white mb-2">Affected Area Radius (km)</label>
                    <input type="number" name="radius" min="0" step="0.1" value="{{ old('radius', 5) }}"
                        class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-white focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                </div>
            </div>

            <!-- Send Notification -->
            <div>
                <label class="flex items-center gap-3">
                    <input type="checkbox" name="send_notification" value="1" checked
                        class="w-4 h-4 rounded border-slate-700 bg-slate-950 text-amber-500 focus:ring-amber-500/40">
                    <span class="text-sm font-medium text-slate-300">Send notification to all users</span>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4">
                <a href="{{ route('admin.alerts.index') }}" class="flex-1 rounded-lg border border-slate-600 px-4 py-2.5 text-center font-semibold text-slate-300 transition hover:border-slate-500 hover:bg-slate-900 hover:text-white">
                    Cancel
                </a>
                <button type="submit" class="flex-1 rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 px-4 py-2.5 font-semibold text-slate-950 shadow-lg shadow-orange-500/25 transition hover:from-amber-400 hover:to-orange-500">
                    Create Alert
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
