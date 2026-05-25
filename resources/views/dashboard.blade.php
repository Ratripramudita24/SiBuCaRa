<x-app-layout>
    <div class="py-6 px-8 space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-[#0d1b2a]">Dashboard Utama</h2>
            <p class="text-sm text-gray-500">Hari ini adalah {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}. Semua sistem berjalan optimal.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-4">
                <h3 class="text-lg font-bold text-[#006434]">Blok Lahan Aktif</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($plants as $plant)
                        <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm relative">
                            <span class="absolute top-5 right-5 bg-emerald-50 text-emerald-700 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider">Aktif</span>

                            <h4 class="text-base font-bold text-gray-900 mb-1">{{ $plant->name }}</h4>
                            <p class="text-xs text-gray-400 mb-4">Mulai Tanam: {{ \Carbon\Carbon::parse($plant->start_date)->translatedFormat('d M Y') }}</p>

                            <div class="space-y-1.5">
                                <div class="flex justify-between text-xs font-semibold text-gray-600">
                                    <span>Progress Pertumbuhan</span>
                                    <span class="text-[#00713d]">
                                        {{ $plant->total_tasks > 0 ? round(($plant->completed_tasks / $plant->total_tasks) * 100) : 0 }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-[#00713d] h-2 rounded-full transition-all duration-500" style="width: {{ $plant->total_tasks > 0 ? ($plant->completed_tasks / $plant->total_tasks) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 bg-white rounded-xl p-8 text-center text-gray-400 border border-dashed border-gray-200">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-sm font-medium">Belum ada blok lahan yang aktif.</p>
                            <p class="text-xs text-gray-400 mt-1">Silakan daftarkan penanaman baru terlebih dahulu di menu <a href="{{ route('plants.index') }}" class="text-[#00713d] underline font-semibold">Jadwal</a>.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm h-fit space-y-4">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#00713d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Jadwal Hari Ini
                </h3>

                <div class="space-y-3">
                    @forelse($todayActivities as $activity)
                        <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/60 flex justify-between items-start gap-4 hover:border-gray-200 transition">
                            <div class="space-y-1 flex-1">
                                <span class="text-[9px] uppercase font-extrabold tracking-wider text-[#00713d] bg-emerald-50 px-2 py-0.5 rounded">
                                    {{ $activity->plant->name }}
                                </span>
                                <h4 class="text-sm font-bold text-gray-900 pt-1">{{ $activity->title }}</h4>
                                <p class="text-xs text-gray-500 leading-relaxed">{{ $activity->description }}</p>
                            </div>

                            <form action="{{ route('activity.done', $activity->id) }}" method="POST" class="flex-shrink-0 pt-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" title="Tandai Selesai" class="w-6 h-6 rounded-md border border-gray-300 flex items-center justify-center bg-white hover:border-emerald-600 hover:bg-emerald-50 transition group">
                                    <svg class="w-4 h-4 text-transparent group-hover:text-emerald-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-10 text-xs text-gray-400 space-y-2">
                            <span class="text-2xl block">🎉</span>
                            <p class="font-medium text-gray-500">Semua beres!</p>
                            <p>Tidak ada tugas pemeliharaan budidaya yang tersisa untuk hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
