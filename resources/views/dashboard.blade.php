<x-app-layout>
    @php
        $role = auth()->user()->role ?? 'owner';
        $statusClass = [
            'belum_dikerjakan' => 'bg-amber-100 text-amber-700',
            'sedang_dikerjakan' => 'bg-blue-100 text-blue-700',
            'selesai' => 'bg-emerald-100 text-emerald-700',
            'tidak_dilakukan' => 'bg-red-100 text-red-700',
        ];
    @endphp

    <div class="py-8 px-8 space-y-6">
        <div class="rounded-2xl border border-emerald-100 bg-white/85 p-6 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-[#00713d]">SiBuCaRa</p>
                    <h2 class="mt-2 text-3xl font-black text-[#0d1b2a]">Dashboard {{ ucfirst($role) }}</h2>
                    <p class="mt-1 text-sm text-gray-500">Hari ini {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}. Ringkasan budidaya cabai rawit Anda.</p>
                </div>

                @if($role === 'owner')
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('plants.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#00713d] px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#005c32]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Tanaman
                        </a>
                        <a href="{{ route('workers.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-2.5 text-sm font-bold text-[#00713d] hover:bg-emerald-100">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                            Kelola Worker
                        </a>
                    </div>
                @endif
            </div>
        </div>

        @if($role === 'owner')
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
                <div class="rounded-2xl border border-indigo-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-black uppercase tracking-wide text-gray-500">Total Aktivitas</p>
                        <span class="rounded-lg bg-indigo-50 p-2 text-indigo-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h7"/></svg>
                        </span>
                    </div>
                    <p class="mt-4 text-3xl font-black text-gray-900">{{ $totalActivities ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500">Semua aktivitas otomatis dari tanaman.</p>
                </div>
                <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-black uppercase tracking-wide text-emerald-700">Selesai</p>
                        <span class="rounded-lg bg-emerald-50 p-2 text-emerald-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                    </div>
                    <p class="mt-4 text-3xl font-black text-emerald-700">{{ $completedActivities ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500">Aktivitas yang sudah tuntas.</p>
                </div>
                <div class="rounded-2xl border border-amber-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-black uppercase tracking-wide text-amber-700">Belum Selesai</p>
                        <span class="rounded-lg bg-amber-50 p-2 text-amber-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                    </div>
                    <p class="mt-4 text-3xl font-black text-amber-700">{{ $pendingActivities ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500">Menunggu atau sedang dikerjakan.</p>
                </div>
                <div class="rounded-2xl border border-blue-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-black uppercase tracking-wide text-blue-700">Sedang Dikerjakan</p>
                        <span class="rounded-lg bg-blue-50 p-2 text-blue-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </span>
                    </div>
                    <p class="mt-4 text-3xl font-black text-blue-700">{{ $inProgressActivities ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500">Aktivitas aktif di lapangan.</p>
                </div>
                <div class="rounded-2xl border border-red-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-black uppercase tracking-wide text-red-700">Tidak Dilakukan</p>
                        <span class="rounded-lg bg-red-50 p-2 text-red-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </span>
                    </div>
                    <p class="mt-4 text-3xl font-black text-red-700">{{ $notDoneActivities ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-500">Butuh evaluasi atau catatan.</p>
                </div>
            </div>

            <div class="rounded-2xl border border-emerald-100 bg-white p-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                    <div>
                        <h3 class="text-lg font-black text-gray-900">Progres Budidaya Keseluruhan</h3>
                        <p class="text-sm text-gray-500">Persentase dihitung dari aktivitas yang sudah selesai.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-4xl font-black text-[#00713d]">{{ $progressPercentage ?? 0 }}%</span>
                        <div class="text-sm text-gray-500">{{ $completedActivities ?? 0 }} selesai dari {{ $totalActivities ?? 0 }}</div>
                    </div>
                </div>
                <div class="h-4 w-full overflow-hidden rounded-full bg-gray-100">
                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-emerald-700" style="width: {{ $progressPercentage ?? 0 }}%"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 rounded-2xl border border-emerald-100 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-emerald-50 flex items-center justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-black text-gray-900">Tanaman Cabai Rawit</h3>
                            <p class="text-sm text-gray-500">Pantau setiap tanaman beserta progres jadwal otomatisnya.</p>
                        </div>
                        <a href="{{ route('plants.index') }}" class="rounded-xl bg-emerald-50 px-4 py-2 text-sm font-bold text-[#00713d] hover:bg-emerald-100">Kelola</a>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse($plants as $plant)
                            @php
                                $total = $plant->activities->count();
                                $done = $plant->activities->where('status', 'selesai')->count();
                                $progress = $total > 0 ? round(($done / $total) * 100) : 0;
                            @endphp
                            <div class="p-5 hover:bg-emerald-50/40">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <h4 class="text-base font-black text-gray-900">{{ $plant->name }}</h4>
                                        <p class="mt-1 text-sm text-gray-500">{{ $plant->variety->name ?? 'Varietas belum dipilih' }} | Tanam {{ \Carbon\Carbon::parse($plant->start_date)->translatedFormat('d M Y') }} | {{ $plant->location ?? 'Lokasi belum diisi' }}</p>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('plants.show', $plant) }}" class="rounded-lg border border-emerald-100 bg-white px-3 py-1.5 text-xs font-bold text-[#00713d] hover:bg-emerald-50">Jadwal</a>
                                        <a href="{{ route('plants.edit', $plant) }}" class="rounded-lg border border-blue-100 bg-white px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-50">Edit</a>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center gap-3">
                                    <div class="h-2 flex-1 overflow-hidden rounded-full bg-gray-100">
                                        <div class="h-full rounded-full bg-[#00713d]" style="width: {{ $progress }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-[#00713d]">{{ $progress }}%</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center">
                                <p class="font-semibold text-gray-700">Belum ada tanaman.</p>
                                <p class="mt-1 text-sm text-gray-500">Tambahkan tanaman pertama agar jadwal budidaya otomatis dibuat.</p>
                                <a href="{{ route('plants.create') }}" class="mt-4 inline-flex rounded-xl bg-[#00713d] px-4 py-2 text-sm font-bold text-white hover:bg-[#005c32]">Tambah Tanaman</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-2xl border border-amber-100 bg-white p-5 shadow-sm">
                        <h3 class="text-lg font-black text-gray-900">Aktivitas Perlu Dikerjakan</h3>
                        <p class="mt-1 text-sm text-gray-500">Prioritas dari jadwal yang jatuh tempo.</p>
                        <div class="mt-4 space-y-3">
                            @forelse(($todayActivities ?? collect())->take(6) as $activity)
                                <a href="{{ route('activities.show', $activity) }}" class="block text-left w-full rounded-xl border border-gray-100 bg-gray-50 p-3 hover:bg-amber-50">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-sm font-semibold text-gray-900">{{ $activity->title }}</p>
                                        <span class="rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $statusClass[$activity->status] ?? 'bg-gray-100 text-gray-700' }}">{{ str_replace('_', ' ', $activity->status) }}</span>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">{{ $activity->plant->name }} | {{ \Carbon\Carbon::parse($activity->planned_date)->translatedFormat('d M Y') }}</p>
                                </a>
                            @empty
                                <div class="rounded-xl border border-dashed border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">Tidak ada aktivitas tertunda hari ini.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                        <h3 class="text-lg font-black text-gray-900">Ringkasan Akun</h3>
                        <p class="mt-1 text-sm text-gray-500">Jumlah aset yang Anda kelola.</p>
                        <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                            <a href="{{ route('workers.index') }}" class="rounded-xl bg-gray-50 p-4 hover:bg-emerald-50">
                                <span class="block text-xs text-gray-500">Worker</span>
                                <span class="text-2xl font-black text-gray-900">{{ $workers->count() ?? 0 }}</span>
                            </a>
                            <a href="{{ route('plants.index') }}" class="rounded-xl bg-gray-50 p-4 hover:bg-emerald-50">
                                <span class="block text-xs text-gray-500">Tanaman</span>
                                <span class="text-2xl font-black text-gray-900">{{ $plants->count() ?? 0 }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($role === 'worker')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-bold text-gray-900">Tugas Saya</h3>
                        <p class="text-sm text-gray-500">Ubah status pekerjaan dan tambahkan catatan pelaksanaan.</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                            @forelse($activities as $activity)
                            <button type="button" onclick="location.href='{{ route('activities.show', $activity) }}'" class="block text-left w-full p-5 hover:bg-gray-50">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $activity->title }}</p>
                                        <p class="text-sm text-gray-500">{{ $activity->plant->name }} - {{ $activity->planned_date->translatedFormat('d M Y') }}</p>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass[$activity->status] ?? 'bg-gray-100 text-gray-700' }}">{{ str_replace('_', ' ', $activity->status) }}</span>
                                </div>
                            </button>
                        @empty
                            <div class="p-8 text-center text-sm text-gray-500">Tidak ada tugas yang diberikan.</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm h-fit">
                    <h3 class="font-bold text-gray-900">Ringkasan</h3>
                    <div class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between"><span>Total Tugas</span><strong>{{ $totalAssigned ?? 0 }}</strong></div>
                        <div class="flex justify-between text-emerald-700"><span>Selesai</span><strong>{{ $completedCount ?? 0 }}</strong></div>
                        <div class="flex justify-between text-amber-700"><span>Menunggu</span><strong>{{ $pendingCount ?? 0 }}</strong></div>
                    </div>
                </div>
            </div>
        @elseif($role === 'penyuluh')
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
                <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase text-gray-500">Tanaman</p>
                    <p class="mt-2 text-3xl font-bold">{{ $totalPlants ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase text-gray-500">Aktivitas</p>
                    <p class="mt-2 text-3xl font-bold">{{ $activityStats['total'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase text-emerald-600">Selesai</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-700">{{ $activityStats['completed'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase text-amber-600">Terlambat/Perlu Cek</p>
                    <p class="mt-2 text-3xl font-bold text-amber-700">{{ ($todayActivities ?? collect())->count() }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase text-red-600">Tidak Dilakukan</p>
                    <p class="mt-2 text-3xl font-bold text-red-700">{{ $activityStats['not_done'] ?? 0 }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-bold text-gray-900">Aktivitas Perlu Perhatian</h3>
                        <p class="text-sm text-gray-500">Aktivitas jatuh tempo yang belum selesai.</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse(($todayActivities ?? collect())->take(8) as $activity)
                            <a href="{{ route('activities.show', $activity) }}" class="block p-5 hover:bg-gray-50">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $activity->title }}</p>
                                        <p class="text-sm text-gray-500">{{ $activity->plant->name }} - {{ \Carbon\Carbon::parse($activity->planned_date)->translatedFormat('d M Y') }}</p>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass[$activity->status] ?? 'bg-gray-100 text-gray-700' }}">{{ str_replace('_', ' ', $activity->status) }}</span>
                                </div>
                            </a>
                        @empty
                            <div class="p-8 text-center text-sm text-gray-500">Tidak ada aktivitas yang perlu perhatian.</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-bold text-gray-900">Catatan Worker & Rekomendasi</h3>
                        <p class="text-sm text-gray-500">Gunakan catatan untuk memberi saran budidaya.</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse(($notDoneActivities ?? collect()) as $activity)
                            <div class="p-5">
                                <p class="font-semibold text-gray-900">{{ $activity->title }} - {{ $activity->plant->name }}</p>
                                <p class="mt-1 text-sm text-gray-600">Catatan: {{ $activity->notes ?: 'Belum ada catatan.' }}</p>
                                <p class="mt-2 text-xs font-semibold text-[#00713d]">Rekomendasi: pantau kelembapan tanah dan sesuaikan jadwal lanjutan dengan kondisi lahan.</p>
                            </div>
                        @empty
                            <div class="p-8 text-center text-sm text-gray-500">Belum ada aktivitas berstatus tidak dilakukan.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
