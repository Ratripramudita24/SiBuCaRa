@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-pink-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-purple-700">SiBuCaRa - Penyuluh</h1>
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

        <!-- Info -->
        <div class="bg-purple-100 border border-purple-300 text-purple-800 p-4 rounded-lg mb-6">
            <p class="font-semibold">Total Kebun: {{ $plants->count() }}</p>
        </div>

        <!-- Plants Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($plants as $plant)
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition overflow-hidden cursor-pointer group"
                onclick="toggleModal({{ $plant->id }})">
                <div class="bg-gradient-to-r from-green-400 to-emerald-500 h-24"></div>

                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">{{ $plant->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $plant->owner->name }}</p>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">
                            {{ ucfirst($plant->status) }}
                        </span>
                    </div>

                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <p>📅 Tanam: {{ $plant->plant_date->format('d M Y') }}</p>
                        <p>🌱 Varietas: {{ $plant->variety }}</p>
                    </div>

                    <!-- Progress -->
                    @php
                        $completed = $plant->schedules->where('status', 'selesai')->count();
                        $total = $plant->schedules->count();
                        $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;
                    @endphp
                    <div class="mb-4">
                        <div class="flex justify-between items-center text-xs mb-1">
                            <span class="font-semibold text-gray-700">Progress</span>
                            <span class="font-bold text-purple-600">{{ $percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>

                    <button onclick="toggleModal(event, {{ $plant->id }})"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 rounded-lg transition">
                        Lihat Detail
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12 bg-white rounded-xl">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4" />
                </svg>
                <p class="text-gray-500 text-lg">Belum ada kebun yang terdaftar</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Detail Modals -->
    @foreach ($plants as $plant)
    <div id="modal-{{ $plant->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="min-h-screen flex items-start justify-center pt-20 px-4">
            <div class="bg-white rounded-xl shadow-lg max-w-4xl w-full max-h-[80vh] overflow-y-auto">
                <!-- Header -->
                <div class="sticky top-0 bg-gradient-to-r from-purple-600 to-pink-600 text-white p-6 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold">{{ $plant->name }}</h2>
                        <p class="text-sm text-purple-100">Pemilik: {{ $plant->owner->name }}</p>
                    </div>
                    <button onclick="toggleModal(event, {{ $plant->id }})"
                        class="text-white hover:text-gray-200 text-2xl">×</button>
                </div>

                <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left: Info & Schedules -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-bold text-gray-800 mb-3">Informasi Tanaman</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-600">Varietas</p>
                                    <p class="font-semibold text-gray-800">{{ $plant->variety }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Status</p>
                                    <p class="font-semibold text-green-600">{{ ucfirst($plant->status) }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Tanggal Tanam</p>
                                    <p class="font-semibold text-gray-800">{{ $plant->plant_date->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Hari ke-</p>
                                    <p class="font-semibold text-gray-800">{{ now()->diffInDays($plant->plant_date) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Schedules -->
                        <div>
                            <h3 class="font-bold text-gray-800 mb-3">Jadwal Kegiatan</h3>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                @forelse ($plant->schedules as $schedule)
                                <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
                                    <div class="flex justify-between items-start mb-1">
                                        <p class="font-semibold text-gray-800">{{ $schedule->activity_name }}</p>
                                        <span class="px-2 py-0.5 rounded text-white text-xs font-semibold
                                            @if ($schedule->status === 'belum dikerjakan') bg-yellow-500
                                            @elseif ($schedule->status === 'sedang dikerjakan') bg-blue-500
                                            @elseif ($schedule->status === 'selesai') bg-green-500
                                            @else bg-red-500
                                            @endif">
                                            {{ $schedule->status }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-600">{{ $schedule->target_date->format('d M Y') }}</p>
                                    @if ($schedule->reason_not_done)
                                    <p class="text-xs text-red-600 mt-1">⚠ Alasan: {{ $schedule->reason_not_done }}</p>
                                    @endif
                                </div>
                                @empty
                                <p class="text-gray-500">Tidak ada jadwal</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Right: Recommendations Form & List -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Form Rekomendasi -->
                        <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                            <h3 class="font-bold text-gray-800 mb-3">Kirim Rekomendasi</h3>
                            <form method="POST" action="{{ route('recommendations.store', $plant) }}" class="space-y-3">
                                @csrf
                                <textarea name="recommendation_text" rows="4" placeholder="Tulis rekomendasi budidaya..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm"
                                    required></textarea>
                                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 rounded-lg transition text-sm">
                                    Kirim
                                </button>
                            </form>
                        </div>

                        <!-- Recommendations List -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-bold text-gray-800 mb-3">Rekomendasi Terbaru</h3>
                            <div class="space-y-3 max-h-64 overflow-y-auto">
                                @forelse ($plant->recommendations as $rec)
                                <div class="border-l-4 border-purple-500 pl-3 py-2 bg-white rounded">
                                    <p class="text-xs text-gray-500 font-semibold">{{ $rec->penyuluh->name }}</p>
                                    <p class="text-sm text-gray-700 mt-1">{{ $rec->recommendation_text }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $rec->created_at->format('d M Y H:i') }}</p>
                                </div>
                                @empty
                                <p class="text-gray-500 text-sm">Belum ada rekomendasi</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<script>
function toggleModal(event, plantId) {
    if (event && event.stopPropagation) {
        event.stopPropagation();
    }
    const modal = document.getElementById(`modal-${plantId}`);
    modal.classList.toggle('hidden');
}

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('fixed')) {
        event.target.classList.add('hidden');
    }
});
</script>
@endsection
