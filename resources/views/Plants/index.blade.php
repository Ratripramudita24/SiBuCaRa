<x-app-layout>
    <div class="py-8 px-8 max-w-6xl mx-auto space-y-6">
        @php
            $plantCount = $plants->count();
            $activityCount = $plants->sum(fn ($plant) => $plant->activities->count());
            $doneCount = $plants->sum(fn ($plant) => $plant->activities->where('status', 'selesai')->count());
            $overallProgress = $activityCount > 0 ? round(($doneCount / $activityCount) * 100) : 0;
        @endphp

        <div class="rounded-2xl border border-emerald-100 bg-white/85 p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-[#00713d]">Data Tanaman</p>
                    <h2 class="mt-2 text-3xl font-black text-[#0d1b2a]">Daftar Tanaman</h2>
                    <p class="mt-1 text-sm text-gray-500">Kelola tanaman cabai rawit, lokasi lahan, dan progres jadwal budidaya.</p>
                </div>
                <a href="{{ route('plants.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#00713d] px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#005c32]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Tanaman
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-gray-500">Total Tanaman</p>
                <p class="mt-3 text-3xl font-black text-[#0d1b2a]">{{ $plantCount }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-gray-500">Total Aktivitas</p>
                <p class="mt-3 text-3xl font-black text-[#0d1b2a]">{{ $activityCount }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-[#00713d]">Progres Keseluruhan</p>
                <p class="mt-3 text-3xl font-black text-[#00713d]">{{ $overallProgress }}%</p>
            </div>
        </div>

        <div class="rounded-2xl border border-emerald-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-5">
                <input type="text" id="search" placeholder="Cari nama tanaman, lokasi..." class="w-full lg:w-72 rounded-xl border-gray-200 px-3 py-2 text-sm focus:border-[#00713d] focus:ring-[#00713d]" oninput="document.getElementById('searchForm').submit()" form="searchForm">

                <form id="searchForm" method="GET" action="{{ route('plants.index') }}" class="flex flex-col sm:flex-row sm:items-center gap-2">
                    <select name="variety" class="rounded-xl border-gray-200 px-3 py-2 text-sm focus:border-[#00713d] focus:ring-[#00713d]">
                        <option value="">Semua Varietas</option>
                        <option value="Cabai Rawit Hijau">Cabai Rawit Hijau</option>
                        <option value="Cabai Rawit Merah">Cabai Rawit Merah</option>
                        <option value="Cabai Rawit Putih">Cabai Rawit Putih</option>
                    </select>
                    <select name="status" class="rounded-xl border-gray-200 px-3 py-2 text-sm focus:border-[#00713d] focus:ring-[#00713d]">
                        <option value="">Semua Status</option>
                        <option value="belum_dikerjakan">Belum Dikerjakan</option>
                        <option value="sedang_dikerjakan">Sedang Dikerjakan</option>
                        <option value="selesai">Selesai</option>
                        <option value="tidak_dilakukan">Tidak Dilakukan</option>
                    </select>
                    <button type="submit" class="rounded-xl bg-[#00713d] px-4 py-2 text-sm font-bold text-white hover:bg-[#005c32]">Filter</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-auto text-left text-sm">
                    <thead class="bg-emerald-50/80 text-xs uppercase text-emerald-900">
                        <tr>
                            <th class="px-4 py-3">Nama Tanaman</th>
                            <th class="px-4 py-3">Varietas</th>
                            <th class="px-4 py-3">Tanggal Tanam</th>
                            <th class="px-4 py-3">Lokasi</th>
                            <th class="px-4 py-3">Status Budidaya</th>
                            <th class="px-4 py-3">Progress</th>
                            <th class="px-4 py-3 min-w-[260px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($plants as $plant)
                            @php
                                $completed = $plant->activities->where('status', 'selesai')->count();
                                $total = $plant->activities->count();
                                $progress = $total > 0 ? round(($completed / $total) * 100) : 0;
                                $status = $plant->activities->last()?->status ?? 'belum_dikerjakan';
                            @endphp
                            <tr class="hover:bg-emerald-50/40">
                                <td class="px-4 py-4 font-bold text-gray-900">{{ $plant->name }}</td>
                                <td class="px-4 py-4 text-gray-600">{{ $plant->variety->name ?? '-' }}</td>
                                <td class="px-4 py-4 text-gray-600">{{ \Carbon\Carbon::parse($plant->start_date)->translatedFormat('d M Y') }}</td>
                                <td class="px-4 py-4 text-gray-600">{{ $plant->location ?? '-' }}</td>
                                <td class="px-4 py-4">
                                    @if($status === 'belum_dikerjakan')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-700">Belum</span>
                                    @elseif($status === 'sedang_dikerjakan')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700">Sedang</span>
                                    @elseif($status === 'selesai')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">Selesai</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">Tidak</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 w-48">
                                    <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100">
                                        <div class="h-2 rounded-full bg-[#00713d]" style="width: {{ $progress }}%"></div>
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500">{{ $progress }}% - {{ $completed }}/{{ $total }}</div>
                                </td>
                                <td class="px-4 py-4 min-w-[260px]">
                                    <div class="flex flex-nowrap items-center gap-2">
                                        <a href="{{ route('plants.show', $plant) }}" class="inline-flex items-center gap-2 rounded-lg border border-blue-100 bg-white px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Detail
                                        </a>
                                        <a href="{{ route('plants.edit', $plant) }}" class="inline-flex items-center gap-2 rounded-lg border border-yellow-100 bg-white px-3 py-1.5 text-xs font-bold text-yellow-700 hover:bg-yellow-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z"/></svg>
                                            Edit
                                        </a>
                                        <button type="button" onclick="if(confirm('Yakin ingin menghapus tanaman \"{{ addslashes($plant->name) }}\"?')){ document.getElementById('delete-form-{{ $plant->id }}').submit(); }" class="inline-flex items-center gap-2 rounded-lg border border-red-100 bg-red-50 px-3 py-1.5 text-xs font-bold text-red-700 hover:bg-red-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Hapus
                                        </button>

                                        <form id="delete-form-{{ $plant->id }}" action="{{ route('plants.destroy', $plant) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                                    <p class="font-bold text-gray-700">Belum ada tanaman.</p>
                                    <a href="{{ route('plants.create') }}" class="mt-2 inline-flex font-bold text-[#00713d] hover:underline">Tambah tanaman pertama</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
