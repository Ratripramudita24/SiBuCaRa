<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jadwal Budidaya - SiBuCaRa</title>
    <style>
        body { font-family: Arial, sans-serif; color: #0d1b2a; margin: 32px; }
        .header { display: flex; justify-content: space-between; gap: 24px; border-bottom: 2px solid #00713d; padding-bottom: 16px; margin-bottom: 20px; }
        .brand { color: #00713d; font-weight: 800; letter-spacing: .08em; font-size: 12px; text-transform: uppercase; }
        h1 { margin: 6px 0 4px; font-size: 24px; }
        p { margin: 0; color: #52616f; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th { background: #ecfdf5; color: #064e3b; text-align: left; text-transform: uppercase; font-size: 11px; }
        th, td { border: 1px solid #dbe7df; padding: 10px; vertical-align: top; }
        .badge { border-radius: 999px; padding: 4px 8px; font-weight: 700; font-size: 10px; display: inline-block; }
        .belum_dikerjakan { background: #fef3c7; color: #92400e; }
        .sedang_dikerjakan { background: #dbeafe; color: #1d4ed8; }
        .selesai { background: #d1fae5; color: #047857; }
        .tidak_dilakukan { background: #fee2e2; color: #b91c1c; }
        .actions { margin-bottom: 20px; text-align: right; }
        button { background: #00713d; color: white; border: 0; border-radius: 10px; padding: 10px 14px; font-weight: 700; cursor: pointer; }
        @media print {
            body { margin: 18mm; }
            .actions { display: none; }
        }
    </style>
</head>
<body>
    <div class="actions">
        <button type="button" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div class="header">
        <div>
            <div class="brand">SiBuCaRa</div>
            <h1>Jadwal Budidaya</h1>
            <p>Daftar aktivitas terjadwal untuk tanaman cabai rawit.</p>
        </div>
        <div>
            <p>Dicetak: {{ now()->translatedFormat('d F Y, H:i') }}</p>
            <p>Owner: {{ auth()->user()->name }}</p>
            <p>Total jadwal: {{ $activities->count() }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Tanaman</th>
                <th>Aktivitas</th>
                <th>Assigned</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activities as $activity)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($activity->planned_date)->translatedFormat('d M Y') }}</td>
                    <td>
                        <strong>{{ $activity->plant->name ?? '-' }}</strong><br>
                        {{ $activity->plant->variety->name ?? 'Varietas cabai rawit' }}
                    </td>
                    <td>
                        <strong>{{ $activity->title }}</strong><br>
                        {{ $activity->description }}
                    </td>
                    <td>{{ $activity->assignedUser?->name ?? '-' }}</td>
                    <td><span class="badge {{ $activity->status }}">{{ str_replace('_', ' ', $activity->status) }}</span></td>
                    <td>{{ $activity->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #6b7280;">Belum ada jadwal atau aktivitas ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        window.addEventListener('load', () => window.print());
    </script>
</body>
</html>
