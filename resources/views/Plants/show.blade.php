<x-app-layout>
    <div class="py-6 px-8 max-w-5xl mx-auto space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-[#0d1b2a]">Detail Tanaman</h2>
                <p class="text-sm text-gray-500">Status jadwal, aktivitas, dan progres budidaya cabai rawit.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('plants.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&lt;- Kembali ke Jadwal</a>
                @if(auth()->user()->role === 'owner' && $plant->owner_id === auth()->id())
                    <a href="{{ route('plants.edit', $plant) }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">Edit Tanaman</a>
                @endif
            </div>
        </div>

        @php
            $total = $plant->activities->count();
            $done = $plant->activities->where('status', 'selesai')->count();
            $notDone = $plant->activities->where('status', 'tidak_dilakukan')->count();
            $pending = $plant->activities->whereIn('status', ['belum_dikerjakan', 'sedang_dikerjakan'])->count();
            $progress = $total > 0 ? round(($done / $total) * 100) : 0;
            $statusClass = [
                'belum_dikerjakan' => 'bg-amber-100 text-amber-700',
                'sedang_dikerjakan' => 'bg-blue-100 text-blue-700',
                'selesai' => 'bg-emerald-100 text-emerald-700',
                'tidak_dilakukan' => 'bg-red-100 text-red-700',
            ];
        @endphp

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="space-y-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $plant->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $plant->variety->name ?? 'Varietas cabai rawit' }}</p>
                    </div>
                    <div class="rounded-xl bg-emerald-50 p-5 border border-emerald-100">
                        <div class="text-xs text-gray-500 uppercase tracking-wider">Progres Budidaya</div>
                        <div class="mt-3 text-3xl font-bold text-emerald-700">{{ $progress }}%</div>
                    </div>
                </div>
                <div class="space-y-3 p-5 bg-gray-50 rounded-xl">
                    <div class="text-sm text-gray-500">Tanggal Tanam</div>
                    <div class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($plant->start_date)->translatedFormat('d M Y') }}</div>
                    <div class="text-sm text-gray-500">Lokasi</div>
                    <div class="text-lg font-semibold text-gray-900">{{ $plant->location ?? 'Belum diisi' }}</div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-xl bg-gray-50 p-4">
                        <p class="text-xs text-gray-500">Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $total }}</p>
                    </div>
                    <div class="rounded-xl bg-emerald-50 p-4">
                        <p class="text-xs text-emerald-700">Selesai</p>
                        <p class="text-2xl font-bold text-emerald-700">{{ $done }}</p>
                    </div>
                    <div class="rounded-xl bg-amber-50 p-4">
                        <p class="text-xs text-amber-700">Belum Selesai</p>
                        <p class="text-2xl font-bold text-amber-700">{{ $pending }}</p>
                    </div>
                    <div class="rounded-xl bg-red-50 p-4">
                        <p class="text-xs text-red-700">Tidak Dilakukan</p>
                        <p class="text-2xl font-bold text-red-700">{{ $notDone }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900">Jadwal Aktivitas Otomatis</h3>
                    <p class="text-sm text-gray-500">Sistem menghasilkan jadwal berdasarkan tanggal tanam.</p>
                </div>
                <span class="text-xs text-gray-500">Total aktivitas: {{ $total }}</span>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($plant->activities as $activity)
                    <div class="px-6 py-4 grid grid-cols-1 lg:grid-cols-[180px_1fr_auto] gap-4 lg:items-center">
                        <div>
                            <div class="text-sm font-semibold text-gray-900">{{ $activity->title }}</div>
                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($activity->planned_date)->translatedFormat('d M Y') }}</div>
                        </div>
                        <div class="text-sm text-gray-600">{{ $activity->system_notes }}</div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass[$activity->status] ?? 'bg-gray-100 text-gray-700' }}">{{ str_replace('_', ' ', $activity->status) }}</span>
                            <a href="{{ route('activities.show', $activity) }}" class="text-sm text-[#00713d] font-semibold hover:underline">Detail</a>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">Belum ada aktivitas.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
