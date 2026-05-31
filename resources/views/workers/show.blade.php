<x-app-layout>
    <div class="py-6 px-8 max-w-5xl mx-auto space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <a href="{{ route('workers.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&lt;- Kembali</a>
                <h2 class="mt-3 text-2xl font-bold text-[#0d1b2a]">{{ $worker->name }}</h2>
                <p class="text-sm text-gray-500">{{ $worker->email }}</p>
            </div>
            <a href="{{ route('workers.edit', $worker) }}" class="inline-flex rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Edit Worker</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                <p class="text-xs uppercase text-gray-500">Total Tugas</p>
                <p class="mt-2 text-3xl font-bold">{{ $activities->count() }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                <p class="text-xs uppercase text-emerald-600">Selesai</p>
                <p class="mt-2 text-3xl font-bold text-emerald-700">{{ $completedCount }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                <p class="text-xs uppercase text-amber-600">Belum Dikerjakan</p>
                <p class="mt-2 text-3xl font-bold text-amber-700">{{ $pendingCount }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900">Tugas Worker</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($activities as $activity)
                    <a href="{{ route('activities.show', $activity) }}" class="block p-5 hover:bg-gray-50">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $activity->title }}</p>
                                <p class="text-sm text-gray-500">{{ $activity->plant->name }} - {{ $activity->planned_date->translatedFormat('d M Y') }}</p>
                            </div>
                            <span class="text-xs font-semibold text-gray-600">{{ str_replace('_', ' ', $activity->status) }}</span>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-sm text-gray-500">Belum ada tugas untuk worker ini.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
