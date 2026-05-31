<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed varietas terlebih dahulu
        $this->call(VarietySeeder::class);

        // Create demo users with different roles
        User::factory()->create([
            'name' => 'Pemilik Kebun',
            'email' => 'owner@example.com',
            'role' => 'owner',
        ]);

        User::factory()->create([
            'name' => 'Pekerja Kebun',
            'email' => 'worker@example.com',
            'role' => 'worker',
            'created_by' => 1, // Created by owner
        ]);

        User::factory()->create([
            'name' => 'Penyuluh',
            'email' => 'penyuluh@example.com',
            'role' => 'penyuluh',
        ]);
    }
}
