<aside class="w-64 min-h-screen bg-[#0d1b2a] text-gray-300 flex flex-col justify-between p-4 flex-shrink-0">
    <div>
        <div class="flex flex-col mb-8 px-2">
            <h1 class="text-xl font-bold text-[#2ec4b6] tracking-wide">SiBuCaRa</h1>
            <p class="text-xs text-gray-500">Sistem Budidaya Cabai Rawit</p>
        </div>

        <div class="space-y-2">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-[#1e293b] text-white border-l-4 border-[#2ec4b6]' : 'hover:bg-[#1e293b]/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                Dashboard
            </a>

            <a href="{{ route('plants.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('plants.index') || request()->routeIs('plants.create') ? 'bg-[#1e293b] text-white border-l-4 border-[#2ec4b6]' : 'hover:bg-[#1e293b]/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Jadwal
            </a>

            <a href="{{ route('plants.calendar') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('plants.calendar') ? 'bg-[#1e293b] text-white border-l-4 border-[#2ec4b6]' : 'hover:bg-[#1e293b]/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
                Kalender
            </a>

            <a href="{{ route('plants.report') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('plants.report') ? 'bg-[#1e293b] text-white border-l-4 border-[#2ec4b6]' : 'hover:bg-[#1e293b]/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan
            </a>
        </div>
    </div>

    <a href="{{ route('profile.edit') }}" class="border-t border-gray-800 pt-4 flex items-center gap-3 hover:bg-[#1e293b]/40 p-2 rounded-xl transition duration-200 group">
        <div class="w-9 h-9 rounded-full bg-[#00713d] flex items-center justify-center text-white font-bold text-sm group-hover:bg-[#005c32]">
            {{ substr(Auth::user()->name, 0, 2) }}
        </div>
        <div class="overflow-hidden flex-1">
            <p class="text-sm font-semibold text-white truncate group-hover:text-[#2ec4b6]">{{ Auth::user()->name }}</p>
            <p class="text-[11px] text-gray-500">Pengaturan Profil ⚙️</p>
        </div>
    </a>
</aside>
