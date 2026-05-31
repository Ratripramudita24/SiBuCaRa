@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="{{ route('owner.dashboard') }}" class="text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-xl font-bold text-gray-800">{{ $plant->name }}</h1>
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
        <!-- Plant Info -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nama Tanaman</p>
                    <p class="text-lg font-bold text-gray-800">{{ $plant->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Varietas</p>
                    <p class="text-lg font-bold text-gray-800">{{ $plant->variety }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Tanam</p>
                    <p class="text-lg font-bold text-gray-800">{{ $plant->plant_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <p class="text-lg font-bold text-green-600">{{ ucfirst($plant->status) }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Schedules -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-6">Jadwal Kegiatan</h2>
                <div class="space-y-4">
                    @forelse ($plant->schedules as $schedule)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-bold text-gray-800">{{ $schedule->activity_name }}</h3>
                                <p class="text-sm text-gray-600">Target: {{ $schedule->target_date->format('d M Y') }}</p>
                                <p class="text-sm font-semibold mt-1">
                                    <span class="px-2 py-1 rounded-full text-white text-xs
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
                        <div class="bg-gray-50 p-3 rounded text-sm text-gray-700">
                            <p class="font-semibold mb-1">Catatan:</p>
                            <p>{{ $schedule->notes }}</p>
                        </div>
                        @if ($schedule->reason_not_done)
                        <div class="bg-red-50 p-3 rounded text-sm text-red-700 mt-2">
                            <p class="font-semibold mb-1">Alasan Tidak Dilakukan:</p>
                            <p>{{ $schedule->reason_not_done }}</p>
                        </div>
                        @endif
                    </div>
                    @empty
                    <p class="text-gray-500">Belum ada jadwal</p>
                    @endforelse
                </div>
            </div>

            <!-- Recommendations -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-6">Rekomendasi Penyuluh</h2>
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @forelse ($plant->recommendations as $rec)
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p class="text-xs text-gray-500 mb-1">{{ $rec->penyuluh->name }}</p>
                            <p class="text-sm text-gray-700">{{ $rec->recommendation_text }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $rec->created_at->format('d M Y H:i') }}</p>
                        </div>
                        @empty
                        <div class="text-center py-6">
                            <p class="text-gray-500 text-sm">Belum ada rekomendasi</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
