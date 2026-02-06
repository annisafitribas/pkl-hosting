<x-appuser-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <span class="font-semibold text-[#0D1B2A]">Edit Profil</span>
    </x-slot>

    {{-- USER TOAST --}}
    <x-user-toast />

    @php
        $fotoUser = $user->foto
            ? asset('storage/'.$user->foto)
            : asset('default-user.png');
    @endphp

    <div
        x-data="{
            showPassword: false,
            fotoPreview: '{{ $fotoUser }}'
        }"
        class="space-y-6"
    >

        <x-card class="p-6 md:p-8 space-y-10">

            <form
                method="POST"
                action="{{ route('user.profile.update') }}"
                enctype="multipart/form-data"
                x-ref="form"
                @submit-profile.window="$el.submit()"
                class="space-y-10"
            >

                @csrf
                @method('PUT')

                {{-- ================= FOTO PROFIL ================= --}}
                <section class="flex flex-col items-center space-y-3">
                    <div class="relative">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-blue-100 shadow">
                            <img :src="fotoPreview" class="w-full h-full object-cover">
                        </div>

                        <label
                            class="absolute bottom-1 right-1 bg-[#123B6E] hover:bg-[#0F325C]
                                   text-white p-2 rounded-full cursor-pointer shadow transition"
                        >
                            <x-heroicon-o-camera class="w-5 h-5"/>
                            <input
                                type="file"
                                name="foto"
                                class="hidden"
                                @change="
                                    const file = $event.target.files[0];
                                    if(file) fotoPreview = URL.createObjectURL(file);
                                "
                            >
                        </label>
                    </div>
                    <p class="text-sm text-gray-500">Klik ikon kamera untuk mengganti foto</p>
                </section>

                {{-- ================= DATA PRIBADI ================= --}}
                <section class="space-y-4">
                    <h3 class="flex items-center gap-2 font-semibold text-lg text-gray-700 border-b pb-2">
                        <x-heroicon-o-identification class="w-5 h-5 text-[#123B6E]"/>
                        Data Pribadi
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" name="name"
                                value="{{ old('name', $user->name) }}"
                                class="mt-1 w-full rounded-lg border-gray-300 px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email"
                                value="{{ old('email', $user->email) }}"
                                class="mt-1 w-full rounded-lg border-gray-300 px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                            <select name="gender"
                                class="mt-1 w-full rounded-lg border-gray-300 px-3 py-2">
                                <option value="">- Pilih -</option>
                                <option value="L" {{ old('gender', $user->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender', $user->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir"
                                value="{{ old('tgl_lahir', optional($user->tgl_lahir)->format('Y-m-d')) }}"
                                class="mt-1 w-full rounded-lg border-gray-300 px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">No. HP</label>
                            <input type="text" name="no_hp"
                                value="{{ old('no_hp', $user->no_hp) }}"
                                class="mt-1 w-full rounded-lg border-gray-300 px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat</label>
                            <input type="text" name="alamat"
                                value="{{ old('alamat', $user->alamat) }}"
                                class="mt-1 w-full rounded-lg border-gray-300 px-3 py-2">
                        </div>

                    </div>
                </section>

                {{-- ================= ACTION ================= --}}
                <div class="flex justify-end gap-3 pt-6 border-t">
                    <x-user-button-link
                        href="{{ route('user.profile.index') }}"
                        variant="secondary"
                    >
                        Batal
                    </x-user-button-link>

<x-user-button
    type="button"
    @click="$dispatch('open-user-confirm', { id: 'confirm-update-profile' })"
>
    Simpan Perubahan
</x-user-button>

                </div>
                
<x-user-confirm-modal
    id="confirm-update-profile"
    title="Simpan Perubahan"
    message="Apakah Anda yakin ingin menyimpan perubahan profil?"
>
    <x-user-button
        type="button"
        @click="
            $dispatch('close-user-confirm', { id: 'confirm-update-profile' });
            $dispatch('submit-profile');
            $dispatch('user-toast', {
                title: 'Berhasil',
                message: 'Profil berhasil diperbarui'
            });
        "
    >
        Ya, Simpan
    </x-user-button>
</x-user-confirm-modal>

            </form>

        </x-card>

    </div>

</x-appuser-layout>
