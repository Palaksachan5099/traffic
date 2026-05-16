<div class="overflow-x-auto rounded-2xl border border-slate-800 bg-slate-950/40">
    <table class="min-w-full divide-y divide-slate-800 text-left text-sm">
        <thead class="bg-slate-900/80 text-xs font-semibold uppercase tracking-wide text-slate-400">
            <tr>
                <th class="px-4 py-3">Location</th>
                <th class="px-4 py-3">Reported</th>
                <th class="px-4 py-3">Severity</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Officer</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800 text-slate-300">
            @forelse($accidents as $accident)
                @php $coord = $accident->coordinates ?? []; @endphp
                <tr class="align-top hover:bg-slate-900/50">
                    <td class="px-4 py-3">
                        <p class="font-medium text-white">{{ $accident->location }}</p>
                        <p class="mt-1 line-clamp-2 max-w-xs text-xs text-slate-500">{{ Str::limit($accident->description, 120) }}</p>
                        @if(!empty($accident->image_path))
                            <a href="{{ asset('storage/'.$accident->image_path) }}" target="_blank" rel="noopener" class="mt-2 block">
                                <img src="{{ asset('storage/'.$accident->image_path) }}" alt="Accident image" class="h-16 w-24 rounded-lg border border-slate-700 object-cover">
                            </a>
                        @endif
                        @if(!empty($coord['lat']) && !empty($coord['lng']))
                            <a href="https://www.openstreetmap.org/?mlat={{ $coord['lat'] }}&mlon={{ $coord['lng'] }}&zoom=16" target="_blank" rel="noopener" class="mt-1 inline-block text-[10px] text-sky-400 hover:underline">Map ↗</a>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-4 py-3 text-xs text-slate-500">
                        {{ $accident->reported_at?->format('M j, H:i') ?? '—' }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex rounded-md border border-slate-700 bg-slate-900 px-2 py-0.5 text-xs capitalize text-amber-100/90">{{ $accident->severity }}</span>
                    </td>
                    <td class="px-4 py-3 capitalize text-amber-200/90">
                        <div>{{ $accident->status }}</div>
                        @if(($accident->status ?? null) === 'resolved' && ($accident->resolved_by_role ?? null) === 'officer')
                            <span class="mt-1 inline-flex rounded-md border border-emerald-500/40 bg-emerald-950/40 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-emerald-200">
                                Resolved by officer
                            </span>
                        @elseif(($accident->status ?? null) === 'resolved' && ($accident->resolved_by_role ?? null) === 'admin')
                            <span class="mt-1 inline-flex rounded-md border border-sky-500/40 bg-sky-950/40 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-sky-200">
                                Resolved by admin
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-400">
                        @if($accident->assignedOfficer)
                            {{ $accident->assignedOfficer->name }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex flex-col items-end gap-2 sm:flex-row sm:flex-wrap sm:justify-end">
                            @if(isset($officers) && $officers->isNotEmpty() && in_array($accident->status, ['pending', 'approved'], true))
                                <form action="{{ route('admin.accident.assign', $accident) }}" method="POST" class="flex flex-wrap items-center justify-end gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="officer_id" class="max-w-[9rem] rounded-lg border border-slate-700 bg-slate-950 px-2 py-1 text-xs text-white">
                                        @foreach($officers as $o)
                                            <option value="{{ $o->getKey() }}">{{ $o->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="rounded-lg bg-indigo-600/90 px-2 py-1 text-xs font-semibold text-white hover:bg-indigo-500">Assign</button>
                                </form>
                            @endif
                            @if($accident->assigned_officer_id)
                                <form action="{{ route('admin.accident.unassign', $accident) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rounded-lg border border-slate-600 px-2 py-1 text-xs text-slate-300 hover:bg-slate-800">Unassign</button>
                                </form>
                            @endif
                            @if($accident->status === 'pending')
                                <form action="{{ route('admin.accident.approve', $accident) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rounded-lg bg-emerald-600/90 px-2 py-1 text-xs font-semibold text-white hover:bg-emerald-500">Approve</button>
                                </form>
                            @endif
                            @if($accident->status === 'approved')
                                <form action="{{ route('admin.accident.resolve', $accident) }}" method="POST" class="inline">
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
                    <td colspan="6" class="px-4 py-8 text-center text-slate-500">No accidents in queue.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
