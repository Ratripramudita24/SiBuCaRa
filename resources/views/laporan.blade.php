<x-app-layout>
    <div class="py-8 px-8 space-y-6">
        @php
            $progress = $totalActivities > 0 ? round(($doneActivities / $totalActivities) * 100) : 0;
        @endphp

        <div class="rounded-2xl border border-emerald-100 bg-white/85 p-6 shadow-sm">
            <p class="text-xs font-black uppercase tracking-[0.2em] text-[#00713d]">Evaluasi</p>
            <h2 class="mt-2 text-3xl font-black text-[#0d1b2a]">Laporan Perkembangan</h2>
            <p class="mt-1 text-sm text-gray-500">Ringkasan aktivitas budidaya cabai rawit berdasarkan akses role Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-gray-500">Total Aktivitas</p>
                <h3 class="mt-3 text-3xl font-black text-gray-900">{{ $totalActivities }}</h3>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-emerald-600">Selesai</p>
                <h3 class="mt-3 text-3xl font-black text-emerald-600">{{ $doneActivities }}</h3>
            </div>
            <div class="rounded-2xl border border-amber-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-amber-600">Belum Selesai</p>
                <h3 class="mt-3 text-3xl font-black text-amber-600">{{ $pendingActivities }}</h3>
            </div>
            <div class="rounded-2xl border border-red-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-red-600">Tidak Dilakukan</p>
                <h3 class="mt-3 text-3xl font-black text-red-600">{{ $notDoneActivities }}</h3>
            </div>
        </div>

        <div class="rounded-2xl border border-emerald-100 bg-white p-6 shadow-sm">
            <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h3 class="text-lg font-black text-gray-900">Progres Aktivitas Selesai</h3>
                    <p class="text-sm text-gray-500">{{ $doneActivities }} dari {{ $totalActivities }} aktivitas sudah selesai.</p>
                </div>
                <span class="text-4xl font-black text-[#00713d]">{{ $progress }}%</span>
            </div>
            <div class="h-4 w-full overflow-hidden rounded-full bg-gray-100">
                <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-emerald-700" style="width: {{ $progress }}%"></div>
            </div>
        </div>

        <div class="rounded-2xl border border-emerald-100 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h3 class="text-lg font-black text-gray-900">Riwayat Aktivitas Selesai</h3>
                <p class="text-sm text-gray-500">Catatan penyelesaian dari worker atau owner.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-emerald-50/80 text-xs font-bold uppercase text-emerald-900">
                        <tr>
                            <th class="px-4 py-3">Tanggal Selesai</th>
                            <th class="px-4 py-3">Tanaman</th>
                            <th class="px-4 py-3">Worker</th>
                            <th class="px-4 py-3">Aktivitas</th>
                            <th class="px-4 py-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($historyActivities as $activity)
                            <tr class="hover:bg-emerald-50/40">
                                <td class="px-4 py-4 text-xs text-gray-500">{{ \Carbon\Carbon::parse($activity->done_at)->translatedFormat('d M Y, H:i') }}</td>
                                <td class="px-4 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $activity->plant->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $activity->plant->variety->name ?? 'Varietas cabai rawit' }}</div>
                                </td>
                                <td class="px-4 py-4 text-xs text-gray-500">{{ $activity->assignedUser->name ?? '-' }}</td>
                                <td class="px-4 py-4">
                                    <a href="{{ route('activities.show', $activity) }}" class="text-sm font-bold text-[#00713d] hover:underline">{{ $activity->title }}</a>
                                    <div class="text-xs text-gray-500">{{ $activity->description }}</div>
                                </td>
                                <td class="px-4 py-4 text-xs text-gray-500">{{ $activity->notes ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-xs text-gray-400">Belum ada aktivitas yang selesai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
