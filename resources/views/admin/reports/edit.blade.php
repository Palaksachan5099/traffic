@extends('layouts.app')

@section('title', 'Edit Report')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <header class="border-b border-slate-800/80 pb-6">
        <a href="{{ route('admin.reports.generate') }}" class="mb-4 inline-flex items-center text-sm text-slate-400 hover:text-slate-300">
            ← Back to Reports
        </a>
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-amber-500/90">Admin Panel</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">Edit Report & Task Status</h1>
            <p class="mt-2 text-slate-400">Update report details and mark tasks as complete for users</p>
        </div>
    </header>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Report Details -->
        <div class="lg:col-span-2 rounded-2xl border border-slate-800 bg-slate-900/50 p-6 shadow-xl shadow-black/20 backdrop-blur">
            <h2 class="text-lg font-semibold text-white mb-6">Report Details</h2>
            <form method="POST" action="{{ route('admin.reports.update', $report->id ?? '#') }}" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Report Type (Read-only) -->
                <div>
                    <label class="block text-sm font-semibold text-white mb-2">Report Type</label>
                    <div class="rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-slate-300">
                        {{ isset($report) && $report->severity ? 'Accident' : 'Traffic Congestion' }}
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-sm font-semibold text-white mb-2">Location</label>
                    <input type="text" name="location" value="{{ old('location', $report->location ?? '') }}" required
                        class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-600 focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                    @error('location')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-white mb-2">Description / Details</label>
                    <textarea name="description" rows="4" required
                        class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-600 focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">{{ old('description', $report->description ?? $report->cause ?? '') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-white mb-2">Status</label>
                    <select name="status" class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-white focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                        <option value="pending" {{ old('status', $report->status ?? '') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="approved" {{ old('status', $report->status ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="in_progress" {{ old('status', $report->status ?? '') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ old('status', $report->status ?? '') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="rejected" {{ old('status', $report->status ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Severity/Congestion Level -->
                <div>
                    <label class="block text-sm font-semibold text-white mb-2">Severity/Level</label>
                    <select name="severity" class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-white focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                        <option value="low" {{ old('severity', $report->severity ?? $report->congestion_level ?? '') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('severity', $report->severity ?? $report->congestion_level ?? '') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('severity', $report->severity ?? $report->congestion_level ?? '') === 'high' ? 'selected' : '' }}>High</option>
                        @if(!isset($report->severity))
                            <option value="severe" {{ old('severity', $report->congestion_level ?? '') === 'severe' ? 'selected' : '' }}>Severe</option>
                        @endif
                    </select>
                </div>

                <!-- Admin Notes -->
                <div>
                    <label class="block text-sm font-semibold text-white mb-2">Admin Notes / Actions Taken</label>
                    <textarea name="admin_notes" rows="3" placeholder="What actions have been taken to resolve this?"
                        class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-600 focus:border-amber-500/50 focus:outline-none focus:ring-2 focus:ring-amber-500/40">{{ old('admin_notes', $report->admin_notes ?? '') }}</textarea>
                </div>

                <!-- Completion Percentage -->
                <div>
                    <label class="block text-sm font-semibold text-white mb-2">Completion Progress (%)</label>
                    <input type="range" name="completion" min="0" max="100" value="{{ old('completion', $report->completion ?? 0) }}" 
                        class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-amber-500">
                    <div class="mt-2 flex justify-between text-xs text-slate-400">
                        <span>0%</span>
                        <span id="completionValue">{{ old('completion', $report->completion ?? 0) }}%</span>
                        <span>100%</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4">
                    <a href="{{ route('admin.reports.generate') }}" class="flex-1 rounded-lg border border-slate-600 px-4 py-2.5 text-center font-semibold text-slate-300 transition hover:border-slate-500 hover:bg-slate-900 hover:text-white">
                        Cancel
                    </a>
                    <button type="submit" class="flex-1 rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 px-4 py-2.5 font-semibold text-slate-950 shadow-lg shadow-orange-500/25 transition hover:from-amber-400 hover:to-orange-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Actions & Info -->
        <div class="space-y-4">
            <!-- Report Info -->
            <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-4">
                <h3 class="font-semibold text-white mb-4">Report Information</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Reporter</p>
                        <p class="text-white">{{ $report->user->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-slate-400">{{ $report->user->email ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Reported Date</p>
                        <p class="text-white">{{ $report->reported_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Report ID</p>
                        <p class="text-slate-300 font-mono text-xs">{{ $report->id ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-4">
                <h3 class="font-semibold text-white mb-4">Current Status</h3>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-950/30 text-yellow-200 border-yellow-500/25',
                        'approved' => 'bg-blue-950/30 text-blue-200 border-blue-500/25',
                        'in_progress' => 'bg-purple-950/30 text-purple-200 border-purple-500/25',
                        'resolved' => 'bg-green-950/30 text-green-200 border-green-500/25',
                        'rejected' => 'bg-red-950/30 text-red-200 border-red-500/25',
                    ];
                    $currentStatus = old('status', $report->status ?? 'pending');
                @endphp
                <div class="px-4 py-3 rounded-lg border {{ $statusColors[$currentStatus] ?? 'bg-slate-800/50 border-slate-700' }}">
                    <p class="text-sm font-semibold">{{ ucfirst(str_replace('_', ' ', $currentStatus)) }}</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-4">
                <h3 class="font-semibold text-white mb-3">Quick Actions</h3>
                <div class="space-y-2">
                    <button type="button" onclick="setStatus('approved')" class="w-full rounded-lg bg-green-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-green-500">
                        Approve Report
                    </button>
                    <button type="button" onclick="setStatus('in_progress')" class="w-full rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-blue-500">
                        Mark In Progress
                    </button>
                    <button type="button" onclick="setStatus('resolved')" class="w-full rounded-lg bg-purple-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-purple-500">
                        Mark Complete
                    </button>
                    <button type="button" onclick="setStatus('rejected')" class="w-full rounded-lg border border-red-500/50 bg-red-950/20 px-3 py-2 text-sm font-medium text-red-200 transition hover:bg-red-950/40">
                        Reject Report
                    </button>
                </div>
            </div>

            <!-- Notify User -->
            <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="notify_user" checked class="w-4 h-4 rounded border-slate-700 bg-slate-950 text-amber-500">
                    <span class="text-sm text-white">Notify user of changes</span>
                </label>
            </div>
        </div>
    </div>
</div>

<script>
    // Update completion percentage display
    document.querySelector('input[name="completion"]').addEventListener('input', function() {
        document.getElementById('completionValue').textContent = this.value + '%';
    });

    // Quick action functions
    function setStatus(status) {
        document.querySelector('select[name="status"]').value = status;
    }
</script>
@endsection
