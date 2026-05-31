<?php

namespace App\Http\Controllers;

use App\Models\PlantActivity;
use Illuminate\Http\Request;

class PlantActivityController extends Controller
{
    /**
     * Show activity details (untuk worker/owner)
     */
    public function show(PlantActivity $activity)
    {
        $activity->load('plant.owner', 'plant.variety', 'assignedUser');

        $user = auth()->user();
        if ($user->role === 'owner' && $activity->plant->owner_id !== $user->id) {
            abort(403);
        }

        if ($user->role === 'worker' && $activity->assigned_user_id !== $user->id) {
            abort(403);
        }

        return view('activities.show', compact('activity'));
    }

    /**
     * Update activity status (worker melakukan ini)
     */
    public function updateStatus(Request $request, PlantActivity $activity)
    {
        // Hanya assigned user atau owner yang bisa update
        $user = auth()->user();
        if (! in_array($user->role, ['owner', 'worker'], true)) {
            abort(403);
        }

        if ($user->role === 'worker' && $activity->assigned_user_id !== $user->id) {
            abort(403);
        } elseif ($user->role === 'owner' && $activity->plant->owner_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:belum_dikerjakan,sedang_dikerjakan,selesai,tidak_dilakukan',
            'notes' => 'nullable|string|max:1000',
        ]);

        $activity->status = $validated['status'];
        
        if ($validated['status'] === 'selesai') {
            $activity->done_at = now();
            $activity->is_done = true;
        } else {
            $activity->done_at = null;
            $activity->is_done = false;

            if ($validated['status'] === 'tidak_dilakukan') {
                $activity->notes = $validated['notes'] ?? 'Tidak dilakukan';
            }
        }

        if (!empty($validated['notes'])) {
            $activity->notes = $validated['notes'];
        }

        $activity->save();

        return back()->with('success', 'Status aktivitas berhasil diperbarui!');
    }

    /**
     * Mark as completed with notes
     */
    public function markComplete(Request $request, PlantActivity $activity)
    {
        $user = auth()->user();
        if (! in_array($user->role, ['owner', 'worker'], true)) {
            abort(403);
        }

        if ($user->role === 'worker' && $activity->assigned_user_id !== $user->id) {
            abort(403);
        }
        if ($user->role === 'owner' && $activity->plant->owner_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $activity->status = 'selesai';
        $activity->done_at = now();
        $activity->is_done = true;
        
        if (!empty($validated['notes'])) {
            $activity->notes = $validated['notes'];
        }

        $activity->save();

        return back()->with('success', 'Aktivitas berhasil ditandai selesai!');
    }

    /**
     * Mark as not done with reason
     */
    public function markNotDone(Request $request, PlantActivity $activity)
    {
        $user = auth()->user();
        if (! in_array($user->role, ['owner', 'worker'], true)) {
            abort(403);
        }

        if ($user->role === 'worker' && $activity->assigned_user_id !== $user->id) {
            abort(403);
        }
        if ($user->role === 'owner' && $activity->plant->owner_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $activity->status = 'tidak_dilakukan';
        $activity->notes = $validated['notes'];
        $activity->save();

        return back()->with('success', 'Aktivitas ditandai tidak dilakukan dengan alasan yang dicatat!');
    }
}
