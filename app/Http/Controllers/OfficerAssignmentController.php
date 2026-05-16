<?php

namespace App\Http\Controllers;

use App\Models\Accident;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Assign traffic officers (users with role officer) to accident records.
 */
class OfficerAssignmentController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $role = strtolower(trim((string) ($user->role ?? '')));

        if ($role === 'admin') {
            $accidents = Accident::query()
                ->with('assignedOfficer')
                ->whereNotNull('assigned_officer_id')
                ->latest('assigned_at')
                ->limit(100)
                ->get();
        } elseif ($role === 'officer') {
            $userKey = (string) $user->getKey();
            $accidents = Accident::query()
                ->with(['assignedOfficer', 'user'])
                ->whereIn('assigned_officer_id', [$user->getKey(), $userKey])
                ->whereIn('status', ['approved', 'pending'])
                ->latest('assigned_at')
                ->get();
        } else {
            abort(403);
        }

        return view('officer.assignments', compact('accidents'));
    }

    public function assign(Request $request, Accident $accident): RedirectResponse
    {
        $request->validate([
            'officer_id' => 'required|string',
        ]);

        $officerInput = trim((string) $request->officer_id);
        $officer = User::query()
            ->get()
            ->first(function (User $candidate) use ($officerInput): bool {
                $candidateRole = strtolower(trim((string) ($candidate->role ?? '')));
                if ($candidateRole !== 'officer') {
                    return false;
                }

                return (string) $candidate->getKey() === $officerInput
                    || strcasecmp((string) $candidate->name, $officerInput) === 0;
            });

        if (! $officer) {
            return back()->withErrors(['officer_id' => 'Select a valid officer.'])->withInput();
        }

        $accident->update([
            'assigned_officer_id' => $officer->getKey(),
            'assigned_at' => now(),
        ]);

        return back()->with('success', 'Officer assigned to this accident.');
    }

    public function unassign(Accident $accident): RedirectResponse
    {
        $accident->update([
            'assigned_officer_id' => null,
            'assigned_at' => null,
        ]);

        return back()->with('success', 'Officer unassigned.');
    }

    public function resolveByOfficer(Accident $accident): RedirectResponse
    {
        $user = auth()->user();
        $role = strtolower(trim((string) ($user->role ?? '')));

        if ($role !== 'officer') {
            abort(403, 'Only officers can resolve from this page.');
        }

        $assignedToOfficer = in_array(
            (string) ($accident->assigned_officer_id ?? ''),
            [(string) $user->getKey(), (string) $user->id],
            true
        );

        if (! $assignedToOfficer) {
            return back()->withErrors([
                'resolve' => 'You can only resolve accidents assigned to you.',
            ]);
        }

        if (($accident->status ?? null) === 'resolved') {
            return back()->with('status', 'This accident is already resolved.');
        }

        $accident->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by_id' => $user->getKey(),
            'resolved_by_role' => 'officer',
        ]);

        return back()->with('success', 'Accident resolved and visible to admin.');
    }
}
