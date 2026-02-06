<x-appadmin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#0D1B2A]">Edit Data Kantor</h2>
    </x-slot>

    <div class="container mx-auto">
        <x-card>
            @if ($errors->any())
                <x-info variant="danger">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-info>
            @endif

            <form action="{{ route('admin.kantors.update', $kantor->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- INFORMASI KANTOR --}}
                <div class="space-y-4">
                    <div>
                        <x-input-label value="Nama PT" />
                        <x-text-input name="nama_pt" class="w-full mt-1" :value="old('nama_pt', $kantor->nama_pt)" />
                        <x-input-error :messages="$errors->get('nama_pt')" />
                    </div>

                    <div>
                        <x-input-label value="Nama Kantor" />
                        <x-text-input name="nama_apk" class="w-full mt-1" :value="old('nama_apk', $kantor->nama_apk)" />
                        <x-input-error :messages="$errors->get('nama_apk')" />
                    </div>

                    <div>
                        <x-input-label value="Logo" />
                        @if ($kantor->logo)
                            <img src="{{ asset('storage/'.$kantor->logo) }}" class="h-20 mb-2 rounded border">
                        @endif
                        <x-text-input type="file" name="logo" class="w-full mt-1" />
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti logo</p>
                        <x-input-error :messages="$errors->get('logo')" />
                    </div>

                    <div>
                        <x-input-label value="Alamat" />
                        <textarea name="alamat" rows="3" class="w-full mt-1 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">{{ old('alamat', $kantor->alamat) }}</textarea>
                        <x-input-error :messages="$errors->get('alamat')" />
                    </div>

                    <div>
                        <x-input-label value="Link Google Maps" />
                        <x-text-input name="link_maps" class="w-full mt-1" :value="old('link_maps', $kantor->link_maps)" />
                        <x-input-error :messages="$errors->get('link_maps')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label value="Latitude Kantor" />
                            <x-text-input type="number" step="any" name="kantor_lat" class="w-full mt-1" :value="old('kantor_lat', $kantor->kantor_lat)" />
                            <x-input-error :messages="$errors->get('kantor_lat')" />
                        </div>

                        <div>
                            <x-input-label value="Longitude Kantor" />
                            <x-text-input type="number" step="any" name="kantor_lng" class="w-full mt-1" :value="old('kantor_lng', $kantor->kantor_lng)" />
                            <x-input-error :messages="$errors->get('kantor_lng')" />
                        </div>

                        <div>
                            <x-input-label value="Radius Absen (meter)" />
                            <x-text-input type="number" name="radius_absen" class="w-full mt-1" :value="old('radius_absen', $kantor->radius_absen ?? 100)" />
                            <x-input-error :messages="$errors->get('radius_absen')" />
                        </div>
                    </div>
                </div>

                {{-- HARI KERJA --}}
                @php
                    $days = ['senin','selasa','rabu','kamis','jumat','sabtu','minggu'];
                    $selected = old('hari_kerja', is_array($kantor->hari_kerja) ? $kantor->hari_kerja : (json_decode($kantor->hari_kerja, true) ?? []));
                @endphp

                <div>
                    <x-input-label value="Hari Kerja" />
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-2">
                        @foreach ($days as $day)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="hari_kerja[]" value="{{ $day }}" {{ in_array($day, $selected) ? 'checked' : '' }}>
                                {{ ucfirst($day) }}
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('hari_kerja')" />
                </div>

                {{-- JAM OPERASIONAL --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label value="Jam Masuk" />
                        <x-text-input type="time" name="jam_masuk" class="w-full mt-1" :value="old('jam_masuk', $kantor->jam_masuk ? substr($kantor->jam_masuk, 0, 5) : '')" />
                        <x-input-error :messages="$errors->get('jam_masuk')" />
                    </div>

                    <div>
                        <x-input-label value="Jam Keluar" />
                        <x-text-input type="time" name="jam_keluar" class="w-full mt-1" :value="old('jam_keluar', $kantor->jam_keluar ? substr($kantor->jam_keluar, 0, 5) : '')" />
                        <x-input-error :messages="$errors->get('jam_keluar')" />
                    </div>
                </div>

                {{-- KONTAK --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label value="WhatsApp" />
                        <x-text-input name="wa_link" class="w-full mt-1" :value="old('wa_link', $kantor->wa_link)" />
                        <x-input-error :messages="$errors->get('wa_link')" />
                    </div>

                    <div>
                        <x-input-label value="Instagram" />
                        <x-text-input name="ig_link" class="w-full mt-1" :value="old('ig_link', $kantor->ig_link)" />
                        <x-input-error :messages="$errors->get('ig_link')" />
                    </div>
                </div>

                {{-- AKSI --}}
                <div class="flex justify-end gap-3 pt-4">
                    <x-button-link href="{{ route('admin.kantors.index') }}" variant="secondary">Batal</x-button-link>
                    <x-button type="button" variant="primary" @click="window.dispatchEvent(
                        new CustomEvent('open-confirm', {detail: { id: 'update-kantor-{{ $kantor->id }}' }}) )">
                        Simpan
                    </x-button>

                    <x-confirm-modal id="update-kantor-{{ $kantor->id }}" title="Simpan Perubahan"
                        message="Apakah Anda yakin ingin menyimpan perubahan pada kantor '{{ $kantor->nama_apk }}'?" variant="primary">
                        <x-button type="submit" variant="primary">
                            Ya, Simpan
                        </x-button>
                    </x-confirm-modal>
                </div>
            </form>
        </x-card>
    </div>
</x-appadmin-layout>