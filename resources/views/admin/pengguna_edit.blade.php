<x-appadmin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-[#0D1B2A]">
            <span class="font-semibold">Pengguna</span>
        </div>
    </x-slot>

@php
    $fotoUser = $user->foto
        ? asset('storage/'.$user->foto)
        : asset('default-user.png');
@endphp

<div class="container mx-auto"
    x-data="{
        role: '{{ $user->role }}',
        showPassword: false,
        showConfirm: false,

        fotoPreview: '{{ $fotoUser }}'
    }"
>

<div class="bg-white p-6 rounded-2xl shadow space-y-6">
    {{-- HEADER --}}
    <div class="text-center">
        <h2 class="text-2xl font-bold text-[#0D1B2A]">Edit Pengguna</h2>
        <p class="text-sm text-gray-600 mt-1">Perbarui data pengguna</p>
    </div>

{{-- ================= FOTO PROFIL ================= --}}
<div class="flex flex-col items-center text-center ">
    <div class="relative w-32 h-32">
        {{-- FOTO --}}
        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-300 shadow">
            <img
                :src="fotoPreview"
                alt="Foto {{ $user->name }}"
                class="w-full h-full object-cover"
            >
        </div>

        {{-- IKON KAMERA --}}
        <label
            class="absolute bottom-1 right-1
                   bg-[#0D1B2A] text-white
                   p-2 rounded-full cursor-pointer
                   shadow hover:bg-black transition">
            <x-heroicon-o-camera class="w-5 h-5"/>

            {{-- INPUT FILE (HIDDEN) --}}
            <input
                type="file"
                name="foto"
                accept="image/*"
                class="hidden"
                @change="
                    const file = $event.target.files[0];
                    if (file) fotoPreview = URL.createObjectURL(file);
                "
            >
        </label>
    </div>
</div>

    {{-- ERROR --}}
    @if ($errors->any())
        <x-info variant="danger">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-info>
    @endif

    <form action="{{ route('admin.pengguna.update', $user->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-10">
        @csrf
        @method('PUT')

        {{-- ================= ROLE (LOCKED) ================= --}}
        <div class="space-y-2">
            <x-input-label value="Role" />
            <input type="text"
                   value="{{ ucfirst($user->role) }}"
                   class="w-full mt-1 border-gray-300 rounded-lg bg-gray-100"
                   disabled>
            <input type="hidden" name="role" value="{{ $user->role }}">
        </div>

        {{-- ================= DATA LOGIN ================= --}}
        <div class="space-y-4">
            <h3 class="font-bold text-lg">Data Login</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label value="Nama Lengkap" />
                    <x-text-input
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full mt-1" />
                </div>

                <div>
                    <x-input-label value="Email" />
                    <x-text-input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full mt-1" />
                </div>

                <div>
                    <x-input-label value="Password Baru" />
                    <div class="relative mt-1">
                        <x-text-input
                            x-bind:type="showPassword ? 'text' : 'password'"
                            name="password"
                            class="w-full pr-10"
                            placeholder="Kosongkan jika tidak diubah" />
                        <button type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500">
                            <x-heroicon-o-eye x-show="!showPassword" class="w-5 h-5"/>
                            <x-heroicon-o-eye-slash x-show="showPassword" class="w-5 h-5"/>
                        </button>
                    </div>
                </div>

<div>
    <x-input-label value="Konfirmasi Password" />
    <div class="relative mt-1">
        <x-text-input
            x-bind:type="showConfirm ? 'text' : 'password'"
            name="password_confirmation"
            class="w-full pr-10"
            placeholder="Kosongkan jika tidak diubah"
        />
        <button type="button"
            @click="showConfirm = !showConfirm"
            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">
            <x-heroicon-o-eye
                x-show="!showConfirm"
                class="w-5 h-5"
            />
            <x-heroicon-o-eye-slash
                x-show="showConfirm"
                class="w-5 h-5"
            />
        </button>
    </div>
</div>

            </div>
        </div>

        {{-- ================= DATA PRIBADI ================= --}}
        <div class="space-y-4">
            <h3 class="font-bold text-lg">Data Pribadi</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label value="Jenis Kelamin" />
                    <x-select-box
                        name="gender"
                        :value="old('gender', $user->gender)"
                        :options="[
                            'L' => 'Laki-laki',
                            'P' => 'Perempuan'
                        ]"
                        placeholder="Pilih"
                        class="mt-1" />
                </div>

                <div>
                    <x-input-label value="Tanggal Lahir" />
                    <x-text-input
                        type="date"
                        name="tgl_lahir"
                        value="{{ old('tgl_lahir', optional($user->tgl_lahir)->format('Y-m-d')) }}"
                        class="w-full mt-1" />
                </div>

                <div>
                    <x-input-label value="Nomor HP" />
                    <x-text-input
                        name="no_hp"
                        value="{{ old('no_hp', $user->no_hp) }}"
                        class="w-full mt-1" />
                </div>

                <div>
                    <x-input-label value="Alamat Lengkap" />
                    <textarea name="alamat" rows="3"
                        class="w-full mt-1 border-gray-300 rounded-lg">{{ old('alamat', $user->alamat) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ================= PEMBIMBING ================= --}}
        <div x-show="role==='pembimbing'" x-cloak class="space-y-4">
            <h3 class="font-bold text-lg">Data Pembimbing</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label value="NIP" />
                    <x-text-input
                        name="pembimbing[nip]"
                        value="{{ old('pembimbing.nip', $user->pembimbingProfile->nip ?? '') }}"
                        class="w-full mt-1" />
                </div>

                <div>
                    <x-input-label value="Jabatan" />
                    <x-text-input
                        name="pembimbing[jabatan]"
                        value="{{ old('pembimbing.jabatan', $user->pembimbingProfile->jabatan ?? '') }}"
                        class="w-full mt-1" />
                </div>

                <div>
                    <x-input-label value="Bagian" />
                    <x-select-box
                        name="pembimbing[bagian_id]"
                        :value="old('pembimbing.bagian_id', $user->pembimbingProfile->bagian_id ?? '')"
                        :options="$bagians->pluck('nama_bagian','id')->toArray()"
                        placeholder="Pilih Bagian"
                        class="mt-1" />
                </div>
            </div>
        </div>

        {{-- ================= DATA AKADEMIK ================= --}}
        <div x-show="role==='user'" x-cloak class="space-y-4">
            <h3 class="font-bold text-lg">Data Akademik</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label value="Nomor Induk" />
                    <x-text-input
                        name="user[nomor_induk]"
                        value="{{ old('user.nomor_induk', $user->profile->nomor_induk ?? '') }}"
                        class="w-full mt-1" />
                </div>

                <div>
                    <x-input-label value="Tingkatan" />
                    <x-text-input
                        name="user[tingkatan]"
                        value="{{ old('user.tingkatan', $user->profile->tingkatan ?? '') }}"
                        class="w-full mt-1" />
                </div>

                <div>
                    <x-input-label value="Pendidikan" />
                    <x-text-input
                        name="user[pendidikan]"
                        value="{{ old('user.pendidikan', $user->profile->pendidikan ?? '') }}"
                        class="w-full mt-1" />
                </div>

                <div>
                    <x-input-label value="Kelas / Semester" />
                    <x-text-input
                        name="user[kelas]"
                        value="{{ old('user.kelas', $user->profile->kelas ?? '') }}"
                        class="w-full mt-1" />
                </div>

                <div>
                    <x-input-label value="Jurusan" />
                    <x-text-input
                        name="user[jurusan]"
                        value="{{ old('user.jurusan', $user->profile->jurusan ?? '') }}"
                        class="w-full mt-1" />
                </div>
            </div>
        </div>

        {{-- ================= PENEMPATAN MAGANG ================= --}}
        <div x-show="role==='user'" x-cloak class="space-y-4">
            <h3 class="font-bold text-lg">Penempatan Magang</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label value="Bagian Magang" />
                    <x-select-box
                        name="user[bagian_id]"
                        :value="old('user.bagian_id', $user->profile->bagian_id ?? '')"
                        :options="$bagians->pluck('nama','id')->toArray()"
                        placeholder="Pilih Bagian"
                        class="mt-1" />
                </div>

                <div>
                    <x-input-label value="Pembimbing" />
                    <x-select-box
                        name="user[pembimbing_id]"
                        :value="old('user.pembimbing_id', $user->profile->pembimbing_id ?? '')"
                        :options="$pembimbings->mapWithKeys(fn($p) => [$p->id => $p->user->name])->toArray()"
                        placeholder="Pilih Pembimbing"
                        class="mt-1" />
                </div>

                <div>
                    <x-input-label value="Tanggal Masuk" />
                    <x-text-input
                        type="date"
                        name="user[tgl_masuk]"
                        value="{{ old('user.tgl_masuk', optional($user->profile?->tgl_masuk)->format('Y-m-d')) }}"
                        class="w-full mt-1" />
                </div>

                <div>
                    <x-input-label value="Tanggal Keluar" />
                    <x-text-input
                        type="date"
                        name="user[tgl_keluar]"
                        value="{{ old('user.tgl_keluar', optional($user->profile?->tgl_keluar)->format('Y-m-d')) }}"
                        class="w-full mt-1" />
                </div>
            </div>
        </div>

        {{-- ================= AKSI ================= --}}
        <div class="flex justify-end gap-3 pt-6 border-t">
            <x-button-link href="{{ route('admin.pengguna.index') }}" variant="secondary">
                Batal
            </x-button-link>

            <x-button type="button" variant="primary"
                @click="window.dispatchEvent(new CustomEvent('open-confirm', { detail: { id: 'update-user' }}))">
                Simpan Perubahan
            </x-button>

            <x-confirm-modal
                id="update-user"
                title="Simpan Perubahan"
                message="Apakah Anda yakin ingin menyimpan perubahan data {{ $user->role }} atas nama {{ $user->name }}?"
                variant="primary">
                <x-button type="submit" variant="primary">
                    Ya, Simpan
                </x-button>
            </x-confirm-modal>

        </div>

    </form>
</div>
</div>
</x-appadmin-layout>
