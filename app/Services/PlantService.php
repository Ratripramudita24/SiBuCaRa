<?php

namespace App\Services;

use App\Models\Plant;
use App\Models\PlantActivity;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PlantService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $plant = Plant::create($data);

            $this->generateActivities($plant);

            return $plant;
        });
    }

    private function generateActivities($plant)
    {
        for ($day = 1; $day <= 92; $day++) {
            $step = $this->dailyScheduleForDay($day);
            $date = Carbon::parse($plant->start_date)
                ->addDays($day);

            $activity = PlantActivity::create([
                'plant_id' => $plant->id,
                'assigned_user_id' => null,
                'title' => $step['title'],
                'description' => $step['description'],
                'planned_date' => $date,
                'status' => 'belum_dikerjakan',
                'order_index' => $day,
                'notes' => $step['notes'],
                'system_notes' => $step['system_notes'],
            ]);

            // Notifikasi ke owner untuk monitoring
            Notification::create([
                'plant_activity_id' => $activity->id,
                'channel' => 'system',
                'message' => $step['title'] . ' dijadwalkan untuk ' . $date->format('d/m/Y'),
                'scheduled_at' => $date,
                'recipient_role' => 'owner',
                'recipient_user_id' => $plant->owner_id,
            ]);

            // Notifikasi ke penyuluh untuk monitoring
            Notification::create([
                'plant_activity_id' => $activity->id,
                'channel' => 'system',
                'message' => $step['title'] . ' dijadwalkan untuk ' . $date->format('d/m/Y') . ' (tanaman: ' . $plant->name . ')',
                'scheduled_at' => $date,
                'recipient_role' => 'penyuluh',
            ]);
        }

        $plant->update([
            'harvest_date' => Carbon::parse($plant->start_date)->addDays(92),
        ]);
    }

    public function regenerateActivities(Plant $plant): void
    {
        DB::transaction(function () use ($plant) {
            $plant->activities()->delete();
            $this->generateActivities($plant->fresh('owner'));
        });
    }

    public function syncDailyActivitiesForOwner(int $ownerId): void
    {
        Plant::query()
            ->where('owner_id', $ownerId)
            ->with('activities')
            ->get()
            ->each(fn (Plant $plant) => $this->syncDailyActivities($plant));
    }

    public function syncDailyActivities(Plant $plant): void
    {
        DB::transaction(function () use ($plant) {
            $activitiesByDate = $plant->activities
                ->keyBy(fn (PlantActivity $activity) => Carbon::parse($activity->planned_date)->toDateString());

            for ($day = 1; $day <= 92; $day++) {
                $step = $this->dailyScheduleForDay($day);
                $date = Carbon::parse($plant->start_date)->addDays($day);
                $dateKey = $date->toDateString();

                $activity = $activitiesByDate->get($dateKey);

                if ($activity) {
                    $activity->update([
                        'title' => $step['title'],
                        'description' => $step['description'],
                        'order_index' => $day,
                        'notes' => $this->mergeScheduleNotes($activity->notes, $step['notes']),
                        'system_notes' => $step['system_notes'],
                    ]);

                    continue;
                }

                $activity = PlantActivity::create([
                    'plant_id' => $plant->id,
                    'assigned_user_id' => null,
                    'title' => $step['title'],
                    'description' => $step['description'],
                    'planned_date' => $date,
                    'status' => 'belum_dikerjakan',
                    'order_index' => $day,
                    'notes' => $step['notes'],
                    'system_notes' => $step['system_notes'],
                ]);

                $this->createScheduleNotifications($plant, $activity, $step, $date);
            }

            $plant->update([
                'harvest_date' => Carbon::parse($plant->start_date)->addDays(92),
            ]);
        });
    }

    private function createScheduleNotifications(Plant $plant, PlantActivity $activity, array $step, Carbon $date): void
    {
        Notification::create([
            'plant_activity_id' => $activity->id,
            'channel' => 'system',
            'message' => $step['title'] . ' dijadwalkan untuk ' . $date->format('d/m/Y'),
            'scheduled_at' => $date,
            'recipient_role' => 'owner',
            'recipient_user_id' => $plant->owner_id,
        ]);

        Notification::create([
            'plant_activity_id' => $activity->id,
            'channel' => 'system',
            'message' => $step['title'] . ' dijadwalkan untuk ' . $date->format('d/m/Y') . ' (tanaman: ' . $plant->name . ')',
            'scheduled_at' => $date,
            'recipient_role' => 'penyuluh',
        ]);
    }

    private function mergeScheduleNotes(?string $currentNotes, string $scheduleNotes): string
    {
        if (blank($currentNotes)) {
            return $scheduleNotes;
        }

        if (str_starts_with($currentNotes, $scheduleNotes)) {
            return $currentNotes;
        }

        return $scheduleNotes . "\n\n" . $currentNotes;
    }

    private function dailyScheduleForDay(int $day): array
    {
        if ($day <= 14) {
            return $this->adaptationSchedule($day);
        }

        if ($day <= 30) {
            return $this->activeVegetativeSchedule($day);
        }

        if ($day <= 60) {
            return $this->floweringSchedule($day);
        }

        return $this->fruitingSchedule($day);
    }

    private function adaptationSchedule(int $day): array
    {
        $notes = 'Pagi (06.00-09.00): 300-500 ml air. Sore (16.00-18.00): 300-500 ml air. Kurangi jika media masih lembap, hentikan penyiraman sore jika turun hujan.';
        $title = "Hari {$day}: Penyiraman & Monitoring Rutin";

        if ($day === 1) {
            $title = 'Hari 1: Adaptasi Lingkungan Baru';
        } elseif ($day === 7) {
            $title = 'Hari 7: Penyiraman & Pemupukan Organik I';
            $notes .= ' Berikan POC 5-10 ml per liter air untuk merangsang pertumbuhan akar dan daun muda.';
        } elseif ($day === 14) {
            $title = 'Hari 14: Penyiraman & Pemupukan Organik II (Pemangkasan)';
            $notes .= ' Berikan POC 5-10 ml per liter air untuk mendukung daun baru. Segera pangkas daun yang menguning/rusak.';
        }

        return [
            'title' => $title,
            'description' => 'Fase Adaptasi & Vegetatif Awal',
            'notes' => $notes,
            'system_notes' => 'Fokus adaptasi tanaman, periksa gejala layu setelah pindah tanam, dan pastikan media tetap lembap.',
        ];
    }

    private function activeVegetativeSchedule(int $day): array
    {
        $notes = 'Pagi: 500-700 ml air. Sore: 500-700 ml air. Pastikan media tidak terlalu kering untuk mendukung pertumbuhan vegetatif.';
        $title = "Hari {$day}: Penyiraman & Monitoring Vegetatif";

        if ($day === 21) {
            $title = 'Hari 21: Penyiraman & Pemupukan Organik III (Top Dressing)';
            $notes .= ' Berikan POC 10 ml per liter air dan tambahkan kompos/pupuk kandang matang 100-150 gram per polybag.';
        } elseif ($day === 30) {
            $title = 'Hari 30: Penyiraman & Pemupukan NPK Vegetatif';
            $notes .= ' Berikan NPK 16-16-16 sebanyak 5 gram per polybag. Taburkan melingkar 5-10 cm dari batang, lalu SEGERA siram tanaman.';
        }

        return [
            'title' => $title,
            'description' => 'Fase Vegetatif Aktif',
            'notes' => $notes,
            'system_notes' => 'Amati pertumbuhan daun secara berkala, bersihkan gulma di sekitar polybag, dan pangkas daun yang menguning.',
        ];
    }

    private function floweringSchedule(int $day): array
    {
        $notes = 'Pagi: 500 ml air. Sore: 500 ml air (Total 1 liter per hari). Tanaman mulai membentuk cabang dan bunga, jangan biarkan kekeringan.';
        $title = "Hari {$day}: Penyiraman & Monitoring Fase Bunga";

        if ($day === 37) {
            $title = 'Hari 37: Penyiraman & Pemupukan Cabang Produktif';
            $notes .= ' Berikan POC 10 ml per liter air untuk mendukung pertumbuhan cabang produktif.';
        } elseif ($day === 45) {
            $title = 'Hari 45: Penyiraman & Pemupukan Fase Berbunga';
            $notes .= ' Berikan NPK 16-16-16 sebanyak 5-10 gram per polybag untuk merangsang pembentukan bunga dan mencegah kerontokan.';
        } elseif ($day === 60) {
            $title = 'Hari 60: Penyiraman & Pemupukan Pembuahan (NPK Tinggi Kalium)';
            $notes .= ' Berikan NPK tinggi kalium (NPK 12-12-17 atau NPK 13-6-27) sebanyak 5-10 gram per polybag untuk mendukung pembentukan buah muda.';
        }

        return [
            'title' => $title,
            'description' => 'Fase Percabangan & Pembungaan',
            'notes' => $notes,
            'system_notes' => $day <= 45
                ? 'Amati pembentukan cabang produktif baru. Segera pasang ajir atau tongkat penyangga jika tanaman tumbuh terlalu tinggi agar batang tidak roboh.'
                : 'Amati pembentukan bunga pertama. Lakukan pemeriksaan rutin terhadap potensi serangan hama dan penyakit.',
        ];
    }

    private function fruitingSchedule(int $day): array
    {
        $notes = 'Pagi: 1 Liter air. Sore: Tambahan 500 ml jika cuaca sangat terik (Total 1-1.5 liter per hari). Jangan sampai kekurangan air karena dapat menyebabkan bunga dan buah muda rontok.';
        $title = "Hari {$day}: Penyiraman & Monitoring Kematangan Buah";

        if ($day === 75) {
            $title = 'Hari 75: Penyiraman & Fase Awal Panen Cabai Hijau';
            $notes .= ' Pemupukan: Berikan NPK tinggi kalium 5-10 gram per polybag. Edukasi Panen: Cabai rawit hijau siap dipanen jika ukuran maksimal, warna hijau tua mengkilap, dan tekstur keras saat disentuh.';
        } elseif ($day === 85) {
            $title = 'Hari 85: Penyiraman, Pemupukan & Edukasi Panen Cabai Merah / Putih';
            $notes .= ' Pemupukan: Berikan POC 10 ml per liter air. Edukasi Panen: Cabai merah siap panen jika 90-100% permukaan merah cerah (kandungan capsaicin maksimal). Cabai putih siap panen jika warna putih krem/kekuningan merata.';
        } elseif ($day === 89) {
            $title = 'Hari 89: Penyiraman & Pemupukan Rutin Pemeliharaan';
            $notes .= ' Berikan NPK tinggi kalium 5-10 gram per polybag untuk menjaga produktivitas buah gelombang berikutnya.';
        } elseif ($day === 92) {
            $title = 'Hari 92: Evaluasi Produksi & Pemeliharaan Jangka Panjang';
            $notes .= ' Setelah panen pertama, pemanenan dapat terus dilakukan setiap 3-7 hari sekali. Lakukan pemupukan NPK tinggi kalium setiap 14 hari dan POC setiap 7 hari agar tanaman terus berbuah selama 6-12 bulan.';
        }

        return [
            'title' => $title,
            'description' => 'Fase Pembuahan & Panen Raya Kontinu',
            'notes' => $notes,
            'system_notes' => $day <= 75
                ? 'Amati perkembangan buah muda. Jaga stabilitas air untuk mencegah kerontokan buah.'
                : 'Periksa tingkat kematangan buah cabai rawit setiap hari di pohon.',
        ];
    }
}
