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
        $plant = Plant::create($data);

        $this->generateActivities($plant);

        return $plant;
    }

    private function generateActivities($plant)
    {
        // Get the first worker from the same owner (for activity assignment)
        $owner = $plant->owner;
        $worker = $owner->workers()->first();

        $order = 0;

        foreach ($this->scheduleSteps() as $step) {
            $date = Carbon::parse($plant->start_date)
                ->addDays($step['day']);

            // Tentukan assigned user berdasarkan role
            $assignedUserId = null;
            if ($step['role'] === 'owner') {
                $assignedUserId = $plant->owner_id;
            } elseif ($step['role'] === 'worker' && $worker) {
                $assignedUserId = $worker->id;
            }

            $activity = PlantActivity::create([
                'plant_id' => $plant->id,
                'assigned_user_id' => $assignedUserId,
                'title' => $step['title'],
                'description' => $step['desc'],
                'planned_date' => $date,
                'status' => 'belum_dikerjakan',
                'order_index' => $order++,
                'system_notes' => $step['system_notes']
            ]);

            // Buat notifikasi untuk worker dan penyuluh
            if ($worker) {
                Notification::create([
                    'plant_activity_id' => $activity->id,
                    'channel' => 'whatsapp',
                    'message' => $step['title'] . ' harus dilakukan pada ' . $date->format('d/m/Y'),
                    'scheduled_at' => $date,
                    'recipient_role' => 'worker',
                    'recipient_user_id' => $worker->id,
                ]);
            }

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
            'harvest_date' => Carbon::parse($plant->start_date)->addDays(90)
        ]);
    }

    public function regenerateActivities(Plant $plant): void
    {
        DB::transaction(function () use ($plant) {
            $plant->activities()->delete();
            $this->generateActivities($plant->fresh('owner'));
        });
    }

    private function scheduleSteps(): array
    {
        return [
            [
                'title' => 'Penyiraman Awal',
                'desc' => 'Penyiraman awal setelah penanaman bibit cabai rawit.',
                'system_notes' => 'Pastikan media tanam lembab merata, tidak tergenang.',
                'day' => 1,
                'role' => 'worker',
            ],
            [
                'title' => 'Penyiraman Rutin Minggu 1',
                'desc' => 'Penyiraman rutin untuk menjaga kelembaban tanah pada fase adaptasi.',
                'system_notes' => 'Cek kondisi tanah pagi atau sore, sesuaikan dengan cuaca.',
                'day' => 7,
                'role' => 'worker',
            ],
            [
                'title' => 'Pemupukan Pertama',
                'desc' => 'Pemberian pupuk tahap pertama untuk mendukung pertumbuhan vegetatif.',
                'system_notes' => 'Gunakan dosis sesuai kebutuhan lahan dan hindari pupuk mengenai batang langsung.',
                'day' => 14,
                'role' => 'worker',
            ],
            [
                'title' => 'Penyiraman dan Penyiangan',
                'desc' => 'Penyiraman lanjutan serta pembersihan gulma di sekitar tanaman.',
                'system_notes' => 'Buang gulma agar nutrisi tidak bersaing dengan tanaman cabai.',
                'day' => 21,
                'role' => 'worker',
            ],
            [
                'title' => 'Pengendalian Hama dan Penyakit',
                'desc' => 'Pemeriksaan daun, batang, dan area bedengan dari tanda hama atau penyakit.',
                'system_notes' => 'Catat gejala serangan dan lakukan tindakan pengendalian bila diperlukan.',
                'day' => 30,
                'role' => 'worker',
            ],
            [
                'title' => 'Pemupukan Susulan',
                'desc' => 'Pemupukan tahap kedua untuk mendukung pembungaan dan pembentukan buah.',
                'system_notes' => 'Perhatikan kondisi tanaman sebelum pemupukan susulan.',
                'day' => 45,
                'role' => 'worker',
            ],
            [
                'title' => 'Monitoring Pembungaan dan Buah',
                'desc' => 'Pemantauan bunga dan bakal buah cabai rawit.',
                'system_notes' => 'Catat jumlah bunga, kondisi buah, dan gejala kerontokan.',
                'day' => 60,
                'role' => 'worker',
            ],
            [
                'title' => 'Persiapan Panen',
                'desc' => 'Pemeriksaan tingkat kematangan buah dan persiapan alat panen.',
                'system_notes' => 'Siapkan wadah panen dan pilih buah sesuai standar kematangan.',
                'day' => 75,
                'role' => 'worker',
            ],
            [
                'title' => 'Estimasi Panen',
                'desc' => 'Estimasi waktu panen cabai rawit berdasarkan tanggal tanam.',
                'system_notes' => 'Panen dilakukan pada buah yang matang dan sehat.',
                'day' => 90,
                'role' => 'worker',
            ],
        ];
    }
}
