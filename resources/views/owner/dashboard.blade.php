@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-green-700">SiBuCaRa - Owner</h1>
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

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Form Tanaman Baru & Workers -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Form Tambah Tanaman -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Tambah Tanaman Baru</h2>
                    <form method="POST" action="{{ route('plants.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Tanaman</label>
                            <input type="text" name="name" placeholder="Nama tanaman..." required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Tanam</label>
                            <input type="date" name="plant_date" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('plant_date') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition">
                            Tambah Tanaman
                        </button>
                    </form>
                </div>

                <!-- Workers Management -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Kelola Pekerja</h2>
                    <form method="POST" action="{{ route('workers.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pekerja</label>
                            <input type="text" name="name" placeholder="Nama..." required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" placeholder="email@example.com" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password Awal</label>
                            <input type="password" name="password" placeholder="••••••••" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
                            Tambah Pekerja
                        </button>
                    </form>

                    <!-- Workers List -->
                    <div class="mt-6 border-t pt-4">
                        <h3 class="font-semibold text-gray-700 mb-3">Daftar Pekerja</h3>
                        @forelse ($workers as $worker)
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded mb-2">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $worker->name }}</p>
                                <p class="text-xs text-gray-600">{{ $worker->email }}</p>
                            </div>
                            <form method="POST" action="{{ route('workers.destroy', $worker) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus pekerja ini?')"
                                    class="text-red-600 hover:text-red-800 text-xs font-semibold">
                                    Hapus
                                </button>
                            </form>
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm">Belum ada pekerja</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Daftar Tanaman -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-6">Daftar Tanaman Anda</h2>

                    @forelse ($plants as $plant)
                    <div class="border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ $plant->name }}</h3>
                                <p class="text-sm text-gray-600">Varietas: {{ $plant->variety }}</p>
                                <p class="text-sm text-gray-600">Tanggal Tanam: {{ $plant->plant_date->format('d M Y') }}</p>
                                <p class="text-sm text-gray-600">Status: <span class="font-semibold text-green-600">{{ ucfirst($plant->status) }}</span></p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('plants.show', $plant) }}" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                    Detail
                                </a>
                                <form method="POST" action="{{ route('plants.destroy', $plant) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus tanaman ini?')"
                                        class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="bg-gray-100 rounded-lg p-3">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-semibold text-gray-700">Progress Tugas</span>
                                <span class="text-sm font-bold text-green-600">
                                    {{ $plant->schedules->where('status', 'selesai')->count() }}/{{ $plant->schedules->count() }}
                                </span>
                            </div>
                            @php
                                $completed = $plant->schedules->where('status', 'selesai')->count();
                                $total = $plant->schedules->count();
                                $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;
                            @endphp
                            <div class="w-full bg-gray-300 rounded-full h-3">
                                <div class="bg-green-500 h-3 rounded-full transition-all"
                                    style="width: {{ $percentage }}%"></div>
                            </div>
                            <p class="text-xs text-gray-600 mt-2">{{ $percentage }}% Selesai</p>
                        </div>

                        <!-- Jadwal Summary -->
                        <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
                            <div class="bg-yellow-50 p-2 rounded">
                                <span class="text-gray-600">Belum Dikerjakan: </span>
                                <span class="font-bold text-yellow-600">{{ $plant->schedules->where('status', 'belum dikerjakan')->count() }}</span>
                            </div>
                            <div class="bg-blue-50 p-2 rounded">
                                <span class="text-gray-600">Sedang Dikerjakan: </span>
                                <span class="font-bold text-blue-600">{{ $plant->schedules->where('status', 'sedang dikerjakan')->count() }}</span>
                            </div>
                            <div class="bg-red-50 p-2 rounded">
                                <span class="text-gray-600">Tidak Dilakukan: </span>
                                <span class="font-bold text-red-600">{{ $plant->schedules->where('status', 'tidak dilakukan')->count() }}</span>
                            </div>
                            <div class="bg-green-50 p-2 rounded">
                                <span class="text-gray-600">Selesai: </span>
                                <span class="font-bold text-green-600">{{ $plant->schedules->where('status', 'selesai')->count() }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0 0h6" />
                        </svg>
                        <p class="text-gray-500 text-lg">Belum ada tanaman. Silakan tambahkan tanaman baru!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
