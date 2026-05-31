<x-app-layout>
    <div class="py-8 px-8 max-w-4xl mx-auto space-y-6">
        <a href="{{ route('plants.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-[#00713d]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Tanaman
        </a>

        <div class="rounded-2xl border border-emerald-100 bg-white/85 p-6 shadow-sm">
            <p class="text-xs font-black uppercase tracking-[0.2em] text-[#00713d]">Jadwal Otomatis</p>
            <h2 class="mt-2 text-3xl font-black text-[#0d1b2a]">Tambah Tanaman Cabai Rawit</h2>
            <p class="mt-2 text-sm text-gray-500">Masukkan detail penanaman. Sistem akan membuat rangkaian aktivitas budidaya dari tanggal tanam.</p>
        </div>

        <div class="rounded-2xl border border-emerald-100 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between border-b border-emerald-50 bg-emerald-50/70 px-6 py-4">
                <h3 class="text-sm font-black text-emerald-950">Form Detail Penanaman</h3>
                <span class="rounded-full bg-emerald-100 px-3 py-1 text-[10px] font-black text-emerald-800">WAJIB DIISI</span>
            </div>

            <form action="{{ route('plants.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Tanaman</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Cabai Rawit Blok A-01" required class="w-full rounded-xl border-gray-200 text-sm focus:border-[#00713d] focus:ring-[#00713d]">
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Varietas Cabai Rawit</label>
                        <select name="variety_id" required class="w-full rounded-xl border-gray-200 text-sm focus:border-[#00713d] focus:ring-[#00713d]">
                            <option value="">Pilih varietas cabai rawit</option>
                            @foreach($varieties as $variety)
                                <option value="{{ $variety->id }}" @selected(old('variety_id') == $variety->id)>{{ $variety->name }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-[11px] text-gray-500">Pilihan berasal dari master varietas sistem.</p>
                        <x-input-error :messages="$errors->get('variety_id')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Tanam</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" required class="w-full rounded-xl border-gray-200 text-sm focus:border-[#00713d] focus:ring-[#00713d]">
                        <x-input-error :messages="$errors->get('start_date')" class="mt-1" />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Lokasi Bedengan / Lahan</label>
                    <input type="text" name="location" value="{{ old('location') }}" placeholder="Contoh: Sawah Sektor Barat" class="w-full rounded-xl border-gray-200 text-sm focus:border-[#00713d] focus:ring-[#00713d]">
                    <x-input-error :messages="$errors->get('location')" class="mt-1" />
                </div>

                <div class="rounded-2xl border border-emerald-100 bg-emerald-50/60 p-4 text-sm text-emerald-800">
                    Jadwal seleksi benih, perawatan, dan aktivitas lanjutan akan dibuat otomatis setelah data disimpan.
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('plants.index') }}" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50">Batalkan</a>
                    <button type="submit" class="rounded-xl bg-[#00713d] px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#005c32]">Buat Jadwal Otomatis</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
