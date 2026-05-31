<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex flex-col items-center mb-6">
        <div class="w-14 h-14 bg-[#00713d] rounded-2xl flex items-center justify-center shadow-md">

            <img src="{{ asset('images/Icon.png') }}" alt="Logo SiBuCaRa" class="w-7 h-7 object-contain">

        </div>
        <h2 class="text-2xl font-bold text-[#006434] tracking-tight">SiBuCaRa</h2>
        <p class="text-xs text-gray-500 mt-1">Sistem Budidaya Cabai Rawit</p>
    </div>

    <div class="flex border-b border-gray-200 mb-6 text-center text-sm font-medium">
        <a href="#" class="w-1/2 pb-3 border-b-2 border-[#00713d] text-[#00713d] font-semibold">
            Masuk
        </a>
        <a href="{{ route('register') }}" class="w-1/2 pb-3 text-gray-500 hover:text-gray-700">
            Daftar Akun
        </a>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <x-input-label for="email" :value="__('Alamat Email')"
                class="text-gray-700 font-medium mb-1 block text-sm" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <input id="email"
                    class="block w-full ps-10 pe-3 py-2 bg-emerald-50/20 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#00713d] focus:border-[#00713d]"
                    type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus
                    autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="mb-4">
            <div class="flex justify-between items-center mb-1">
                <x-input-label for="password" :value="__('Kata Sandi')" class="text-gray-700 font-medium text-sm" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-[#00713d] font-semibold hover:underline"
                        href="{{ route('password.request') }}">
                        {{ __('Lupa Sandi?') }}
                    </a>
                @endif
            </div>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <input id="password"
                    class="block w-full ps-10 pe-10 py-2 bg-emerald-50/20 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#00713d] focus:border-[#00713d]"
                    type="password" name="password" placeholder="••••••••" required autocomplete="current-password" />
                <div
                    class="absolute inset-y-0 end-0 flex items-center pe-3 text-gray-400 cursor-pointer hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="block mb-6">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox"
                    class="w-4 h-4 rounded border-gray-300 text-[#00713d] focus:ring-[#00713d]" name="remember">
                <span class="ms-2 text-xs text-gray-600">{{ __('Ingat saya di perangkat ini') }}</span>
            </label>
        </div>

        <div>
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-[#00713d] hover:bg-[#005c32] text-white text-sm font-semibold rounded-lg transition duration-150 shadow-md">
                <span>Masuk</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </div>
    </form>
</x-guest-layout>
