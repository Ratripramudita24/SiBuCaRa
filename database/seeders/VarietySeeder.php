<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Variety;

class VarietySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Variety::create([
            'name' => 'Cabai Rawit Hijau',
            'color' => 'hijau',
            'description' => 'Varietas cabai rawit dengan warna hijau, pedas dan cocok untuk masakan tradisional'
        ]);

        Variety::create([
            'name' => 'Cabai Rawit Merah',
            'color' => 'merah',
            'description' => 'Varietas cabai rawit dengan warna merah, pedas sekali dan banyak digunakan untuk sambal'
        ]);

        Variety::create([
            'name' => 'Cabai Rawit Putih',
            'color' => 'putih',
            'description' => 'Varietas cabai rawit dengan warna putih, sangat pedas dan langka di pasaran'
        ]);
    }
}
