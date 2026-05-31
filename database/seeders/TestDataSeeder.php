<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Plant;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Owner
        $owner = User::create([
            'name' => 'Budi Santoso',
            'email' => 'owner@sibucara.test',
            'password' => bcrypt('password123'),
            'role' => 'owner',
        ]);

        // Create Workers
        $worker1 = User::create([
            'name' => 'Andi Wijaya',
            'email' => 'worker1@sibucara.test',
            'password' => bcrypt('password123'),
            'role' => 'worker',
            'owner_id' => $owner->id,
        ]);

        $worker2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'worker2@sibucara.test',
            'password' => bcrypt('password123'),
            'role' => 'worker',
            'owner_id' => $owner->id,
        ]);

        // Create Penyuluh
        User::create([
            'name' => 'Dr. Hendri Pirmansyah',
            'email' => 'penyuluh@sibucara.test',
            'password' => bcrypt('password123'),
            'role' => 'penyuluh',
        ]);

        // Create Plants
        $plant1 = $owner->plants()->create([
            'name' => 'Cabai Rawit Merah',
            'variety' => 'Cabai Rawit',
            'plant_date' => Carbon::now()->subDays(30),
            'status' => 'active',
        ]);

        $plant2 = $owner->plants()->create([
            'name' => 'Cabai Rawit Putih',
            'variety' => 'Cabai Rawit',
            'plant_date' => Carbon::now()->subDays(15),
            'status' => 'active',
        ]);

        // Create Schedules for Plant1
        $this->createSchedules($plant1);

        // Create Schedules for Plant2
        $this->createSchedules($plant2);
    }

    private function createSchedules(Plant $plant)
    {
        $plantDate = Carbon::parse($plant->plant_date);

        $activities = [
            [
                'name' => 'Penyemaian',
                'days' => 0,
                'notes' => 'Lakukan penyemaian apabila kondisi tanah kering. Jika terjadi hujan atau tanah masih lembap, penyiraman tidak perlu dilakukan.',
                'status' => 'selesai',
            ],
            [
                'name' => 'Penanaman',
                'days' => 13,
                'notes' => 'Pindahkan bibit yang sehat ke lahan utama jika sudah berdaun 4-5 helai.',
                'status' => 'selesai',
            ],
            [
                'name' => 'Pemupukan I',
                'days' => 27,
                'notes' => 'Pemupukan dapat ditunda apabila terjadi hujan deras untuk menghindari pupuk terbawa aliran air.',
                'status' => 'sedang dikerjakan',
            ],
            [
                'name' => 'Pengendalian Hama',
                'days' => 35,
                'notes' => 'Pelaksanaan disesuaikan dengan kondisi cuaca dan tingkat serangan hama di lapangan.',
                'status' => 'belum dikerjakan',
            ],
            [
                'name' => 'Pemupukan II',
                'days' => 48,
                'notes' => 'Pemupukan dapat ditunda apabila terjadi hujan deras.',
                'status' => 'belum dikerjakan',
            ],
            [
                'name' => 'Panen',
                'days' => 92,
                'notes' => 'Lakukan pemanenan pada buah yang sudah matang 80-90% di pagi hari.',
                'status' => 'belum dikerjakan',
            ],
        ];

        foreach ($activities as $activity) {
            Schedule::create([
                'plant_id' => $plant->id,
                'activity_name' => $activity['name'],
                'target_date' => $plantDate->copy()->addDays($activity['days']),
                'status' => $activity['status'],
                'notes' => $activity['notes'],
            ]);
        }
    }
}
