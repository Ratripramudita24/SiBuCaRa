<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\Recommendation;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function penyuluhDashboard()
    {
        $plants = Plant::with('owner', 'schedules', 'recommendations')->get();
        return view('penyuluh.dashboard', compact('plants'));
    }

    public function store(Request $request, Plant $plant)
    {
        $validated = $request->validate([
            'recommendation_text' => 'required|string|min:10',
        ]);

        Recommendation::create([
            'plant_id' => $plant->id,
            'penyuluh_id' => auth()->id(),
            'recommendation_text' => $validated['recommendation_text'],
        ]);

        return back()->with('success', 'Rekomendasi berhasil dikirim');
    }
}
