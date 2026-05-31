<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WorkerController extends Controller
{
    public function storeWorker(Request $request)
    {
        $owner = auth()->user();
        abort_if($owner->role !== 'owner', 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'worker',
            'owner_id' => $owner->id,
        ]);

        return back()->with('success', 'Worker berhasil ditambahkan');
    }

    public function destroy(User $worker)
    {
        abort_if($worker->owner_id !== auth()->id(), 403);
        $worker->delete();
        return back()->with('success', 'Worker berhasil dihapus');
    }
}
