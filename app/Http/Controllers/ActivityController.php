<?php

namespace App\Http\Controllers;

use App\Models\PlantActivity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function done($id)
    {
        $activity = PlantActivity::findOrFail($id);

        $activity->update([
            'is_done'=>true,
            'done_at'=>now()
        ]);

        return redirect()->back();
    }

    public function undo($id)
    {
        $activity = PlantActivity::findOrFail($id);

        $activity->update([
            'is_done'=>false,
            'done_at'=>null
        ]);

        return redirect()->back();
    }
}
