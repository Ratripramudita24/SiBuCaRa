@extends('layouts.app')

@section('content')
    <div class="py-8 px-8 max-w-6xl mx-auto space-y-6">
        @php
            $total = $activities->count();
            $done = $activities->where('status', 'selesai')->count();
            $waiting = $activities->whereIn('status', ['belum_dikerjakan', 'sedang_dikerjakan'])->count();
            $assigned = $activities->whereNotNull('assigned_user_id')->count();
        @endphp

        <div class="rounded-2xl border border-emerald-100 bg-white/85 p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-[#00713d]">Agenda Budidaya</p>
                    <h2 class="mt-2 text-3xl font-black text-[#0d1b2a]">Jadwal Budidaya</h2>
                    <p class="mt-1 text-sm text-gray-500">Daftar aktivitas terjadwal untuk semua tanaman Anda.</p>
                </div>
                <a href="{{ route('plants.index') }}" class="rounded-xl bg-gray-100 px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-200">Kembali ke Tanaman</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-gray-500">Total Jadwal</p>
                <p class="mt-3 text-3xl font-black text-gray-900">{{ $total }}</p>
            </div>
            <div class="rounded-2xl border border-blue-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-blue-700">Sudah Ditugaskan</p>
                <p class="mt-3 text-3xl font-black text-blue-700">{{ $assigned }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-emerald-700">Selesai</p>
                <p class="mt-3 text-3xl font-black text-emerald-700">{{ $done }}</p>
            </div>
            <div class="rounded-2xl border border-amber-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-amber-700">Perlu Dikerjakan</p>
                <p class="mt-3 text-3xl font-black text-amber-700">{{ $waiting }}</p>
            </div>
        </div>

        <div class="rounded-2xl border border-emerald-100 bg-white p-6 shadow-sm">
            @error('assigned_user_id')
                <div class="mb-4 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                    {{ $message }}
                </div>
            @enderror

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-5">
                <form method="GET" action="{{ route('schedules.index') }}" class="flex flex-col sm:flex-row sm:items-center gap-2">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama tanaman atau aktivitas" class="w-full sm:w-72 rounded-xl border-gray-200 px-3 py-2 text-sm focus:border-[#00713d] focus:ring-[#00713d]">
                    <select name="status" class="rounded-xl border-gray-200 px-3 py-2 text-sm focus:border-[#00713d] focus:ring-[#00713d]">
                        <option value="">Semua Status</option>
                        <option value="belum_dikerjakan" @selected(request('status') === 'belum_dikerjakan')>Belum Dikerjakan</option>
                        <option value="sedang_dikerjakan" @selected(request('status') === 'sedang_dikerjakan')>Sedang Dikerjakan</option>
                        <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
                        <option value="tidak_dilakukan" @selected(request('status') === 'tidak_dilakukan')>Tidak Dilakukan</option>
                    </select>
                    <button type="submit" class="rounded-xl bg-[#00713d] px-4 py-2 text-sm font-bold text-white hover:bg-[#005c32]">Filter</button>
                </form>
                <a href="{{ route('schedules.print', request()->query()) }}" target="_blank" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-700">Export PDF</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-auto text-left text-sm">
                    <thead class="bg-emerald-50/80 text-xs uppercase text-emerald-900">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Tanaman</th>
                            <th class="px-4 py-3">Aktivitas</th>
                            <th class="px-4 py-3">Worker</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($activities as $act)
                            <tr class="hover:bg-emerald-50/40">
                                <td class="px-4 py-4 font-semibold text-gray-900">{{ \Carbon\Carbon::parse($act->planned_date)->translatedFormat('d M Y') }}</td>
                                <td class="px-4 py-4">{{ $act->plant->name ?? '-' }}</td>
                                <td class="px-4 py-4">
                                    <a href="{{ route('activities.show', $act) }}" class="font-bold text-[#00713d] hover:underline">{{ $act->title }}</a>
                                    <p class="mt-1 text-xs text-gray-500">{{ $act->plant->variety->name ?? 'Varietas cabai rawit' }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    @if($workers->isNotEmpty())
                                        <form method="POST" action="{{ route('activities.assignWorker', $act) }}" data-worker-assignment-form>
                                            @csrf
                                            @method('PATCH')
                                            <label for="assigned_user_id_{{ $act->id }}" class="sr-only">Worker penanggung jawab</label>
                                            <select
                                                id="assigned_user_id_{{ $act->id }}"
                                                name="assigned_user_id"
                                                data-worker-assignment-select
                                                class="w-44 rounded-xl border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 focus:border-[#00713d] focus:ring-[#00713d]"
                                            >
                                                <option value="">Belum ditugaskan</option>
                                                @foreach($workers as $worker)
                                                    <option value="{{ $worker->id }}" @selected($act->assigned_user_id === $worker->id)>
                                                        {{ $worker->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <p class="mt-1 text-[11px] font-semibold text-gray-400" data-worker-assignment-status>
                                                {{ $act->assignedUser?->name ? 'Tersimpan' : 'Menunggu penugasan' }}
                                            </p>
                                        </form>
                                    @else
                                        <a href="{{ route('workers.create') }}" class="text-xs font-bold text-[#00713d] hover:underline">Tambah worker</a>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if($act->status === 'belum_dikerjakan')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-700">Belum</span>
                                    @elseif($act->status === 'sedang_dikerjakan')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700">Sedang</span>
                                    @elseif($act->status === 'selesai')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">Selesai</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">Tidak Dilakukan</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-gray-500">{{ $act->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-gray-500">Belum ada jadwal atau aktivitas ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
