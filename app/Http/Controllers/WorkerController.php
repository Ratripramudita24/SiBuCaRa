<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WorkerController extends Controller
{
    /**
     * Show all workers created by this owner
     */
    public function index()
    {
        if (auth()->user()->role !== 'owner') {
            abort(403);
        }

        $workers = auth()->user()->workers()
            ->withCount([
                'assignedActivities as total_assigned_count',
                'assignedActivities as completed_assigned_count' => fn ($query) => $query->where('status', 'selesai'),
            ])
            ->latest()
            ->get();

        return view('workers.index', compact('workers'));
    }

    /**
     * Show form to create new worker
     */
    public function create()
    {
        if (auth()->user()->role !== 'owner') {
            abort(403);
        }

        return view('workers.create');
    }

    /**
     * Store new worker (created by owner)
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'owner') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $worker = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'worker',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('workers.index')->with('success', 'Akun worker berhasil dibuat!');
    }

    /**
     * Show worker details
     */
    public function show(User $worker)
    {
        if ($worker->role !== 'worker' || $worker->created_by !== auth()->id()) {
            abort(403);
        }

        $activities = $worker->assignedActivities()->with('plant')->orderBy('planned_date')->get();
        $completedCount = $activities->where('status', 'selesai')->count();
        $pendingCount = $activities->where('status', 'belum_dikerjakan')->count();

        return view('workers.show', compact('worker', 'activities', 'completedCount', 'pendingCount'));
    }

    /**
     * Show edit form
     */
    public function edit(User $worker)
    {
        if ($worker->role !== 'worker' || $worker->created_by !== auth()->id()) {
            abort(403);
        }

        return view('workers.edit', compact('worker'));
    }

    /**
     * Update worker details
     */
    public function update(Request $request, User $worker)
    {
        if ($worker->role !== 'worker' || $worker->created_by !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $worker->id,
        ]);

        $worker->update($validated);

        return redirect()->route('workers.show', $worker)->with('success', 'Data worker berhasil diperbarui!');
    }

    /**
     * Delete worker
     */
    public function destroy(User $worker)
    {
        if ($worker->role !== 'worker' || $worker->created_by !== auth()->id()) {
            abort(403);
        }

        $worker->delete();

        return redirect()->route('workers.index')->with('success', 'Worker berhasil dihapus!');
    }

    /**
     * Reset worker password (owner bisa reset)
     */
    public function resetPassword(Request $request, User $worker)
    {
        if ($worker->role !== 'worker' || $worker->created_by !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $worker->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password worker berhasil direset!');
    }
}
