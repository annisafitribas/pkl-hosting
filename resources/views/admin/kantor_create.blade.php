<x-appadmin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-[#0D1B2A]">
            <span class="font-semibold">Kantor</span>
        </div>
    </x-slot>

    <div class="container mx-auto">
        <x-card>
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-[#0D1B2A]">Tambah Data Kantor</h2>
                <p class="text-sm text-gray-600 mt-1">Masukkan data kantor dengan lengkap</p>
            </div>

            {{-- ERROR VALIDASI --}}
            @if ($errors->any())
                <x-info variant="danger">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-info>
            @endif

            <form action="{{ route('admin.kantors.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- INFORMASI KANTOR --}}
                <x-card title="Informasi Kantor">
                    <div class="space-y-4">
                        <div>
                            <x-input-label value="Nama Kantor" />
                            <x-text-input name="nama_pt" class="w-full mt-1" placeholder="Contoh: PT PLN (Persero) ULP Banjarbaru" />
                            <x-input-error :messages="$errors->get('nama_pt')" />
                        </div>
                        
                        <div>
                            <x-input-label value="Nama Aplikasi" />
                            <x-text-input name="nama_apk" class="w-full mt-1" placeholder="Contoh: APM" /> 
                            <x-input-error :messages="$errors->get('nama_apk')" />
                        </div>

                        <div>
                            <x-input-label value="Logo Kantor" />
                            <x-text-input type="file" name="logo" class="w-full mt-1" />
                            <p class="text-xs text-gray-500 mt-1">Format JPG / PNG, maksimal 2MB</p>
                            <x-input-error :messages="$errors->get('logo')" />
                        </div>

                        <div>
                            <x-input-label value="Alamat" />
                            <textarea name="alamat" rows="3"
                                class="w-full mt-1 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Contoh: Jl. Ahmad Yani No. 12" ></textarea>
                            <x-input-error :messages="$errors->get('alamat')" />
                        </div>

                        <div>
                            <x-input-label value="Link Google Maps" />
                            <x-text-input name="link_maps" class="w-full mt-1"
                                placeholder="Contoh: https://maps.google.com/?q=-6.200000,106.816666" />
                            <x-input-error :messages="$errors->get('link_maps')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label value="Latitude Kantor" />
                                <x-text-input type="number" step="any" name="kantor_lat" class="w-full mt-1"
                                    placeholder="Contoh: -3.44173652" />
                                <x-input-error :messages="$errors->get('kantor_lat')" />
                            </div>

                            <div>
                                <x-input-label value="Longitude Kantor" />
                                <x-text-input type="number" step="any" name="kantor_lng" class="w-full mt-1"
                                    placeholder="Contoh: 114.83029841" />
                                <x-input-error :messages="$errors->get('kantor_lng')" />
                            </div>

                            <div>
                                <x-input-label value="Radius Absen (meter)" />
                                <x-text-input type="number" name="radius_absen" class="w-full mt-1"
                                    placeholder="Contoh: 100" />
                                <x-input-error :messages="$errors->get('radius_absen')" />
                            </div>
                        </div>
                    </div>
                </x-card>

                {{-- HARI KERJA --}}
                @php $days = ['senin','selasa','rabu','kamis','jumat','sabtu','minggu']; @endphp
                <x-card title="Hari Kerja">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($days as $day)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="hari_kerja[]" value="{{ $day }}">
                                {{ ucfirst($day) }}
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('hari_kerja')" />
                </x-card>

                {{-- JAM OPERASIONAL --}}
                <x-card title="Jam Operasional">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label value="Jam Masuk" />
                            <x-text-input type="time" name="jam_masuk" class="w-full mt-1" placeholder="07:45" />
                            <x-input-error :messages="$errors->get('jam_masuk')" />
                        </div>

                        <div>
                            <x-input-label value="Jam Keluar" />
                            <x-text-input type="time" name="jam_keluar" class="w-full mt-1" placeholder="17:00" />
                            <x-input-error :messages="$errors->get('jam_keluar')" />
                        </div>
                    </div>
                </x-card>

                {{-- KONTAK & SOSIAL MEDIA --}}
                <x-card title="Kontak & Sosial Media">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label value="WhatsApp" />
                            <x-text-input name="wa_link" class="w-full mt-1"
                                placeholder="https://wa.me/628123456789" />
                            <x-input-error :messages="$errors->get('wa_link')" />
                        </div>

                        <div>
                            <x-input-label value="Instagram" />
                            <x-text-input name="ig_link" class="w-full mt-1"
                                placeholder="https://instagram.com/apm_office" />
                            <x-input-error :messages="$errors->get('ig_link')" />
                        </div>
                    </div>
                </x-card>

                {{-- AKSI --}}
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <x-button-link href="{{ route('admin.kantors.index') }}" variant="secondary">Batal</x-button-link>
                    <x-button type="button" variant="primary"
                        @click="window.dispatchEvent(new CustomEvent('open-confirm', { detail: { id: 'create-kantor' }}))">
                        Simpan
                    </x-button>
                    <x-confirm-modal id="create-kantor" title="Tambah Konfigurasi" message="Apakah Anda yakin ingin menambahkan konfigurasi?" variant="primary">
                        <x-button type="submit" variant="primary">
                            Ya, Simpan
                        </x-button>
                    </x-confirm-modal>
                </div>
                </div>
            </form>
        </x-card>
    </div>
</x-appadmin-layout>