<x-app-layout>
    <div class="py-8 px-8 max-w-6xl mx-auto space-y-6">
        @php
            $workerCount = $workers->count();
            $totalTasks = $workers->sum('total_assigned_count');
            $completedTasks = $workers->sum('completed_assigned_count');
        @endphp

        <div class="rounded-2xl border border-emerald-100 bg-white/85 p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-[#00713d]">Tim Lapangan</p>
                    <h2 class="mt-2 text-3xl font-black text-[#0d1b2a]">Worker Saya</h2>
                    <p class="mt-1 text-sm text-gray-500">Kelola akun pekerja kebun dan pantau beban tugasnya.</p>
                </div>
                <a href="{{ route('workers.create') }}" class="inline-flex justify-center rounded-xl bg-[#00713d] px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#005c32]">Buat Worker</a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-gray-500">Total Worker</p>
                <p class="mt-3 text-3xl font-black text-gray-900">{{ $workerCount }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-gray-500">Total Tugas</p>
                <p class="mt-3 text-3xl font-black text-gray-900">{{ $totalTasks }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-emerald-700">Tugas Selesai</p>
                <p class="mt-3 text-3xl font-black text-emerald-700">{{ $completedTasks }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($workers as $worker)
                @php
                    $totalAssigned = $worker->total_assigned_count;
                    $completed = $worker->completed_assigned_count;
                    $progress = $totalAssigned > 0 ? round(($completed / $totalAssigned) * 100) : 0;
                @endphp
                <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-[#00713d] text-sm font-black text-white">
                            {{ substr($worker->name, 0, 2) }}
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-lg font-black text-gray-900">{{ $worker->name }}</h3>
                            <p class="truncate text-sm text-gray-500">{{ $worker->email }}</p>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-xl bg-gray-50 p-3">
                            <span class="block text-xs text-gray-500">Total Tugas</span>
                            <span class="text-xl font-black text-gray-900">{{ $totalAssigned }}</span>
                        </div>
                        <div class="rounded-xl bg-emerald-50 p-3">
                            <span class="block text-xs text-emerald-700">Selesai</span>
                            <span class="text-xl font-black text-emerald-700">{{ $completed }}</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100">
                            <div class="h-2 rounded-full bg-[#00713d]" style="width: {{ $progress }}%"></div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">{{ $progress }}% tugas selesai</p>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <a href="{{ route('workers.show', $worker) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-bold text-gray-700 hover:bg-gray-50">Detail</a>
                        <a href="{{ route('workers.edit', $worker) }}" class="rounded-lg border border-blue-200 px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-50">Edit</a>
                        <form action="{{ route('workers.destroy', $worker) }}" method="POST" onsubmit="return confirm('Hapus worker ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-bold text-red-700 hover:bg-red-50">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="md:col-span-2 xl:col-span-3 rounded-2xl border border-dashed border-emerald-200 bg-white p-10 text-center shadow-sm">
                    <p class="font-bold text-gray-700">Belum ada worker.</p>
                    <p class="mt-1 text-sm text-gray-500">Buat akun worker agar tugas budidaya dapat dilihat dan diperbarui.</p>
                    <a href="{{ route('workers.create') }}" class="mt-4 inline-flex rounded-xl bg-[#00713d] px-4 py-2 text-sm font-bold text-white hover:bg-[#005c32]">Buat Worker Pertama</a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
