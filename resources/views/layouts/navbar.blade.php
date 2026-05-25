<header class="bg-white border-b border-gray-100 px-8 py-4 flex justify-between items-center">
    <div class="text-sm font-medium text-gray-500">Panel Kontrol Budidaya</div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-800">Log Out</button>
    </form>
</header>
