<x-appadmin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-[#0D1B2A]">
            <span class="font-semibold">Profil</span>
        </div>
    </x-slot>

@php
    $admin = auth()->user();

    $fotoAdmin = $admin->foto
        ? asset('storage/'.$admin->foto)
        : asset('default-user.png');
@endphp

<div class="container mx-auto"
    x-data="{
        showPassword: false,
        showConfirm: false,
        fotoPreview: '{{ $fotoAdmin }}'
    }"
>

<div class="bg-white p-6 rounded-2xl shadow space-y-6">

    {{-- HEADER --}}
    <div class="text-center">
        <h2 class="text-2xl font-bold text-[#0D1B2A]">Edit Profil Admin</h2>
        <p class="text-sm text-gray-600 mt-1">Perbarui data akun Anda</p>
    </div>

    {{-- FOTO PROFIL --}}
    <div class="flex flex-col items-center">
        <div class="relative w-32 h-32">
            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-300 shadow">
                <img :src="fotoPreview" class="w-full h-full object-cover">
            </div>

            <label
                class="absolute bottom-1 right-1
                       bg-[#0D1B2A] text-white
                       p-2 rounded-full cursor-pointer
                       shadow hover:bg-black transition">
                <x-heroicon-o-camera class="w-5 h-5"/>

                <input
                    type="file"
                    name="foto"
                    form="form-profile"
                    accept="image/*"
                    class="hidden"
                    @change="fotoPreview = URL.createObjectURL($event.target.files[0])"
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

    {{-- FORM --}}
    <form id="form-profile"
          method="POST"
          action="{{ route('admin.profile.update') }}"
          enctype="multipart/form-data"
          class="space-y-10">
        @csrf
        @method('PUT')

        {{-- DATA LOGIN --}}
        <div class="space-y-4">
            <h3 class="font-bold text-lg">Data Login</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label value="Nama Lengkap" />
                    <x-text-input
                        name="name"
                        value="{{ old('name', $admin->name) }}"
                        class="w-full mt-1" />
                </div>

                <div>
                    <x-input-label value="Username" />
                    <x-text-input
                        type="email"
                        name="email"
                        value="{{ old('email', $admin->email) }}"
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
                            class="absolute inset-y-0 right-3 text-gray-500">
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
                            placeholder="Ulangi perubahan password" />
                        <button type="button"
                            @click="showConfirm = !showConfirm"
                            class="absolute inset-y-0 right-3 text-gray-500">
                            <x-heroicon-o-eye x-show="!showConfirm" class="w-5 h-5"/>
                            <x-heroicon-o-eye-slash x-show="showConfirm" class="w-5 h-5"/>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- DATA PRIBADI --}}
        <div class="space-y-4">
            <h3 class="font-bold text-lg">Data Pribadi</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label value="Jenis Kelamin" />
                    <x-select-box
                        name="gender"
                        :value="old('gender', $admin->gender)"
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
                        value="{{ old('tgl_lahir', optional($admin->tgl_lahir)->format('Y-m-d')) }}"
                        class="w-full mt-1" />
                </div>

                <div>
                    <x-input-label value="Nomor HP" />
                    <x-text-input
                        name="no_hp"
                        value="{{ old('no_hp', $admin->no_hp) }}"
                        class="w-full mt-1" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label value="Alamat" />
                    <textarea name="alamat" rows="3"
                        class="w-full mt-1 border-gray-300 rounded-lg">{{ old('alamat', $admin->alamat) }}</textarea>
                </div>
            </div>
        </div>

        {{-- AKSI --}}
        <div class="flex justify-end gap-3 pt-6 border-t">
            <x-button-link href="{{ route('admin.profile.show') }}" variant="secondary">
                Batal
            </x-button-link>

            <x-button type="button" variant="primary"
                @click="window.dispatchEvent(
                    new CustomEvent('open-confirm', { detail: { id: 'update-profile' } })
                )">
                Simpan Perubahan
            </x-button>

            <x-confirm-modal
                id="update-profile"
                title="Simpan Perubahan"
                message="Apakah Anda yakin ingin menyimpan perubahan profil admin bernama {{ $admin->name }}?"
                variant="primary"
            >
                <x-button type="submit" variant="primary">
                    Ya, Simpan
                </x-button>
            </x-confirm-modal>
        </div>

    </form>
</div>
</div>
</x-appadmin-layout>
