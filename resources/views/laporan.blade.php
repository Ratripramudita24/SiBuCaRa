<x-app-layout>
    <div class="py-6 px-8 space-y-6">
        <h2 class="text-2xl font-bold text-[#0d1b2a]">Riwayat Aktivitas</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm">
                <p class="text-xs font-medium text-gray-500 uppercase">Total Aktivitas</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalActivities }}</h3>
            </div>
            <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm">
                <p class="text-xs font-medium text-emerald-600 uppercase">Selesai Tepat Waktu</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-1">{{ $doneActivities }}</h3>
            </div>
            <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm">
                <p class="text-xs font-medium text-amber-600 uppercase">Dalam Proses Antrean</p>
                <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ $pendingActivities }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-700 uppercase">
                        <tr>
                            <th class="px-4 py-3">Tanggal Selesai</th>
                            <th class="px-4 py-3">Lahan</th>
                            <th class="px-4 py-3">Aktivitas</th>
                            <th class="px-4 py-3 text-center">Batal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($historyActivities as $activity)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-4 py-3 text-xs text-gray-500">{{ \Carbon\Carbon::parse($activity->done_at)->format('d M Y, H:i') }} WIB</td>
                                <td class="px-4 py-3 font-semibold text-gray-900 text-xs">{{ $activity->plant->name }}</td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                    <div class="text-xs text-gray-500">{{ $activity->description }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('activity.undo', $activity->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs text-gray-400 hover:text-amber-600 underline font-medium">Undo</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400 text-xs">Belum ada riwayat log yang diselesaikan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
