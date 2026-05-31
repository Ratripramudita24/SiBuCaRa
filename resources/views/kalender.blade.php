<x-app-layout>
    <div class="py-8 px-8 space-y-6">
        @php
            $total = $activities->count();
            $done = $activities->where('status', 'selesai')->count();
            $nextActivity = $activities->first();
        @endphp

        <div class="rounded-2xl border border-emerald-100 bg-white/85 p-6 shadow-sm">
            <p class="text-xs font-black uppercase tracking-[0.2em] text-[#00713d]">Timeline</p>
            <h2 class="mt-2 text-3xl font-black text-[#0d1b2a]">Kalender Budidaya</h2>
            <p class="mt-1 text-sm text-gray-500">Timeline aktivitas yang dibuat otomatis dari tanggal tanam.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-gray-500">Total Agenda</p>
                <p class="mt-3 text-3xl font-black text-gray-900">{{ $total }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-emerald-700">Selesai</p>
                <p class="mt-3 text-3xl font-black text-emerald-700">{{ $done }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-gray-500">Agenda Terdekat</p>
                <p class="mt-3 text-lg font-black text-gray-900">{{ $nextActivity ? \Carbon\Carbon::parse($nextActivity->planned_date)->translatedFormat('d M Y') : '-' }}</p>
            </div>
        </div>

        <div class="rounded-2xl border border-emerald-100 bg-white p-6 shadow-sm space-y-8">
            @forelse($activities->groupBy(fn ($item) => \Carbon\Carbon::parse($item->planned_date)->translatedFormat('F Y')) as $month => $tasks)
                <div>
                    <h3 class="mb-4 border-b border-emerald-100 pb-3 text-sm font-black text-emerald-950">{{ $month }}</h3>
                    <div class="relative ml-3 space-y-5 border-l-2 border-emerald-100">
                        @foreach($tasks as $task)
                            <div class="relative pl-6">
                                <div class="absolute -left-[7px] top-4 h-3 w-3 rounded-full {{ $task->status === 'selesai' ? 'bg-emerald-600' : ($task->status === 'tidak_dilakukan' ? 'bg-red-500' : ($task->status === 'sedang_dikerjakan' ? 'bg-blue-500' : 'bg-amber-400')) }} border-2 border-white shadow-sm"></div>
                                <a href="{{ route('activities.show', $task) }}" class="block rounded-2xl border border-gray-100 bg-gray-50 p-4 hover:bg-emerald-50">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                        <div>
                                            <span class="block text-[10px] font-black uppercase tracking-wide text-gray-400">{{ $task->plant->name }}</span>
                                            <h4 class="mt-1 text-sm font-black text-gray-900">{{ $task->title }}</h4>
                                            <p class="mt-1 text-xs text-gray-500">{{ $task->description }}</p>
                                        </div>
                                        <div class="flex flex-wrap md:justify-end gap-2">
                                            <span class="block rounded-lg border border-gray-200 bg-white px-3 py-1 text-xs font-bold text-gray-600">
                                                {{ \Carbon\Carbon::parse($task->planned_date)->translatedFormat('d M Y') }}
                                            </span>
                                            <span class="block rounded-lg border border-gray-200 bg-white px-3 py-1 text-xs font-bold text-gray-600">
                                                {{ str_replace('_', ' ', $task->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-sm text-gray-400">Belum ada agenda jadwal budidaya.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>
