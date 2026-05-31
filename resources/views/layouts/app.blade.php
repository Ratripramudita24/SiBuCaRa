<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiBuCaRa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#f8fcf9]">

    <div class="flex h-screen overflow-hidden">

        <div class="h-full flex-shrink-0 sticky top-0">
            @include('layouts.sidebar')
        </div>

        <div class="flex-1 flex flex-col h-full overflow-y-auto">

            @include('layouts.navbar')

            <main class="flex-grow">
                {{ $slot }}
            </main>

            @include('layouts.footer')

        </div>
    </div>

</body>
</html>
