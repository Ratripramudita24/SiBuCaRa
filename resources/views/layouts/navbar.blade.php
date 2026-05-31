<header class="bg-white/90 backdrop-blur border-b border-emerald-100/70 px-8 py-4 flex justify-between items-center">
    <div>
        <div class="text-sm font-bold text-[#0d1b2a]">Panel Kontrol Budidaya</div>
        <div class="text-xs text-gray-500">Pantau tanaman, jadwal, worker, dan laporan dalam satu tempat.</div>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="rounded-lg border border-red-100 bg-red-50 px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-100 hover:text-red-800">Log Out</button>
    </form>
</header>
