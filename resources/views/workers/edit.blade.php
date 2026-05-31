<x-app-layout>
    <div class="py-6 px-8 max-w-2xl mx-auto space-y-6">
        <div>
            <a href="{{ route('workers.show', $worker) }}" class="text-sm text-gray-500 hover:text-gray-700">&lt;- Kembali</a>
            <h2 class="mt-3 text-2xl font-bold text-[#0d1b2a]">Edit Worker</h2>
            <p class="text-sm text-gray-500">Perbarui identitas worker atau reset password awal.</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <form action="{{ route('workers.update', $worker) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Pekerja</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $worker->name) }}" class="w-full rounded-lg border-gray-200 text-sm focus:border-[#00713d] focus:ring-[#00713d]" required>
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email / Username Login</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $worker->email) }}" class="w-full rounded-lg border-gray-200 text-sm focus:border-[#00713d] focus:ring-[#00713d]" required>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" class="flex-1 rounded-lg bg-[#00713d] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#005c32]">Simpan</button>
                    <a href="{{ route('workers.show', $worker) }}" class="flex-1 rounded-lg border border-gray-200 px-6 py-2.5 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-900">Reset Password</h3>
            <form action="{{ route('workers.resetPassword', $worker) }}" method="POST" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                @method('PATCH')
                <input type="password" name="password" placeholder="Password baru" class="rounded-lg border-gray-200 text-sm focus:border-[#00713d] focus:ring-[#00713d]" required>
                <input type="password" name="password_confirmation" placeholder="Konfirmasi password" class="rounded-lg border-gray-200 text-sm focus:border-[#00713d] focus:ring-[#00713d]" required>
                <button type="submit" class="md:col-span-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Reset Password</button>
            </form>
        </div>
    </div>
</x-app-layout>
