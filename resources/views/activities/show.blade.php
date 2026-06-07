<x-app-layout>
    @php
        $statusClass = [
            'belum_dikerjakan' => 'bg-amber-100 text-amber-700',
            'sedang_dikerjakan' => 'bg-blue-100 text-blue-700',
            'selesai' => 'bg-emerald-100 text-emerald-700',
            'tidak_dilakukan' => 'bg-red-100 text-red-700',
        ];
    @endphp

    <div class="py-6 px-8">
        <div class="max-w-3xl mx-auto space-y-6">
            <a href="{{ url()->previous() }}" class="text-sm text-gray-500 hover:text-gray-700">&lt;- Kembali</a>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="bg-[#0d1b2a] text-white p-6">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold">{{ $activity->title }}</h1>
                            <p class="text-sm text-gray-300 mt-1">{{ $activity->plant->name }} - {{ $activity->plant->variety->name ?? 'Varietas cabai rawit' }}</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass[$activity->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ str_replace('_', ' ', $activity->status) }}
                        </span>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Deskripsi Aktivitas</h3>
                        <p class="mt-2 text-sm text-gray-700">{{ $activity->description }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-lg bg-gray-50 p-4">
                            <p class="text-xs font-semibold uppercase text-gray-500">Jadwal Rencana</p>
                            <p class="mt-1 text-lg font-bold text-gray-900">{{ $activity->planned_date->translatedFormat('d F Y') }}</p>
                        </div>

                        @if($activity->done_at)
                            <div class="rounded-lg bg-emerald-50 p-4">
                                <p class="text-xs font-semibold uppercase text-emerald-600">Selesai Pada</p>
                                <p class="mt-1 text-lg font-bold text-emerald-700">{{ $activity->done_at->translatedFormat('d F Y H:i') }}</p>
                            </div>
                        @endif
                    </div>

                    @if($activity->assignedUser)
                        <div class="rounded-lg border border-blue-100 bg-blue-50 p-4">
                            <p class="text-xs font-semibold uppercase text-blue-700">Ditugaskan Ke</p>
                            <p class="mt-1 font-semibold text-gray-900">{{ $activity->assignedUser->name }}</p>
                            <p class="text-sm text-gray-600">{{ $activity->assignedUser->email }}</p>
                        </div>
                    @endif

                    @if($activity->system_notes)
                        <div class="rounded-lg border border-emerald-100 bg-emerald-50 p-4">
                            <p class="text-xs font-semibold uppercase text-emerald-700">Catatan Sistem</p>
                            <p class="mt-2 text-sm text-gray-700">{{ $activity->system_notes }}</p>
                        </div>
                    @endif

                    @if($activity->notes)
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <p class="text-xs font-semibold uppercase text-gray-600">Catatan Jadwal</p>
                            <p class="mt-2 whitespace-pre-line text-sm text-gray-700">{{ $activity->notes }}</p>
                        </div>
                    @endif
                </div>

                @if(auth()->user()->role === 'worker' || (auth()->user()->role === 'owner' && $activity->plant->owner_id === auth()->id()))
                    <div class="border-t border-gray-100 bg-gray-50 p-6">
                        @if(in_array($activity->status, ['belum_dikerjakan', 'sedang_dikerjakan']))
                            @if($activity->status === 'belum_dikerjakan')
                                <form action="{{ route('activities.updateStatus', $activity) }}" method="POST" class="mb-4">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="sedang_dikerjakan">
                                    <button type="submit" class="w-full rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">Mulai Pekerjaan</button>
                                </form>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <form action="{{ route('activities.complete', $activity) }}" method="POST" class="space-y-2">
                                    @csrf
                                    @method('PATCH')
                                    <textarea name="notes" placeholder="Catatan pelaksanaan (opsional)" class="w-full rounded-lg border-gray-200 text-sm focus:border-[#00713d] focus:ring-[#00713d]" rows="3"></textarea>
                                    <button type="submit" class="w-full rounded-lg bg-emerald-600 px-6 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Tandai Selesai</button>
                                </form>

                                <form action="{{ route('activities.notDone', $activity) }}" method="POST" class="space-y-2">
                                    @csrf
                                    @method('PATCH')
                                    <textarea name="notes" placeholder="Alasan tidak dilakukan, contoh: hujan deras" class="w-full rounded-lg border-gray-200 text-sm focus:border-[#00713d] focus:ring-[#00713d]" rows="3" required></textarea>
                                    <button type="submit" class="w-full rounded-lg bg-red-600 px-6 py-2 text-sm font-semibold text-white hover:bg-red-700">Tidak Dilakukan</button>
                                </form>
                            </div>
                        @elseif($activity->status === 'selesai')
                            <p class="text-center text-sm font-semibold text-emerald-700">Aktivitas sudah selesai.</p>
                        @elseif($activity->status === 'tidak_dilakukan')
                            <p class="text-center text-sm font-semibold text-red-700">Aktivitas ditandai tidak dilakukan.</p>
                        @endif
                    </div>
                @else
                    <div class="border-t border-gray-100 bg-gray-50 p-6">
                        <p class="text-center text-sm text-gray-600">Mode monitoring penyuluh. Status hanya dapat diubah oleh worker atau owner terkait.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
