<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\PlantActivity;
use App\Models\Variety;
use App\Services\PlantService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class PlantController extends Controller
{
    protected $plantService;

    public function __construct(PlantService $plantService)
    {
        $this->plantService = $plantService;
    }

    /**
     * Dashboard - Main entry point
     */
    public function dashboard()
    {
        $user = auth()->user();

        if ($user->role === 'owner') {
            return $this->ownerDashboard();
        } elseif ($user->role === 'worker') {
            return $this->workerDashboard();
        } elseif ($user->role === 'penyuluh') {
            return $this->penyuluhDashboard();
        }

        return redirect('/');
    }

    /**
     * Owner Dashboard
     */
    private function ownerDashboard()
    {
        $user = auth()->user();
        $plants = $user->plants()
            ->with(['activities' => fn ($query) => $query->orderBy('planned_date'), 'variety'])
            ->latest()
            ->get();

        $activities = $plants->flatMap->activities;
        $totalActivities = $activities->count();
        $completedActivities = $activities->where('status', 'selesai')->count();
        $inProgressActivities = $activities->where('status', 'sedang_dikerjakan')->count();
        $notDoneActivities = $activities->where('status', 'tidak_dilakukan')->count();
        $pendingActivities = $activities
            ->whereIn('status', ['belum_dikerjakan', 'sedang_dikerjakan'])
            ->count();

        $progressPercentage = $totalActivities > 0
            ? round(($completedActivities / $totalActivities) * 100) 
            : 0;

        $workers = $user->workers()->latest()->get();

        $todayActivities = PlantActivity::with('plant')
            ->whereHas('plant', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })
            ->where('planned_date', '<=', Carbon::today())
            ->where('status', '!=', 'selesai')
            ->orderBy('planned_date')
            ->get();

        return view('dashboard', [
            'plants' => $plants,
            'workers' => $workers,
            'totalActivities' => $totalActivities,
            'completedActivities' => $completedActivities,
            'pendingActivities' => $pendingActivities,
            'inProgressActivities' => $inProgressActivities,
            'notDoneActivities' => $notDoneActivities,
            'progressPercentage' => $progressPercentage,
            'todayActivities' => $todayActivities,
        ]);
    }

    /**
     * Worker Dashboard
     */
    private function workerDashboard()
    {
        $user = auth()->user();
        $activities = $user->assignedActivities()
            ->with('plant', 'plant.owner', 'plant.variety')
            ->orderBy('planned_date')
            ->get();

        $totalAssigned = count($activities);
        $completedCount = $activities->where('status', 'selesai')->count();
        $pendingCount = $activities->where('status', 'belum_dikerjakan')->count();

        // Today's activities assigned to this worker
        $todayActivities = $user->assignedActivities()
            ->with('plant')
            ->where('planned_date', '<=', Carbon::today())
            ->where('status', '!=', 'selesai')
            ->orderBy('planned_date')
            ->get();

        return view('dashboard', [
            'activities' => $activities,
            'totalAssigned' => $totalAssigned,
            'completedCount' => $completedCount,
            'pendingCount' => $pendingCount,
            'todayActivities' => $todayActivities,
        ]);
    }

    /**
     * Penyuluh Dashboard
     */
    private function penyuluhDashboard()
    {
        $plants = Plant::with('activities', 'owner', 'variety')->get();
        $allActivities = PlantActivity::with('plant', 'assignedUser')->get();

        $activityStats = [
            'total' => count($allActivities),
            'completed' => $allActivities->where('status', 'selesai')->count(),
            'pending' => $allActivities->where('status', 'belum_dikerjakan')->count(),
            'in_progress' => $allActivities->where('status', 'sedang_dikerjakan')->count(),
            'not_done' => $allActivities->where('status', 'tidak_dilakukan')->count(),
        ];

        $todayActivities = PlantActivity::with('plant')
            ->where('planned_date', '<=', Carbon::today())
            ->where('status', '!=', 'selesai')
            ->orderBy('planned_date')
            ->get();

        $lateActivities = $todayActivities->where('planned_date', '<', Carbon::today())->take(8);
        $notDoneActivities = $allActivities
            ->where('status', 'tidak_dilakukan')
            ->sortByDesc('updated_at')
            ->take(8);

        return view('dashboard', [
            'plants' => $plants,
            'activityStats' => $activityStats,
            'totalPlants' => count($plants),
            'todayActivities' => $todayActivities,
            'lateActivities' => $lateActivities,
            'notDoneActivities' => $notDoneActivities,
        ]);
    }

    /**
     * Show all plants (untuk owner)
     */
    public function index()
    {
        $this->authorizeOwner();

        $plants = auth()->user()->plants()
            ->with(['variety', 'activities' => fn ($query) => $query->orderBy('planned_date')])
            ->latest()
            ->get();

        return view('plants.index', compact('plants'));
    }

    /**
     * Show form to create new plant
     */
    public function create()
    {
        $this->authorizeOwner();

        $varieties = Variety::all();

        return view('plants.create', compact('varieties'));
    }

    /**
     * Store new plant and generate jadwal
     */
    public function store(Request $request)
    {
        $this->authorizeOwner();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'variety_id' => 'required|exists:varieties,id',
            'start_date' => 'required|date|after_or_equal:today',
            'location' => 'nullable|string|max:255',
        ]);

        $validated['owner_id'] = auth()->id();

        $plant = $this->plantService->create($validated);

        return redirect()->route('plants.show', $plant)->with('success', 'Tanaman berhasil ditambahkan dan jadwal otomatis telah dibuat!');
    }

    /**
     * Show plant details and activities
     */
    public function show(Plant $plant)
    {
        $plant->load('activities.assignedUser', 'variety', 'owner');

        // Hanya owner atau penyuluh/worker yang berkaitan yang bisa lihat
        if (auth()->user()->role === 'owner' && $plant->owner_id !== auth()->id()) {
            abort(403);
        }

        if (auth()->user()->role === 'worker' && ! $plant->activities->contains('assigned_user_id', auth()->id())) {
            abort(403);
        }

        return view('plants.show', compact('plant'));
    }

    /**
     * Show edit form
     */
    public function edit(Plant $plant)
    {
        $this->authorizeOwner();

        if ($plant->owner_id !== auth()->id()) {
            abort(403);
        }

        $varieties = Variety::all();

        return view('plants.edit', compact('plant', 'varieties'));
    }

    /**
     * Update plant
     */
    public function update(Request $request, Plant $plant)
    {
        $this->authorizeOwner();

        if ($plant->owner_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'variety_id' => 'required|exists:varieties,id',
            'location' => 'nullable|string|max:255',
        ]);

        $plant->update($validated);

        return redirect()->route('plants.show', $plant)->with('success', 'Tanaman berhasil diperbarui!');
    }

    /**
     * Delete plant
     */
    public function destroy(Plant $plant)
    {
        $this->authorizeOwner();

        if ($plant->owner_id !== auth()->id()) {
            abort(403);
        }

        $plant->delete();

        return redirect()->route('plants.index')->with('success', 'Tanaman berhasil dihapus!');
    }

    /**
     * List jadwal/aktivitas untuk semua tanaman milik owner
     */
    public function schedules(Request $request)
    {
        $this->authorizeOwner();

        $activities = $this->ownerScheduleQuery($request)
            ->orderBy('planned_date')
            ->get();

        return view('schedules.index', compact('activities'));
    }

    public function printSchedules(Request $request)
    {
        $this->authorizeOwner();

        $activities = $this->ownerScheduleQuery($request)
            ->orderBy('planned_date')
            ->get();

        return view('schedules.print', compact('activities'));
    }

    /**
     * Calendar view
     */
    public function calendarView()
    {
        $activities = $this->visibleActivities()
            ->with('plant.variety')
            ->orderBy('planned_date', 'asc')
            ->get();

        return view('kalender', compact('activities'));
    }

    /**
     * Report view
     */
    public function report()
    {
        $activitiesQuery = $this->visibleActivities();

        $historyActivities = (clone $activitiesQuery)
            ->with('plant.variety', 'assignedUser')
            ->where('status', 'selesai')
            ->orderBy('done_at', 'desc')
            ->get();

        $totalActivities = (clone $activitiesQuery)->count();
        $doneActivities = (clone $activitiesQuery)->where('status', 'selesai')->count();
        $pendingActivities = (clone $activitiesQuery)->whereIn('status', ['belum_dikerjakan', 'sedang_dikerjakan'])->count();
        $notDoneActivities = (clone $activitiesQuery)->where('status', 'tidak_dilakukan')->count();

        return view('laporan', compact('historyActivities', 'totalActivities', 'doneActivities', 'pendingActivities', 'notDoneActivities'));
    }

    private function visibleActivities(): Builder
    {
        $user = auth()->user();

        $query = PlantActivity::query();

        if ($user->role === 'owner') {
            return $query->whereHas('plant', fn ($plantQuery) => $plantQuery->where('owner_id', $user->id));
        }

        if ($user->role === 'worker') {
            return $query->where('assigned_user_id', $user->id);
        }

        return $query;
    }

    private function ownerScheduleQuery(Request $request): Builder
    {
        return PlantActivity::with('plant.variety', 'assignedUser')
            ->whereHas('plant', function ($query) {
                $query->where('owner_id', auth()->id());
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = $request->query('q');

                $query->where(function ($activityQuery) use ($keyword) {
                    $activityQuery
                        ->where('title', 'like', '%' . $keyword . '%')
                        ->orWhereHas('plant', fn ($plantQuery) => $plantQuery->where('name', 'like', '%' . $keyword . '%'));
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->query('status')));
    }

    /**
     * Helper: Authorize owner
     */
    private function authorizeOwner()
    {
        if (auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized');
        }
    }
}
