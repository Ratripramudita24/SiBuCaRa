<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function workerDashboard()
    {
        $worker = auth()->user();
        $owner = $worker->owner;
        $plants = $owner->plants()->with('schedules')->get();
        $schedules = Schedule::whereIn('plant_id', $owner->plants()->pluck('id'))
            ->orderBy('target_date', 'asc')
            ->get();

        return view('worker.dashboard', compact('plants', 'schedules'));
    }

    public function updateStatus(Request $request, Schedule $schedule)
    {
        $plant = $schedule->plant;
        abort_if($plant->owner_id !== auth()->user()->owner_id, 403);

        $validated = $request->validate([
            'status' => 'required|in:belum dikerjakan,sedang dikerjakan,selesai,tidak dilakukan',
            'reason_not_done' => 'nullable|string|required_if:status,tidak dilakukan',
        ]);

        $schedule->update([
            'status' => $validated['status'],
            'reason_not_done' => $validated['reason_not_done'] ?? null,
        ]);

        return back()->with('success', 'Status jadwal berhasil diperbarui');
    }
}
