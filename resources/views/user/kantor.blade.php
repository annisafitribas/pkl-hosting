<x-appuser-layout>
    <x-slot name="header">
        <span class="font-semibold text-[#0D1B2A]">Tentang</span>
    </x-slot>

    <div class="space-y-6 mb-6">

        @php
            $kantor = $kantors->first();
        @endphp

        @if ($kantor)

            {{-- ================= HEADER KANTOR ================= --}}
            <x-card>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-blue-50 flex items-center justify-center overflow-hidden border">
                        @if ($kantor->logo)
                            <img
                                src="{{ asset('storage/'.$kantor->logo) }}"
                                class="w-full h-full object-cover"
                            >
                        @else
                            <x-heroicon-o-building-office class="w-7 h-7 text-blue-600" />
                        @endif
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-gray-800">
                            {{ $kantor->nama_apk ?? 'Nama Kantor Belum Diatur' }}
                        </h2>
                        <p class="text-sm text-gray-500">
                            Informasi operasional kantor
                        </p>
                    </div>
                </div>
            </x-card>

            {{-- ================= JAM OPERASIONAL ================= --}}
            <x-card class="space-y-4">
                <h3 class="flex items-center gap-2 font-semibold text-lg text-gray-700">
                    <x-heroicon-o-clock class="w-5 h-5 text-blue-600" />
                    Jam Operasional
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    <x-show-item
                        label="Hari Kerja"
                        :value="is_array($kantor->hari_kerja) ? implode(', ', $kantor->hari_kerja) : '-'"
                    />

                    <x-show-item
                        label="Jam Kerja"
                        :value="(
                            ($kantor?->jam_masuk ? \Carbon\Carbon::parse($kantor->jam_masuk)->format('H:i') : '07:45')
                            .' - '.
                            ($kantor?->jam_keluar ? \Carbon\Carbon::parse($kantor->jam_keluar)->format('H:i') : '17:00')
                            .' WITA'
                        )"
                    />
                </div>
            </x-card>

            {{-- ================= IDENTITAS KANTOR ================= --}}
            <x-card class="space-y-4">
                <h3 class="flex items-center gap-2 font-semibold text-lg text-gray-700">
                    <x-heroicon-o-identification class="w-5 h-5 text-blue-600" />
                    Identitas Kantor
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    <x-show-item label="Alamat" :value="$kantor->alamat ?? '-'" />
                    <x-show-item
                        label="Koordinat"
                        :value="($kantor->kantor_lat && $kantor->kantor_lng)
                            ? $kantor->kantor_lat.', '.$kantor->kantor_lng
                            : '-'"
                    />
                    <x-show-item
                        label="Radius Absen"
                        :value="$kantor->radius_absen ? $kantor->radius_absen.' meter' : '-'"
                    />
                </div>
            </x-card>

            {{-- ================= LOKASI & KONTAK ================= --}}
            <x-card class="space-y-4">
                <h3 class="flex items-center gap-2 font-semibold text-lg text-gray-700">
                    <x-heroicon-o-map class="w-5 h-5 text-blue-600" />
                    Lokasi & Kontak
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">

                    <x-show-item label="Google Maps">
                        @if ($kantor->link_maps)
                            <x-user-button-link
                                href="{{ $kantor->link_maps }}"
                                target="_blank"
                                variant="secondary"
                            >
                                Lihat Lokasi
                            </x-user-button-link>
                        @else
                            -
                        @endif
                    </x-show-item>

                    <x-show-item label="WhatsApp">
                        @if ($kantor->wa_link)
                            <x-user-button-link
                                href="{{ $kantor->wa_link }}"
                                target="_blank"
                                variant="secondary"
                            >
                                Hubungi
                            </x-user-button-link>
                        @else
                            -
                        @endif
                    </x-show-item>

                    <x-show-item label="Instagram">
                        @if ($kantor->ig_link)
                            <x-user-button-link
                                href="{{ $kantor->ig_link }}"
                                target="_blank"
                                variant="secondary"
                            >
                                {{ '@'.basename($kantor->ig_link) }}
                            </x-user-button-link>
                        @else
                            -
                        @endif
                    </x-show-item>

                </div>
            </x-card>

        @else

            {{-- ================= EMPTY STATE ================= --}}
            <x-card class="text-center py-10">
                <x-heroicon-o-building-office class="w-14 h-14 text-gray-300 mx-auto mb-4" />
                <p class="text-gray-500 text-sm">
                    Informasi kantor belum tersedia.<br>
                    Silakan hubungi admin untuk detail lebih lanjut.
                </p>
            </x-card>

        @endif

    </div>
</x-appuser-layout>
