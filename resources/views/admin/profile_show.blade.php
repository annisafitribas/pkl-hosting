<x-appadmin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-[#0D1B2A]">
            <span class="font-semibold">Profil</span>
        </div>
    </x-slot>

    @php
        $admin = auth()->user();

        $fotoAdmin = $admin->foto
            ? asset('storage/' . $admin->foto)
            : asset('default-user.png');
    @endphp

    <div class="bg-white p-8 rounded-2xl shadow border space-y-8">

        {{-- ================= HEADER PROFIL ================= --}}
        <div class="flex flex-col items-center text-center space-y-3">
            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-300 shadow">
                <img
                    src="{{ $fotoAdmin }}"
                    alt="Foto {{ $admin->name }}"
                    onerror="this.src='{{ asset('default-user.png') }}'"
                    class="w-full h-full object-cover"
                >
            </div>

            <div>
                <h2 class="text-xl font-bold text-[#0D1B2A]">
                    {{ $admin->name }}
                </h2>

                <span class="inline-block mt-1 px-4 py-1 text-sm rounded-full bg-gray-200 font-semibold">
                    Admin
                </span>
            </div>
        </div>

        {{-- ================= DATA PROFIL ================= --}}
        <section class="space-y-4">
            <h3 class="flex items-center gap-2 font-semibold text-lg border-b pb-2">
                <x-heroicon-o-user-circle class="w-6 h-6"/>
                Data Profil Admin
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-show-item
                    label="Username"
                    :value="$admin->email"
                />

                <x-show-item
                    label="Jenis Kelamin"
                    :value="$admin->gender === 'L'
                        ? 'Laki-laki'
                        : ($admin->gender === 'P' ? 'Perempuan' : '-')"
                />

                <x-show-item
                    label="Tanggal Lahir"
                    :value="optional($admin->tgl_lahir)->format('d-m-Y') ?? '-'"
                />

                <x-show-item
                    label="Nomor HP"
                    :value="$admin->no_hp ?? '-'"
                />

                <x-show-item
                    label="Alamat"
                    :value="$admin->alamat ?? '-'"
                    full
                />
            </div>
        </section>

        {{-- ================= AKSI ================= --}}
        <div class="flex justify-end gap-3 pt-6 border-t">
            <x-button-link
                href="{{ route('admin.profile.edit') }}"
                variant="primary"
                icon="heroicon-o-pencil-square"
            >
                Edit Profil
            </x-button-link>
        </div>

    </div>
</x-appadmin-layout>
