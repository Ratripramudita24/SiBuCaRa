<x-app-layout>
    <div class="py-6 px-8 max-w-4xl mx-auto space-y-6">
        <div>
            <a href="{{ route('plants.index') }}" class="text-xs text-gray-500 hover:text-gray-700">← Kembali ke Jadwal</a>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex justify-between items-center gap-4">
            <div class="space-y-1">
                <h3 class="text-lg font-bold text-[#00713d]">Automasi Jadwal Budidaya</h3>
                <p class="text-xs text-gray-500">Masukkan data detail penanaman di bawah ini. Sistem akan otomatis melacak jadwal kegiatan pemeliharaan.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-6 py-3 border-b border-gray-100 flex justify-between items-center">
                <h4 class="text-sm font-bold text-gray-800">📋 Form Detail Penanaman</h4>
                <span class="text-[10px] bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-sm">WAJIB DIISI</span>
            </div>

            <form action="{{ route('plants.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Cabai / Label Tanaman</label>
                    <input type="text" name="name" placeholder="Contoh: Cabai Rawit Blok A-01" required class="w-full text-sm rounded-lg border-gray-200 focus:ring-1 focus:ring-[#00713d] focus:border-[#00713d]">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Penanaman Utama (Seedling)</label>
                        <input type="date" name="start_date" required class="w-full text-sm rounded-lg border-gray-200 focus:ring-1 focus:ring-[#00713d] focus:border-[#00713d]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Estimasi Luas Lahan / Lokasi Bedengan</label>
                        <input type="text" name="location" placeholder="Contoh: Sawah Sektor Barat" class="w-full text-sm rounded-lg border-gray-200 focus:ring-1 focus:ring-[#00713d] focus:border-[#00713d]">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('plants.index') }}" class="px-5 py-2 border border-gray-200 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">Batalkan</a>
                    <button type="submit" class="px-6 py-2 bg-[#00713d] hover:bg-[#005c32] text-white text-sm font-semibold rounded-lg shadow-md">✨ Buat Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
