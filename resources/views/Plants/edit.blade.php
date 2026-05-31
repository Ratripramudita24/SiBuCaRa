<x-app-layout>
    <div class="py-6 px-8 max-w-4xl mx-auto space-y-6">
        <div>
            <a href="{{ route('plants.show', $plant) }}" class="text-xs text-gray-500 hover:text-gray-700">&lt;- Kembali ke Detail Tanaman</a>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <h3 class="text-lg font-bold text-[#00713d]">Edit Data Tanaman</h3>
            <p class="text-xs text-gray-500 mt-1">Ubah nama, varietas cabai rawit, dan lokasi tanaman. Tanggal tanam dikunci agar jadwal otomatis yang sudah dibuat tetap konsisten.</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-6 py-3 border-b border-gray-100">
                <h4 class="text-sm font-bold text-gray-800">Form Tanaman</h4>
            </div>

            <form action="{{ route('plants.update', $plant) }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Tanaman</label>
                    <input type="text" name="name" value="{{ old('name', $plant->name) }}" required class="w-full text-sm rounded-lg border-gray-200 focus:ring-1 focus:ring-[#00713d] focus:border-[#00713d]">
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Varietas Cabai Rawit</label>
                        <select name="variety_id" required class="w-full text-sm rounded-lg border-gray-200 focus:ring-1 focus:ring-[#00713d] focus:border-[#00713d]">
                            @foreach($varieties as $variety)
                                <option value="{{ $variety->id }}" @selected(old('variety_id', $plant->variety_id) == $variety->id)>{{ $variety->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('variety_id')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Tanam</label>
                        <input type="date" value="{{ \Carbon\Carbon::parse($plant->start_date)->format('Y-m-d') }}" disabled class="w-full text-sm rounded-lg border-gray-200 bg-gray-50 text-gray-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Lokasi Bedengan / Lahan</label>
                    <input type="text" name="location" value="{{ old('location', $plant->location) }}" class="w-full text-sm rounded-lg border-gray-200 focus:ring-1 focus:ring-[#00713d] focus:border-[#00713d]">
                    <x-input-error :messages="$errors->get('location')" class="mt-1" />
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('plants.show', $plant) }}" class="px-5 py-2 border border-gray-200 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">Batalkan</a>
                    <button type="submit" class="px-6 py-2 bg-[#00713d] hover:bg-[#005c32] text-white text-sm font-semibold rounded-lg shadow-md">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
