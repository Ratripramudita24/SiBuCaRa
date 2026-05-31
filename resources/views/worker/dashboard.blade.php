@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-blue-700">SiBuCaRa - Pekerja</h1>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Keluar
                </button>
            </form>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Messages -->
        @if ($message = session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ $message }}
        </div>
        @endif

        <!-- Info Box -->
        <div class="bg-blue-100 border border-blue-300 text-blue-800 p-4 rounded-lg mb-6">
            <p class="font-semibold">Atasan: {{ $plants->first()?->owner->name ?? 'N/A' }}</p>
        </div>

        <!-- Filter Tabs -->
        <div class="flex gap-2 mb-6 flex-wrap">
            <a href="?status=" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 font-semibold hover:bg-gray-300 transition">
                Semua
            </a>
            <a href="?status=belum%20dikerjakan" class="px-4 py-2 rounded-lg bg-yellow-100 text-yellow-800 font-semibold hover:bg-yellow-200 transition">
                Belum Dikerjakan
            </a>
            <a href="?status=sedang%20dikerjakan" class="px-4 py-2 rounded-lg bg-blue-100 text-blue-800 font-semibold hover:bg-blue-200 transition">
                Sedang Dikerjakan
            </a>
            <a href="?status=selesai" class="px-4 py-2 rounded-lg bg-green-100 text-green-800 font-semibold hover:bg-green-200 transition">
                Selesai
            </a>
        </div>

        <!-- Schedules -->
        <div class="space-y-4">
            @forelse ($schedules as $schedule)
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Tanaman</p>
                        <p class="text-lg font-bold text-gray-800">{{ $schedule->plant->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Kegiatan</p>
                        <p class="text-lg font-bold text-gray-800">{{ $schedule->activity_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Target Tanggal</p>
                        <p class="text-lg font-bold text-blue-600">{{ $schedule->target_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <p class="text-lg font-bold">
                            <span class="px-3 py-1 rounded-full text-white text-sm
                                @if ($schedule->status === 'belum dikerjakan') bg-yellow-500
                                @elseif ($schedule->status === 'sedang dikerjakan') bg-blue-500
                                @elseif ($schedule->status === 'selesai') bg-green-500
                                @else bg-red-500
                                @endif">
                                {{ $schedule->status }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <p class="font-semibold text-gray-800 mb-2">Panduan:</p>
                    <p class="text-gray-700">{{ $schedule->notes }}</p>
                </div>

                <!-- Alasan jika tidak dilakukan -->
                @if ($schedule->reason_not_done)
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-4">
                    <p class="font-semibold text-red-800 mb-1">Alasan Tidak Dilakukan:</p>
                    <p class="text-red-700">{{ $schedule->reason_not_done }}</p>
                </div>
                @endif

                <!-- Update Status Form -->
                <form method="POST" action="{{ route('schedules.update', $schedule) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Perbarui Status</label>
                        <select name="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            onchange="toggleReasonField(this.value)">
                            <option value="">Pilih Status</option>
                            <option value="belum dikerjakan" @selected($schedule->status === 'belum dikerjakan')>Belum Dikerjakan</option>
                            <option value="sedang dikerjakan" @selected($schedule->status === 'sedang dikerjakan')>Sedang Dikerjakan</option>
                            <option value="selesai" @selected($schedule->status === 'selesai')>Selesai</option>
                            <option value="tidak dilakukan" @selected($schedule->status === 'tidak dilakukan')>Tidak Dilakukan</option>
                        </select>
                    </div>

                    <!-- Conditional Reason Field -->
                    <div id="reason-field-{{ $schedule->id }}" style="display: {{ $schedule->status === 'tidak dilakukan' ? 'block' : 'none' }};">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Tidak Dilakukan</label>
                        <textarea name="reason_not_done" rows="3" placeholder="Jelaskan mengapa kegiatan tidak dilakukan..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            @required($schedule->status === 'tidak dilakukan')>{{ $schedule->reason_not_done }}</textarea>
                        @error('reason_not_done') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
                        Simpan Status
                    </button>
                </form>
            </div>
            @empty
            <div class="text-center py-12 bg-white rounded-xl">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-gray-500 text-lg">Tidak ada tugas untuk ditampilkan</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
function toggleReasonField(status) {
    const schedules = document.querySelectorAll('[id^="reason-field-"]');
    schedules.forEach(field => {
        const select = field.previousElementSibling.querySelector('select');
        if (status === 'tidak dilakukan') {
            field.style.display = 'block';
            field.querySelector('textarea').required = true;
        } else {
            field.style.display = 'none';
            field.querySelector('textarea').required = false;
        }
    });
}
</script>
@endsection
