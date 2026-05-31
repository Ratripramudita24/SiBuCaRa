<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiBuCaRa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#f4faf6] text-[#0d1b2a]">

    <div class="flex h-screen overflow-hidden">

        <div class="h-full shrink-0 sticky top-0">
            @include('layouts.sidebar')
        </div>

        <div class="flex-1 flex flex-col h-full overflow-y-auto bg-[radial-gradient(circle_at_top_right,rgba(46,196,182,0.12),transparent_34%),linear-gradient(180deg,#f7fcf8_0%,#f4faf6_52%,#f8fbf9_100%)]">

            @include('layouts.navbar')

            <main class="flex-grow">
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>

            @include('layouts.footer')

        </div>
    </div>

</body>
</html>
