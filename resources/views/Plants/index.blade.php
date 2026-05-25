<x-app-layout>
    <div class="py-6 px-8 bg-[#f8fcf9] min-h-screen space-y-6 max-w-5xl mx-auto">

        <div class="flex items-center gap-2 text-sm font-medium text-gray-500">
            <button onclick="window.history.back()" class="hover:text-gray-800 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Input Tanggal Penanaman
            </button>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-2xs flex justify-between items-center gap-6">
            <div class="space-y-2">
                <h3 class="text-xl font-bold text-[#00713d] tracking-tight">Automasi Jadwal Budidaya</h3>
                <p class="text-xs text-gray-500 leading-relaxed max-w-2xl">
                    Masukkan data detail penanaman Anda di bawah ini. Sistem kami akan secara otomatis menghasilkan kalender pemeliharaan lengkap mulai dari penyemaian hingga masa panen berdasarkan varietas yang dipilih.
                </p>
            </div>
            <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-2xl flex items-center justify-center flex-shrink-0 border border-gray-100">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M3 12h1m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="bg-gray-50/70 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h4 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#00713d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Form Detail Penanaman
                </h4>
                <span class="text-[10px] bg-emerald-100 text-emerald-800 font-extrabold px-2 py-0.5 rounded-md uppercase tracking-wider">Wajib Diisi</span>
            </div>

            <form action="{{ route('plants.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Cabai</label>
                    <input type="text" name="name" placeholder="Masukkan Nama Cabai (Contoh: Cabai Rawit 1)" required
                           class="w-full text-sm rounded-lg bg-gray-50/50 border-gray-200 focus:ring-1 focus:ring-[#00713d] focus:border-[#00713d] py-2.5 px-4">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Tanggal Penanaman Utama (Seedling)</label>
                        <div class="relative">
                            <input type="date" name="start_date" required
                                   class="w-full text-sm rounded-lg bg-gray-50/50 border-gray-200 focus:ring-1 focus:ring-[#00713d] focus:border-[#00713d] py-2.5 px-4">
                        </div>
                        <span class="text-[10px] text-gray-400 mt-1.5 block italic">Tanggal saat benih pertama kali masuk ke media semai.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Estimasi Luas Lahan (m²) / Lokasi Lahan</label>
                        <input type="text" name="location" placeholder="Contoh: Sawah Barat / Blok B"
                               class="w-full text-sm rounded-lg bg-gray-50/50 border-gray-200 focus:ring-1 focus:ring-[#00713d] focus:border-[#00713d] py-2.5 px-4">
                    </div>
                </div>

                <div class="p-5 rounded-xl border border-gray-100 bg-gray-50/40 space-y-4">
                    <div class="flex justify-between items-center text-xs font-bold">
                        <span class="text-gray-700">Alur Jadwal Otomatis</span>
                        <span class="text-[#00713d]">Estimasi Panen: +110 HST</span>
                    </div>

                    <div class="relative pt-2">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-[#00713d] h-2 rounded-full w-[20%]"></div>
                        </div>
                        <div class="flex justify-between text-[10px] font-bold text-gray-400 mt-3 uppercase tracking-wider">
                            <span class="text-[#00713d]">Semai</span>
                            <span>Tanam</span>
                            <span>Vegetatif</span>
                            <span>Panen</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="window.history.back()" class="px-6 py-2.5 border border-gray-200 text-sm font-semibold rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Batalkan
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-[#00713d] hover:bg-[#005c32] text-white text-sm font-semibold rounded-lg shadow-sm transition flex items-center gap-2">
                        <span>✨ Buat jadwal</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 items-center">
            <div class="md:col-span-2 space-y-3">
                <h4 class="text-sm font-bold text-gray-900">Mengapa data ini penting?</h4>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Akurasi tanggal penanaman memungkinkan SiBuCaRa untuk menyinkronkan data cuaca lokal dengan fase kritis tanaman Anda. Kami akan memberikan notifikasi pemupukan dan pencegahan hama tepat pada waktunya.
                </p>
                <ul class="text-xs font-semibold text-gray-700 space-y-1.5">
                    <li class="flex items-center gap-2 text-[#00713d]">
                        ✓ Kalkulasi kebutuhan pupuk otomatis
                    </li>
                    <li class="flex items-center gap-2 text-[#00713d]">
                        ✓ Pengaturan logistik tenaga kerja panen
                    </li>
                </ul>
            </div>

            <div class="md:col-span-1 rounded-xl overflow-hidden shadow-md border border-gray-100 h-36">
                <img src="https://images.unsplash.com/photo-1592417817098-8f3d6eb18865?q=80&w=600&auto=format&fit=crop"
                     alt="Kebun Cabai" class="w-full h-full object-cover">
            </div>
        </div>

    </div>
</x-app-layout>
