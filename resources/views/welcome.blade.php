@php
use Illuminate\Support\Facades\Route;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-800 font-sans flex items-center justify-center">

    <main class="w-full max-w-xl px-6">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">

            {{-- TITLE --}}
            <h1 class="text-2xl font-medium text-[#0D1B2A]">
                {{ config('app.name') }}
            </h1>

            <p class="mt-2 text-sm text-gray-600">
                Sistem informasi untuk pengelolaan data dan aktivitas secara terintegrasi.
            </p>

            {{-- ACTION --}}
            <div class="mt-8 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('login') }}"
                   class="w-full sm:w-auto px-5 py-2.5 text-sm rounded-md
                          bg-[#0D1B2A] text-white text-center
                          hover:bg-[#324463] transition">
                    Masuk
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="w-full sm:w-auto px-5 py-2.5 text-sm rounded-md
                              border border-gray-300 text-center
                              hover:bg-gray-100 transition">
                        Daftar
                    </a>
                @endif
            </div>

            {{-- DIVIDER --}}
            <div class="my-8 border-t border-gray-200"></div>

            {{-- INFO --}}
            <ul class="space-y-3 text-sm text-gray-600">
                <li class="flex gap-3">
                    <span class="w-1.5 h-1.5 mt-2 bg-yellow-400 rounded-full"></span>
                    Akses satu pintu untuk seluruh pengguna
                </li>
                <li class="flex gap-3">
                    <span class="w-1.5 h-1.5 mt-2 bg-yellow-400 rounded-full"></span>
                    Antarmuka sederhana dan mudah digunakan
                </li>
                <li class="flex gap-3">
                    <span class="w-1.5 h-1.5 mt-2 bg-yellow-400 rounded-full"></span>
                    Cocok untuk kebutuhan instansi dan organisasi
                </li>
            </ul>

        </div>

        {{-- FOOTER --}}
        <p class="mt-6 text-center text-xs text-gray-400">
            © {{ date('Y') }} {{ config('app.name') }}
        </p>
    </main>

</body>
</html>
