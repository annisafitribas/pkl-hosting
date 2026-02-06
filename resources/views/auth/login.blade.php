@php
use Illuminate\Support\Facades\Route;
@endphp

<x-guest-layout>

    <div
        class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden
               grid grid-cols-1 md:grid-cols-2">

        {{-- ================= LEFT : BRANDING PANEL ================= --}}
        <div
            class="relative hidden md:flex flex-col justify-between px-14 py-16
                   bg-gradient-to-br from-[#0D47A1] to-[#08306B]
                   text-white overflow-hidden">

            {{-- Decorative accent --}}
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-white/5 rounded-full"></div>
            <div class="absolute bottom-0 right-0 w-full h-1 bg-[#FBC02D]"></div>

            <div class="relative z-10">

                {{-- App Identity --}}
                <div class="flex items-center gap-4 mb-14">
                    @if ($appLogo)
                        <img
                            src="{{ asset('storage/' . $appLogo) }}"
                            class="w-14 h-14 object-contain bg-white rounded-xl p-2 shadow"
                        >
                    @endif

                    <div>
                        <p class="text-xs uppercase tracking-wide text-white/60">
                            Aplikasi Presensi
                        </p>
                        <p class="text-xl font-semibold">
                            {{ $appName }}
                        </p>
                    </div>
                </div>

                {{-- Institution --}}
                <h1 class="text-4xl font-bold leading-tight mb-4">
                    {{ $appPt }}
                </h1>

                <p class="text-white/80 max-w-md">
                    Sistem presensi magang berbasis kebijakan perusahaan,
                    dirancang untuk akurasi dan akuntabilitas.
                </p>

                {{-- Info list --}}
                <div class="mt-10 space-y-4 text-sm">
                    <div class="flex gap-3">
                        <span class="w-1 bg-[#FBC02D] rounded"></span>
                        <span>Presensi berbasis lokasi & radius kantor</span>
                    </div>
                    <div class="flex gap-3">
                        <span class="w-1 bg-[#FBC02D] rounded"></span>
                        <span>Rekap otomatis & laporan harian</span>
                    </div>
                    <div class="flex gap-3">
                        <span class="w-1 bg-[#FBC02D] rounded"></span>
                        <span>Mendukung WFO & WFH</span>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="relative z-10 text-xs text-white/60 mt-6">
                © {{ date('Y') }} {{ $appPt }}
            </div>
        </div>

        {{-- ================= RIGHT : LOGIN FORM ================= --}}
        <div class="flex items-center px-5 sm:px-10 py-8 sm:py-10">
            <div class="w-full max-w-md mx-auto">

                {{-- Mobile branding --}}
                <div class="md:hidden text-center mb-8">
                    @if ($appLogo)
                        <img
                            src="{{ asset('storage/' . $appLogo) }}"
                            class="w-16 h-16 mx-auto object-contain mb-3"
                        >
                    @endif
                    <h1 class="text-xl font-bold text-[#0D47A1]">
                        {{ $appName }}
                    </h1>
                    <p class="text-sm text-gray-500">
                        {{ $appPt }}
                    </p>
                </div>

                {{-- Divider mobile --}}
                <div class="md:hidden flex items-center gap-2 mb-6">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-xs text-gray-400">Login</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                {{-- Title --}}
                <h2 class="text-2xl font-semibold text-gray-800">
                    Masuk ke Akun
                </h2>
                <p class="text-sm text-gray-500 mt-1 mb-6">
                    Gunakan akun yang telah terdaftar
                </p>

                {{-- Session Status --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}"
                      class="space-y-4 sm:space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input
                            id="email"
                            class="block mt-1 w-full"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <x-input-label for="password" value="Password" />
                        <x-text-input
                            id="password"
                            class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required
                        />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Remember & Forgot --}}
                    <div class="flex items-center justify-between text-sm">
                        <label class="inline-flex items-center">
                            <input
                                type="checkbox"
                                name="remember"
                                class="rounded border-gray-300
                                       text-[#0D47A1]
                                       focus:ring-[#0D47A1]"
                            >
                            <span class="ms-2 text-gray-600">
                                Remember me
                            </span>
                        </label>

                        @if (Route::has('password.request'))
                            <a
                                href="{{ route('password.request') }}"
                                class="text-[#0D47A1] hover:underline font-medium"
                            >
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    {{-- Button --}}
                    <x-primary-button
                        class="w-full justify-center
                               bg-[#0D47A1] hover:bg-[#08306B]">
                        Log in
                    </x-primary-button>

                    <p class="text-center text-sm text-gray-600 pt-3">
                        Belum punya akun? Pendaftaran dilakukan melalui admin ya 😊
                    </p>
                </form>
            </div>
        </div>

    </div>
<script>
document.querySelector('form').addEventListener('submit', function (e) {
    const email = document.querySelector('[name="email"]');
    if (!email.value) {
        e.preventDefault();
        alert('Email wajib diisi');
        email.focus();
    }
});
</script>
</x-guest-layout>
