{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SiBuCaRa - Sistem Budidaya Cabai Rawit</title>

    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8faf8;
        }

        .hero-gradient {
            background:
                linear-gradient(to right, rgba(255, 255, 255, 1) 35%, rgba(255, 255, 255, .75) 50%, rgba(255, 255, 255, 0) 70%);
        }
    </style>
</head>

<body class="text-gray-800">

    {{-- Navbar --}}
    <header class="w-full bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between py-5">

                {{-- Logo --}}
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 bg-[#00713d] rounded-2xl flex items-center justify-center shadow-md">

                        <img src="{{ asset('images/Icon.png') }}" alt="Logo SiBuCaRa" class="w-7 h-7 object-contain">

                    </div>

                    <div>
                        <h1 class="text-3xl font-extrabold text-green-700">
                            SiBuCaRa
                        </h1>

                        <p class="text-gray-500 text-sm">
                            Sistem Budidaya Cabai Rawit
                        </p>
                    </div>
                </div>
                {{-- Auth Button --}}
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-8 py-3 border border-green-700 rounded-xl text-green-700 font-semibold hover:bg-green-50 transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-8 py-3 border border-green-700 rounded-xl text-green-700 font-semibold hover:bg-green-50 transition">
                            Masuk
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-8 py-3 bg-green-700 text-white rounded-xl font-semibold hover:bg-green-800 transition shadow">
                                Daftar Akun
                            </a>
                        @endif
                    @endauth
                </div>

            </div>
        </div>
    </header>

    {{-- Hero Section --}}
    <section class="relative overflow-hidden">
        <div class="grid lg:grid-cols-2 min-h-[700px]">

            {{-- Left Content --}}
            <div class="flex items-center bg-white">
                <div class="max-w-2xl px-6 lg:px-16 py-16">

                    <div
                        class="inline-flex items-center gap-2 bg-green-100 text-green-700 px-5 py-2 rounded-full text-sm font-semibold">
                        🌱 Aplikasi Penjadwalan Budidaya
                    </div>

                    <h1 class="mt-8 text-6xl leading-tight font-extrabold text-gray-900">
                        Kelola Budidaya
                        <span class="text-green-700 block">
                            Cabai Rawit
                        </span>
                    </h1>

                    <p class="mt-8 text-xl leading-relaxed text-gray-500 max-w-xl">
                        Jadwal otomatis, pencatatan kegiatan, dan monitoring pertumbuhan tanaman cabai rawit dalam satu
                        sistem.
                    </p>

                    {{-- Button --}}
                    <div class="mt-10 flex flex-wrap gap-5">

                        <a href="{{ route('login') }}"
                            class="px-10 py-5 bg-green-700 text-white rounded-2xl font-semibold shadow-lg hover:bg-green-800 transition">
                            🌱 Mulai Menanam Sekarang
                        </a>

                    </div>

                </div>
            </div>

            {{-- Right Image --}}
            <div class="relative">
                <div class="absolute inset-0 hero-gradient z-10"></div>

                <img src="https://images.unsplash.com/photo-1588252303782-cb80119abd6d?q=80&w=1200&auto=format&fit=crop"
                    class="w-full h-full object-cover" alt="Cabai Rawit">

                {{-- Floating Card --}}
                <div class="absolute bottom-10 right-10 bg-white/95 backdrop-blur p-8 rounded-3xl shadow-2xl z-20">
                    <p class="text-gray-500 text-lg">
                        Estimasi Panen
                    </p>

                    <h3 class="text-5xl font-extrabold text-green-700 mt-2">
                        75 Hari Lagi
                    </h3>

                    <p class="mt-3 text-green-600 font-semibold">
                        📅 Panen Optimal
                    </p>
                </div>
            </div>

        </div>
    </section>

    {{-- Feature Section --}}
    <section class="py-24 bg-[#f7faf7]">

        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            <div class="text-center mb-16">
                <p class="text-green-700 font-semibold uppercase tracking-wider">
                    🌱 Fitur Utama
                </p>

                <h2 class="mt-4 text-5xl font-extrabold text-gray-900 leading-tight">
                    Semua yang Anda Butuhkan untuk
                    <span class="text-green-700 block">
                        Budidaya Cabai Rawit
                    </span>
                </h2>
            </div>

            {{-- Cards --}}
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

                {{-- Card --}}
                <div class="bg-white rounded-3xl p-10 border border-gray-100 shadow-sm hover:shadow-xl transition">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center text-3xl mb-8">
                        📅
                    </div>

                    <h3 class="text-2xl font-bold mb-5">
                        Jadwal Otomatis
                    </h3>

                    <p class="text-gray-500 leading-relaxed">
                        Sistem membuat jadwal budidaya secara otomatis berdasarkan siklus pertumbuhan cabai rawit.
                    </p>
                </div>

                {{-- Card --}}
                <div class="bg-white rounded-3xl p-10 border border-gray-100 shadow-sm hover:shadow-xl transition">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center text-3xl mb-8">
                        📝
                    </div>

                    <h3 class="text-2xl font-bold mb-5">
                        Pantau Kegiatan Harian
                    </h3>

                    <p class="text-gray-500 leading-relaxed">
                        Lihat dan kelola kegiatan budidaya setiap hari agar tanaman tumbuh optimal.
                    </p>
                </div>

                {{-- Card --}}
                <div class="bg-white rounded-3xl p-10 border border-gray-100 shadow-sm hover:shadow-xl transition">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center text-3xl mb-8">
                        📊
                    </div>

                    <h3 class="text-2xl font-bold mb-5">
                        Riwayat & Laporan
                    </h3>

                    <p class="text-gray-500 leading-relaxed">
                        Catat setiap kegiatan dan lihat riwayat budidaya untuk evaluasi yang lebih baik.
                    </p>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid md:grid-cols-4 gap-6 mt-12">

                <div class="bg-white rounded-3xl p-8 text-center shadow-sm">
                    <h3 class="text-5xl font-extrabold text-green-700">
                        100%
                    </h3>

                    <p class="mt-2 text-gray-500">
                        Fokus Cabai Rawit
                    </p>
                </div>
            </div>

        </div>

    </section>

</body>

</html>
