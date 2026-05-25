<?php

namespace App\Services;

use App\Models\Plant;
use App\Models\PlantActivity;
use App\Models\Notification;
use Carbon\Carbon;

class PlantService
{
    public function create(array $data)
    {
        $plant = Plant::create($data);

        $this->generateActivities($plant);

        return $plant;
    }

    private function generateActivities($plant)
    {
        $steps = [
            ['title'=>'Seleksi Benih','desc'=>'Pilih benih terbaik','day'=>0],
            ['title'=>'Penyemaian','desc'=>'Media semai','day'=>7],
            ['title'=>'Pemindahan','desc'=>'Pindah tanam','day'=>21],
            ['title'=>'Perawatan','desc'=>'Penyiraman & pupuk','day'=>35],
            ['title'=>'Panen','desc'=>'Cabai siap panen','day'=>90],
        ];

        $order = 0;

        foreach ($steps as $step) {

            $date = Carbon::parse($plant->start_date)
                ->addDays($step['day']);

            $activity = PlantActivity::create([
                'plant_id'=>$plant->id,
                'title'=>$step['title'],
                'description'=>$step['desc'],
                'planned_date'=>$date,
                'order_index'=>$order++
            ]);

            Notification::create([
                'plant_activity_id'=>$activity->id,
                'channel'=>'whatsapp',
                'message'=>$step['title'].' harus dilakukan',
                'scheduled_at'=>$date
            ]);
        }

        $plant->update([
            'harvest_date'=>Carbon::parse($plant->start_date)->addDays(90)
        ]);
    }
}
