<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $appName ?? config('app.name') }}</title>

    {{-- FONT --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- FAVICON --}}
    @if (!empty($appLogo))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $appLogo) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @endif

    {{-- ASSETS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased relative overflow-hidden">

    {{-- BASE BACKGROUND --}}
    <div class="absolute inset-0 bg-gray-100"></div>

    {{-- DECORATIVE SHAPES --}}
    <div class="absolute -top-48 -left-48 w-[520px] h-[520px]
                bg-[#0D47A1]/10 rounded-full blur-3xl"></div>

    <div class="absolute top-1/3 -right-40 w-[420px] h-[420px]
                bg-[#FBC02D]/10 rounded-full blur-3xl"></div>

    {{-- CONTENT --}}
    <main class="relative z-10 min-h-screen flex items-center justify-center p-4">
        {{ $slot }}
    </main>

</body>
</html>
