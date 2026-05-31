<aside class="w-64 min-h-screen bg-[#0b1826] text-gray-300 flex flex-col justify-between p-4 shrink-0 shadow-2xl shadow-slate-950/20">
    @php $role = Auth::user()->role ?? 'owner'; @endphp

    <div>
        <div class="mb-8 rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-4">
            <h1 class="text-xl font-black text-[#2ec4b6] tracking-wide">SiBuCaRa</h1>
            <p class="mt-1 text-xs leading-relaxed text-slate-400">Sistem Budidaya Cabai Rawit</p>
        </div>

        <div class="space-y-2">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white ring-1 ring-white/10 border-l-4 border-[#2ec4b6]' : 'hover:bg-white/[0.06] hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v4H3V3zm0 7h18v11H3V10z"/></svg>
                Dashboard
            </a>

            @if($role === 'owner')
                <a href="{{ route('plants.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition {{ request()->routeIs('plants.*') ? 'bg-white/10 text-white ring-1 ring-white/10 border-l-4 border-[#2ec4b6]' : 'hover:bg-white/[0.06] hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l3 7h7l-5.5 4 2 7L12 16l-6.5 6 2-7L2 9h7l3-7z"/></svg>
                    Tanaman
                </a>

                <a href="{{ route('schedules.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition {{ request()->routeIs('schedules.*') ? 'bg-white/10 text-white ring-1 ring-white/10 border-l-4 border-[#2ec4b6]' : 'hover:bg-white/[0.06] hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V8H3v11a2 2 0 002 2z"/></svg>
                    Jadwal Budidaya
                </a>

                <a href="{{ route('workers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition {{ request()->routeIs('workers.*') ? 'bg-white/10 text-white ring-1 ring-white/10 border-l-4 border-[#2ec4b6]' : 'hover:bg-white/[0.06] hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                    Worker
                </a>
            @endif

            <a href="{{ route('plants.calendar') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition {{ request()->routeIs('plants.calendar') ? 'bg-white/10 text-white ring-1 ring-white/10 border-l-4 border-[#2ec4b6]' : 'hover:bg-white/[0.06] hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V8H3v11a2 2 0 002 2z"/></svg>
                Kalender
            </a>

            <a href="{{ route('plants.report') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition {{ request()->routeIs('plants.report') ? 'bg-white/10 text-white ring-1 ring-white/10 border-l-4 border-[#2ec4b6]' : 'hover:bg-white/[0.06] hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6M5 21h14a2 2 0 002-2V7l-5-5H5a2 2 0 00-2 2v15a2 2 0 002 2z"/></svg>
                Laporan
            </a>
        </div>
    </div>

    <a href="{{ route('profile.edit') }}" class="border border-white/10 bg-white/[0.03] flex items-center gap-3 hover:bg-white/[0.07] p-3 rounded-2xl transition duration-200 group">
        <div class="w-10 h-10 rounded-full bg-[#00713d] flex items-center justify-center text-white font-black text-sm group-hover:bg-[#005c32]">
            {{ substr(Auth::user()->name, 0, 2) }}
        </div>
        <div class="overflow-hidden flex-1">
            <p class="text-sm font-semibold text-white truncate group-hover:text-[#2ec4b6]">{{ Auth::user()->name }}</p>
            <p class="text-[11px] text-gray-500">{{ ucfirst($role) }}</p>
        </div>
    </a>
</aside>
