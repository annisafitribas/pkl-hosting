<x-appadmin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-[#0D1B2A]">
            <span class="font-semibold">Pengguna</span>
        </div>
    </x-slot>

    <div class="container mx-auto"
        x-data="{
            role: '{{ old('role') }}',
            showPassword: false,
            showConfirm: false
        }"
        @select-change.window="
            if ($event.detail.name === 'role') {
                role = $event.detail.value
            }
        "
    >

        <div class="bg-white p-6 rounded-2xl shadow space-y-10">

            {{-- HEADER --}}
            <div class="text-center">
                <h2 class="text-2xl font-bold text-[#0D1B2A]">Tambah Pengguna</h2>
                <p class="text-sm text-gray-600 mt-1">Isi data sesuai role</p>
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

            <form action="{{ route('admin.pengguna.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-10">
                @csrf

                {{-- ROLE --}}
                <div class="space-y-2">
                    <x-input-label value="Role" />
                    <x-select-box
                        name="role"
                        :value="old('role')"
                        :options="[
                            'user' => 'Peserta Magang',
                            'admin' => 'Admin',
                            'pembimbing' => 'Pembimbing'
                        ]"
                        placeholder="Pilih Role"
                    />
                </div>

                {{-- DATA LOGIN --}}
                <div class="space-y-4">
                    <h3 class="font-bold text-lg">Data Login</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Nama Lengkap" />
                            <x-text-input name="name" value="{{ old('name') }}" class="w-full mt-1" placeholder="Contoh: User" />
                        </div>

                        <div>
                            <x-input-label value="Email" />
                            <x-text-input type="email" name="email" value="{{ old('email') }}" class="w-full mt-1" placeholder="Contoh: user@gmail.com" />
                        </div>

                        <div>
                            <x-input-label value="Password" />
                            <div class="relative mt-1">
                                <x-text-input
                                    x-bind:type="showPassword ? 'text' : 'password'"
                                    name="password"
                                    class="w-full pr-10"
                                />
                                <button type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-3 flex items-center text-gray-500">
                                    <x-heroicon-o-eye x-show="!showPassword" class="w-5 h-5"/>
                                    <x-heroicon-o-eye-slash x-show="showPassword" class="w-5 h-5"/>
                                </button>
                            </div>

                            {{-- helper text --}}
                            <p class="mt-1 text-xs text-gray-500">
                                Password minimal 6 karakter
                            </p>
                        </div>

                        <div>
                            <x-input-label value="Konfirmasi Password" />
                            <div class="relative mt-1">
                                <x-text-input
                                    x-bind:type="showConfirm ? 'text' : 'password'"
                                    name="password_confirmation"
                                    class="w-full pr-10"
                                />
                                <button type="button"
                                    @click="showConfirm = !showConfirm"
                                    class="absolute inset-y-0 right-3 flex items-center text-gray-500">
                                    <x-heroicon-o-eye x-show="!showConfirm" class="w-5 h-5"/>
                                    <x-heroicon-o-eye-slash x-show="showConfirm" class="w-5 h-5"/>
                                </button>
                            </div>

                            {{-- helper text --}}
                            <p class="mt-1 text-xs text-gray-500">
                                Minimal 6 karakter dan harus sama dengan password
                            </p>
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
                                :value="old('gender')"
                                :options="['L'=>'Laki-laki','P'=>'Perempuan']"
                                placeholder="Pilih"
                                class="mt-1"
                            />
                        </div>

                        <div>
                            <x-input-label value="Tanggal Lahir" />
                            <x-text-input type="date" name="tgl_lahir" value="{{ old('tgl_lahir') }}" class="w-full mt-1" />
                        </div>

                        <div>
                            <x-input-label value="Nomor HP" />
                            <x-text-input name="no_hp" value="{{ old('no_hp') }}" class="w-full mt-1" placeholder="Contoh: 6285828390740" />
                        </div>

                        <div>
                            <x-input-label value="Foto" />
                            <x-text-input type="file" name="foto" class="w-full mt-1" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label value="Alamat Lengkap" />
                            <textarea name="alamat" rows="3"
                                class="w-full mt-1 border-gray-300 rounded-lg" placeholder="Cobtoh: Jl. Panglima Batur" >{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- DATA PEMBIMBING --}}
                <div x-show="role === 'pembimbing'" x-cloak class="space-y-4">
                    <h3 class="font-bold text-lg">Data Pembimbing</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="NIP" />
                            <x-text-input name="pembimbing[nip]" value="{{ old('pembimbing.nip') }}" class="w-full mt-1" placeholder="Contoh: 45124584" />
                        </div>

                        <div>
                            <x-input-label value="Jabatan" />
                            <x-text-input name="pembimbing[jabatan]" value="{{ old('pembimbing.jabatan') }}" class="w-full mt-1" placeholder="Contoh: Leader" />
                        </div>

                        <div>
                            <x-input-label value="Bagian" />
                            <x-select-box
                                name="pembimbing[bagian_id]"
                                :value="old('pembimbing.bagian_id')"
                                :options="$bagians->pluck('nama','id')->toArray()"
                                placeholder="Pilih Bagian"
                                class="mt-1"
                            />
                        </div>

                    </div>
                </div>

                {{-- DATA AKADEMIK --}}
                <div x-show="role === 'user'" x-cloak class="space-y-4">
                    <h3 class="font-bold text-lg">Data Akademik</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><x-input-label value="Nomor Induk" /><x-text-input name="user[nomor_induk]" value="{{ old('user.nomor_induk') }}" class="w-full mt-1" placeholder="Contoh: 2301301023" /></div>
                        <div><x-input-label value="Tingkatan" /><x-text-input name="user[tingkatan]" value="{{ old('user.tingkatan') }}" class="w-full mt-1" placeholder="Contoh: SMA/SMK/S1/D3.." /></div>
                        <div><x-input-label value="Nama/Asal Pendidikan" /><x-text-input name="user[pendidikan]" value="{{ old('user.pendidikan') }}" class="w-full mt-1" placeholder="Politeknik Negeri Tanah Laut " /></div>
                        <div><x-input-label value="Kelas / Semester" /><x-text-input name="user[kelas]" value="{{ old('user.kelas') }}" class="w-full mt-1" placeholder="Contoh: 5" /></div>
                        <div><x-input-label value="Jurusan" /><x-text-input name="user[jurusan]" value="{{ old('user.jurusan') }}" class="w-full mt-1" placeholder="Komputer dan Bisnis" /></div>

                    </div>
                </div>

                {{-- PENEMPATAN MAGANG --}}
                <div x-show="role === 'user'" x-cloak class="space-y-4">
                    <h3 class="font-bold text-lg">Penempatan Magang</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Bagian Magang" />
                            <x-select-box
                                name="user[bagian_id]"
                                :value="old('user.bagian_id')"
                                :options="$bagians->pluck('nama','id')->toArray()"
                                placeholder="Pilih Bagian"
                                class="mt-1"
                            />
                        </div>

                        <div>
                            <x-input-label value="Pembimbing" />
                            <x-select-box
                                name="user[pembimbing_id]"
                                :value="old('user.pembimbing_id')"
                                :options="$pembimbings->mapWithKeys(fn($p) => [$p->id => $p->user->name])->toArray()"
                                placeholder="Pilih Pembimbing"
                                class="mt-1"
                            />
                        </div>

                        <div><x-input-label value="Tanggal Masuk" /><x-text-input type="date" name="user[tgl_masuk]" value="{{ old('user.tgl_masuk') }}" class="w-full mt-1" /></div>
                        <div><x-input-label value="Tanggal Keluar" /><x-text-input type="date" name="user[tgl_keluar]" value="{{ old('user.tgl_keluar') }}" class="w-full mt-1" /></div>
                    </div>
                </div>

                {{-- AKSI (GLOBAL – SEMUA ROLE) --}}
                <div class="flex justify-end gap-3 pt-6 border-t">
                    <x-button-link href="{{ route('admin.pengguna.index') }}" variant="secondary">
                        Batal
                    </x-button-link>

                    <x-button type="button" variant="primary"
                        @click="window.dispatchEvent(new CustomEvent('open-confirm', { detail: { id: 'create-user' }}))">
                        Simpan
                    </x-button>

                    <x-confirm-modal
                        id="create-user"
                        title="Tambah Pengguna"
                        message="Apakah Anda yakin ingin menambahkan pengguna baru?"
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
