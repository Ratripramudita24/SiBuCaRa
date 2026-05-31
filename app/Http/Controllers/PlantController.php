<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PlantController extends Controller
{
    public function ownerDashboard()
    {
        $owner = auth()->user();
        $plants = $owner->plants()->with('schedules')->get();
        $workers = $owner->workers()->get();

        return view('owner.dashboard', compact('plants', 'workers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'plant_date' => 'required|date',
        ]);

        $plant = auth()->user()->plants()->create([
            'name' => $validated['name'],
            'variety' => 'Cabai Rawit',
            'plant_date' => $validated['plant_date'],
            'status' => 'active',
        ]);

        $this->generateSchedules($plant);

        return back()->with('success', 'Tanaman berhasil ditambahkan dan jadwal otomatis telah dibuat');
    }

    private function generateSchedules(Plant $plant)
    {
        $plantDate = Carbon::parse($plant->plant_date);

        $activities = [
            [
                'name' => 'Penyemaian',
                'days' => 0,
                'notes' => 'Lakukan penyemaian apabila kondisi tanah kering. Jika terjadi hujan atau tanah masih lembap, penyiraman tidak perlu dilakukan.',
            ],
            [
                'name' => 'Penanaman',
                'days' => 13,
                'notes' => 'Pindahkan bibit yang sehat ke lahan utama jika sudah berdaun 4-5 helai.',
            ],
            [
                'name' => 'Pemupukan I',
                'days' => 27,
                'notes' => 'Pemupukan dapat ditunda apabila terjadi hujan deras untuk menghindari pupuk terbawa aliran air.',
            ],
            [
                'name' => 'Pengendalian Hama',
                'days' => 35,
                'notes' => 'Pelaksanaan disesuaikan dengan kondisi cuaca dan tingkat serangan hama di lapangan.',
            ],
            [
                'name' => 'Pemupukan II',
                'days' => 48,
                'notes' => 'Pemupukan dapat ditunda apabila terjadi hujan deras.',
            ],
            [
                'name' => 'Panen',
                'days' => 92,
                'notes' => 'Lakukan pemanenan pada buah yang sudah matang 80-90% di pagi hari.',
            ],
        ];

        foreach ($activities as $activity) {
            Schedule::create([
                'plant_id' => $plant->id,
                'activity_name' => $activity['name'],
                'target_date' => $plantDate->copy()->addDays($activity['days']),
                'status' => 'belum dikerjakan',
                'notes' => $activity['notes'],
            ]);
        }
    }

    public function show(Plant $plant)
    {
        abort_if($plant->owner_id !== auth()->id(), 403);
        $plant->load('schedules', 'recommendations');
        return view('owner.plant-detail', compact('plant'));
    }

    public function destroy(Plant $plant)
    {
        abort_if($plant->owner_id !== auth()->id(), 403);
        $plant->delete();
        return back()->with('success', 'Tanaman berhasil dihapus');
    }
}

