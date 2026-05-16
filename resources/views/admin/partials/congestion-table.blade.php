@php
    $levelBadge = fn ($lvl) => match ($lvl) {
        'low' => 'border-emerald-500/40 bg-emerald-950/40 text-emerald-200',
        'medium' => 'border-amber-500/40 bg-amber-950/40 text-amber-200',
        'high' => 'border-orange-500/40 bg-orange-950/40 text-orange-200',
        'severe' => 'border-rose-500/40 bg-rose-950/40 text-rose-200',
        default => 'border-slate-600 bg-slate-900 text-slate-300',
    };
@endphp

<div class="overflow-x-auto rounded-2xl border border-slate-800 bg-slate-950/40">
    <table class="min-w-full divide-y divide-slate-800 text-left text-sm">
        <thead class="bg-slate-900/80 text-xs font-semibold uppercase tracking-wide text-slate-400">
            <tr>
                <th class="px-4 py-3">Location</th>
                <th class="px-4 py-3">Reported</th>
                <th class="px-4 py-3">Level</th>
                <th class="px-4 py-3">Delay</th>
                <th class="px-4 py-3">Cause</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800 text-slate-300">
            @forelse($congestionReports as $report)
                <tr class="align-top hover:bg-slate-900/50">
                    <td class="px-4 py-3">
                        <a href="{{ route('congestion.show', $report) }}" class="font-medium text-sky-300 hover:text-sky-200">{{ $report->location }}</a>
                        @if($report->user)
                            <p class="mt-1 text-[10px] text-slate-500">By {{ $report->user->name }}</p>
                        @endif
                        @if(!empty($report->image_path))
                            <a href="{{ asset('storage/'.$report->image_path) }}" target="_blank" rel="noopener" class="mt-2 block">
                                <img src="{{ asset('storage/'.$report->image_path) }}" alt="Congestion image" class="h-16 w-24 rounded-lg border border-slate-700 object-cover">
                            </a>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-4 py-3 text-xs text-slate-500">
                        {{ $report->reported_at?->format('M j, H:i') ?? '—' }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex rounded-md border px-2 py-0.5 text-xs capitalize {{ $levelBadge($report->congestion_level) }}">{{ $report->congestion_level }}</span>
                    </td>
                    <td class="whitespace-nowrap px-4 py-3 tabular-nums text-slate-400">{{ $report->delay_minutes ?? 0 }} min</td>
                    <td class="max-w-xs px-4 py-3 text-xs text-slate-500">{{ Str::limit($report->cause, 100) }}</td>
                    <td class="px-4 py-3 capitalize text-sky-200/90">{{ $report->status }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex flex-wrap justify-end gap-2">
                            @if($report->status === 'pending')
                                <form action="{{ route('admin.traffic.approve', $report) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rounded-lg bg-emerald-600/90 px-2 py-1 text-xs font-semibold text-white hover:bg-emerald-500">Approve</button>
                                </form>
                            @endif
                            @if($report->status === 'approved')
                                <form action="{{ route('admin.traffic.resolve', $report) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rounded-lg bg-slate-600 px-2 py-1 text-xs font-semibold text-white hover:bg-slate-500">Resolve</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-slate-500">No congestion reports in queue.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
