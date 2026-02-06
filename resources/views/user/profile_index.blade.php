<x-appuser-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <span class="font-semibold text-[#0D1B2A]">Profil Saya</span>
    </x-slot>

    @php
        $fotoUser = $user->foto
            ? asset('storage/' . $user->foto)
            : asset('default-user.png');
    @endphp

    <div
        x-data="{
            openPasswordModal: false,
            showOld: false,
            showNew: false,
            showConfirm: false
        }"
        class="space-y-6 mb-6"
    >

        {{-- ================= PROFIL UTAMA ================= --}}
        <x-card class="relative text-center">
            <div class="flex flex-col items-center space-y-3">

                <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-blue-100 shadow">
                    <img src="{{ $fotoUser }}" alt="Foto {{ $user->name }}" class="w-full h-full object-cover">
                </div>

                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                    <span class="inline-block mt-1 px-4 py-1 text-sm rounded-full bg-blue-50 text-blue-700 font-semibold">
                        Peserta Magang
                    </span>
                </div>

                <x-user-button-link
                    href="{{ route('user.profile.edit') }}"
                    icon="heroicon-o-pencil"
                    class="absolute top-4 right-4"
                >
                    Edit
                </x-user-button-link>

            </div>
        </x-card>

        {{-- ================= DATA PRIBADI ================= --}}
        <x-card class="space-y-4">
            <h3 class="flex items-center gap-2 font-semibold text-lg text-gray-700">
                <x-heroicon-o-identification class="w-5 h-5 text-blue-600"/>
                Data Pribadi
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <x-show-item label="Nama Lengkap" :value="$user->name" />
                <x-show-item label="Email" :value="$user->email" />
                <x-show-item
                    label="Jenis Kelamin"
                    :value="$user->gender === 'L' ? 'Laki-laki' : ($user->gender === 'P' ? 'Perempuan' : '-')"
                />
                <x-show-item
                    label="Tanggal Lahir"
                    :value="optional($user->tgl_lahir)->format('d-m-Y') ?? '-'"
                />
                <x-show-item label="No. HP" :value="$user->no_hp ?? '-'" />
                <x-show-item label="Alamat" :value="$user->alamat ?? '-'" />
            </div>
        </x-card>

        {{-- ================= AKADEMIK ================= --}}
        @if($user->profile)
        <x-card class="space-y-4">
            <h3 class="flex items-center gap-2 font-semibold text-lg text-gray-700">
                <x-heroicon-o-academic-cap class="w-5 h-5 text-blue-600"/>
                Akademik
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <x-show-item label="Pendidikan" :value="$user->profile->pendidikan ?? '-'" />
                <x-show-item label="Tingkatan" :value="$user->profile->tingkatan ?? '-'" />
                <x-show-item label="Kelas / Semester" :value="$user->profile->kelas ?? '-'" />
                <x-show-item label="Jurusan" :value="$user->profile->jurusan ?? '-'" />
                <x-show-item label="Nomor Induk" :value="$user->profile->nomor_induk ?? '-'" />
            </div>
        </x-card>
        @endif

        {{-- ================= PENEMPATAN MAGANG ================= --}}
        @if($user->profile)
        <x-card class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="flex items-center gap-2 font-semibold text-lg text-gray-700">
                    <x-heroicon-o-rectangle-stack class="w-5 h-5 text-blue-600"/>
                    Penempatan Magang
                </h3>

                <span class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $user->profile->status_magang === 'Aktif'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-gray-200 text-gray-600' }}">
                    {{ $user->profile->status_magang }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <x-show-item
                    label="Bagian"
                    :value="optional($user->profile->bagian)->nama ?? '-'"
                />
                <x-show-item
                    label="Pembimbing"
                    :value="optional($user->profile->pembimbing->user)->name ?? '-'"
                />
                <x-show-item
                    label="Tanggal Masuk"
                    :value="optional($user->profile->tgl_masuk)->format('d-m-Y') ?? '-'"
                />
                <x-show-item
                    label="Tanggal Keluar"
                    :value="optional($user->profile->tgl_keluar)->format('d-m-Y') ?? '-'"
                />
                <x-show-item
                    label="Sisa Hari Magang"
                    :value="$user->profile->sisa_hari . ' hari'"
                />
            </div>
        </x-card>
        @endif

        {{-- ================= KEAMANAN AKUN ================= --}}
        <x-card class="space-y-4">
            <h3 class="flex items-center gap-2 font-semibold text-lg text-gray-700">
                <x-heroicon-o-lock-closed class="w-5 h-5 text-blue-600"/>
                Keamanan Akun
            </h3>

            <x-show-item label="Password" value="********" />

            <x-user-button
                variant="secondary"
                icon="heroicon-o-key"
                @click="openPasswordModal = true"
            >
                Ubah Password
            </x-user-button>
        </x-card>

        {{-- ================= MODAL PASSWORD ================= --}}
        <div
            x-show="openPasswordModal"
            x-transition.opacity
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60"
        >
            <x-card
                @click.away="openPasswordModal = false"
                class="w-full max-w-md space-y-6"
            >
                {{-- Header --}}
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Ubah Password
                    </h3>
                    <button @click="openPasswordModal = false">
                        <x-heroicon-o-x-mark class="w-5 h-5 text-gray-500"/>
                    </button>
                </div>

                {{-- FORM PASSWORD --}}
                <form
                    method="POST"
                    action="{{ route('user.password.update') }}"
                    x-ref="passwordForm"
                    @submit-password.window="$el.submit()"
                    class="space-y-4"
                >
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-sm text-gray-600">Password Lama</label>
                        <input type="password" name="current_password"
                            class="w-full mt-1 rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Password Baru</label>
                        <input type="password" name="password"
                            class="w-full mt-1 rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full mt-1 rounded-lg border-gray-300">
                    </div>

                    {{-- ACTION --}}
                    <div class="flex justify-end gap-2 pt-4 border-t">
                        <x-user-button
                            type="button"
                            variant="secondary"
                            @click="openPasswordModal = false"
                        >
                            Batal
                        </x-user-button>

                        <x-user-button
                            type="button"
                            @click="$dispatch('open-user-confirm', { id: 'confirm-password-update' })"
                        >
                            Simpan
                        </x-user-button>
                    </div>
                </form>
            </x-card>
        </div>

        <x-user-confirm-modal
            id="confirm-password-update"
            title="Ubah Password"
            message="Apakah Anda yakin ingin mengubah password akun?"
        >
            <x-user-button
                type="button"
                @click="
                    openPasswordModal = false;
                    $dispatch('close-user-confirm', { id: 'confirm-password-update' });
                    $dispatch('submit-password');
                    $dispatch('user-toast', {
                        title: 'Berhasil',
                        message: 'Password berhasil diubah'
                    });
                "
            >
                Ya, Simpan
            </x-user-button>
        </x-user-confirm-modal>

    </div>

</x-appuser-layout>
