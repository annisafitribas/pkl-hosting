@props(['header' => null])

@php
$menuLinks = [
    ['label' => 'Beranda', 'route' => 'user.dashboard'],
    ['label' => 'Pengajuan', 'route' => 'user.pengajuan.index'],
    ['label' => 'Laporan', 'route' => 'user.laporan.index'],
    ['label' => 'Tentang', 'route' => 'user.kantors.index'],
    ['label' => 'Profil', 'route' => 'user.profile.index'],
];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $appName }}</title>

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
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="font-sans antialiased bg-[#F5F7FA] text-gray-800">

<div x-data="{ openMobile: false }" class="min-h-screen flex flex-col">

    @php
        $fotoUser = auth()->user()->foto
            ? asset('storage/' . auth()->user()->foto)
            : asset('default-user.png');
    @endphp

    <!-- NAVBAR -->
    <nav class="fixed top-0 left-0 right-0 z-50
                bg-[#123B6E]/95 backdrop-blur
                border-b border-[#0F2F57]
                shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-2">
            <div class="flex items-center justify-between h-16">

                {{-- LOGO --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow overflow-hidden">
                        @if ($appLogo)
                            <img src="{{ asset('storage/' . $appLogo) }}" class="max-w-full max-h-full object-contain">
                        @else
                            <x-heroicon-o-building-office class="w-6 h-6 text-[#123B6E]" />
                        @endif
                    </div>
                    <span class="text-lg font-semibold text-white">{{ $appName }}</span>
                </div>

                {{-- MENU DESKTOP --}}
                <div class="hidden md:flex items-center gap-6 text-sm font-medium text-white">
                    @foreach ($menuLinks as $link)
                        <a href="{{ route($link['route']) }}"
                        class="{{ request()->routeIs($link['route'].'*')
                                ? 'font-semibold border-b-2 border-yellow-400'
                                : 'hover:text-yellow-300 transition' }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>

                {{-- USER + HAMBURGER --}}
                <div class="flex items-center gap-3">

                    {{-- USER DROPDOWN --}}
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 focus:outline-none">
                                <div class="w-9 h-9 rounded-full overflow-hidden border shadow">
                                    <img
                                        src="{{ $fotoUser }}"
                                        alt="Foto {{ auth()->user()->name }}"
                                        class="w-full h-full object-cover"
                                        onerror="this.onerror=null;this.src='{{ asset('default-user.png') }}'"
                                    >
                                </div>

                                {{-- NAMA (DESKTOP ONLY) --}}
                                <span class="hidden md:block font-semibold text-sm text-white">
                                    {{ auth()->user()->name }}
                                </span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3 border-b">
                                <p class="font-semibold">{{ auth()->user()->name }}</p>
                                <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
                            </div>

                            <x-dropdown-link href="{{ route('user.profile.index') }}">
                                <div class="flex items-center gap-2">
                                    <x-heroicon-o-user-circle class="w-4 h-4" />
                                    <span>Profil Saya</span>
                                </div>
                            </x-dropdown-link>

                            <div class="my-1 border-t"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-red-600"
                                >
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-arrow-left-on-rectangle class="w-4 h-4" />
                                        <span>Keluar</span>
                                    </div>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>

                    {{-- HAMBURGER (MOBILE ONLY) --}}
                    <button @click="openMobile = !openMobile"
                            class="md:hidden p-2 rounded-lg hover:bg-white/10">
                        <x-heroicon-o-bars-3 class="w-6 h-6 text-white"/>
                    </button>

                </div>
            </div>
        </div>

        {{-- MENU MOBILE --}}
        <div x-cloak
            x-show="openMobile"
            x-transition
            @click.away="openMobile = false"
            class="md:hidden bg-[#0F2F57] border-t border-[#0A2445]">

            <div class="px-6 py-5 space-y-2 text-sm text-white">
                @foreach ($menuLinks as $link)
                    <a href="{{ route($link['route']) }}"
                    @click.stop
                    class="block px-2 py-2 rounded-lg hover:bg-white/10 transition">
                        {{ $link['label'] }}
                    </a>
                @endforeach

                <hr class="my-3 border-white/10">

                <a href="{{ route('user.profile.index') }}"
                @click.stop
                class="block px-2 py-2 rounded-lg hover:bg-white/10 transition">
                    Profil Saya
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            @click.stop
                            class="block w-full text-left px-2 py-2 rounded-lg
                                hover:bg-white/10 transition text-red-500">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- HEADER -->
    <header class="pt-24 pb-4 bg-white border-b">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div class="font-semibold text-[#0D1B2A]">
                {{ $header ?? 'Dashboard' }}
            </div>
            <div class="text-sm text-gray-600">
                <span id="waktu"></span>
                <span class="ml-2">{{ now()->format('d/m/Y') }}</span>
            </div>
        </div>
    </header>

    <!-- CONTENT -->
    <main class="flex-1 pt-10 px-6 max-w-7xl mx-auto w-full">
        {{ $slot }}
    </main>
</div>

<script>
function updateJam() {
    document.getElementById('waktu').textContent =
        new Date().toLocaleTimeString('id-ID', { hour12: false });
}
setInterval(updateJam, 1000);
updateJam();
</script>

<x-user-toast />
</body>
</html>