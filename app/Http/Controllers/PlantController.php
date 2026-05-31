<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\PlantActivity;
use App\Services\PlantService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PlantController extends Controller
{
    // 1. HALAMAN BERANDA / MONITORING (Diakses lewat route '/dashboard')
    public function dashboard()
    {
        // Mengambil tugas hari ini atau yang terlewat untuk To-Do List di Beranda
        $todayActivities = PlantActivity::with('plant')
            ->where('planned_date', '<=', Carbon::today())
            ->where('is_done', false)
            ->orderBy('planned_date', 'asc')
            ->get();

        // Mengambil ringkasan blok lahan aktif untuk progress bar di Beranda
        $plants = Plant::withCount([
            'activities as total_tasks',
            'activities as completed_tasks' => function ($query) {
                $query->where('is_done', true);
            }
        ])->where('status', 'active')->get();

        return view('dashboard', compact('todayActivities', 'plants'));
    }

    // 2. HALAMAN JADWAL (Diakses lewat route resource '/plants' atau plants.index)
    // Menampilkan murni Form Detail Penanaman (Sesuai gambar instruksi Anda)
    public function index()
    {
        $plants = Plant::with('activities')->orderBy('created_at', 'desc')->get();

        // Diarahkan tepat ke file view form jadwal di folder plants
        return view('plants.index', compact('plants'));
    }

    // 3. PROSES SIMPAN DATA TANAMAN BARU (plants.store)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'location' => 'nullable|string|max:255'
        ]);

        app(PlantService::class)->create($request->all());

        // Setelah simpan sukses, diredirect langsung ke dashboard utama
        return redirect()->route('dashboard')->with('success', 'Jadwal budidaya berhasil dibuat!');
    }

    // 4. HAPUS DATA TANAMAN (plants.destroy)
    public function destroy($id)
    {
        Plant::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    // 5. HALAMAN KALENDER (plants.calendar)
    public function calendarView()
    {
        $activities = PlantActivity::with('plant')->orderBy('planned_date', 'asc')->get();
        return view('kalender', compact('activities'));
    }

    // 6. HALAMAN LAPORAN (plants.report)
    public function report()
    {
        // Hanya mengambil list riwayat aktivitas yang sudah selesai diceklis
        $historyActivities = PlantActivity::with('plant')
            ->where('is_done', true)
            ->orderBy('done_at', 'desc')
            ->get();

        $totalActivities = PlantActivity::count();
        $doneActivities = PlantActivity::where('is_done', true)->count();
        $pendingActivities = PlantActivity::where('is_done', false)->count();

        return view('laporan', compact('historyActivities', 'totalActivities', 'doneActivities', 'pendingActivities'));
    }
}
