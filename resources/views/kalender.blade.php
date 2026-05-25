<x-app-layout>
    <div class="py-6 px-8 space-y-6">
        <h2 class="text-2xl font-bold text-[#0d1b2a]">Kalender & Timeline Produksi</h2>
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-8">
            @forelse($activities->groupBy(function($item) { return \Carbon\Carbon::parse($item->planned_date)->translatedFormat('F Y'); }) as $bulan => $daftarTugas)
                <div>
                    <h3 class="text-sm font-bold text-gray-800 border-b pb-2 mb-4">{{ $bulan }}</h3>
                    <div class="relative border-l-2 border-gray-100 ml-3 space-y-6">
                        @foreach($daftarTugas as $task)
                            <div class="relative pl-6">
                                <div class="absolute -left-[7px] top-1.5 w-3 h-3 rounded-full {{ $task->is_done ? 'bg-emerald-600' : 'bg-gray-300' }} border-2 border-white shadow-sm"></div>
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 p-3 bg-gray-50 rounded-xl border border-gray-100">
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-gray-400 block">{{ $task->plant->name }}</span>
                                        <h4 class="text-sm font-bold text-gray-900 {{ $task->is_done ? 'line-through text-gray-400' : '' }}">{{ $task->title }}</h4>
                                        <p class="text-xs text-gray-500">{{ $task->description }}</p>
                                    </div>
                                    <div>
                                        <span class="text-xs font-semibold text-gray-600 bg-white px-3 py-1 rounded-md border border-gray-200 block">
                                            📅 {{ \Carbon\Carbon::parse($task->planned_date)->translatedFormat('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-sm text-gray-400">Belum ada agenda jadwal budidaya.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>
