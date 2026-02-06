<x-guest-layout>

    <div
        class="w-full max-w-md bg-white rounded-3xl shadow-2xl px-6 py-8 sm:px-8 sm:py-10">

        {{-- Title --}}
        <h2 class="text-lg font-semibold text-gray-800 text-center">
            Lupa Password
        </h2>
        <p class="text-sm text-gray-600 text-center mt-2 mb-6">
            Masukkan email terdaftar. Kami akan mengirimkan
            tautan untuk mengatur ulang password Anda.
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <!-- Email -->
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

            <!-- Action -->
            <div class="pt-2">
                <x-primary-button
                    class="w-full justify-center bg-[#0D47A1] hover:bg-[#08306B]">
                    Kirim Link Reset Password
                </x-primary-button>
            </div>
        </form>

        {{-- Back to login --}}
        <div class="mt-6 text-center">
            <a
                href="{{ route('login') }}"
                class="text-sm text-[#0D47A1] hover:underline font-medium"
            >
                ← Kembali ke halaman login
            </a>
        </div>

    </div>

</x-guest-layout>
