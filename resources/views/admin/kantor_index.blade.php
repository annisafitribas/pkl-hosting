<x-appadmin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-[#0D1B2A]">Kantor</div>
    </x-slot>

    <div class="container mx-auto">
        @php $kantor = $kantors->first(); @endphp

        <x-card>
            @if($kantor)
                <div class="flex items-start justify-between flex-wrap gap-4 mb-8">
                    <div class="flex items-center gap-4">
                        @if($kantor->logo)
                            <img src="{{ asset('storage/'.$kantor->logo) }}" class="h-12 w-12 object-contain rounded-lg border bg-white p-1">
                        @else
                            <x-heroicon-o-building-office class="w-10 h-10 text-indigo-600" />
                        @endif

                        <div>
                            <h2 class="text-xl font-bold">{{ $kantor->nama_apk ?? 'Nama Kantor Belum Diatur' }}</h2>
                            <p class="text-sm text-gray-500">Informasi & pengaturan operasional kantor</p>
                        </div>
                    </div>

                    <x-button-link href="{{ route('admin.kantors.edit', $kantor->id) }}" variant="primary" icon="heroicon-o-pencil-square">
                        Edit Kantor
                    </x-button-link>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <x-card title="Identitas Kantor" icon="heroicon-o-identification">
                        <x-info label="Nama PT" :value="$kantor->nama_pt ?? '-'" />
                        <x-info label="Nama Aplikasi " :value="$kantor->nama_apk ?? '-'" />
                        <x-info label="Alamat" :value="$kantor->alamat ?? '-'" />
                    </x-card>

                    <x-card title="Operasional" icon="heroicon-o-clock">
                        <x-info label="Hari Kerja" :value="is_array($kantor->hari_kerja) ? implode(', ', $kantor->hari_kerja) : '-'" />
                        <x-info label="Jam Kerja" :value="($kantor->jam_masuk && $kantor->jam_keluar) ? substr($kantor->jam_masuk,0,5).' – '.substr($kantor->jam_keluar,0,5) : '-'" />
                        <x-info label="Radius Absen" :value="$kantor->radius_absen ? $kantor->radius_absen.' meter' : '-'" />
                    </x-card>

                    <x-card title="Lokasi & Kontak" icon="heroicon-o-map">
                        <x-info label="Koordinat" :value="($kantor->kantor_lat && $kantor->kantor_lng) ? $kantor->kantor_lat.', '.$kantor->kantor_lng : '-'" />
                        <x-info label="Link Maps" :value="$kantor->link_maps ? 'Klik untuk melihat maps' : '-'" :link="$kantor->link_maps" />
                        <x-info label="WhatsApp" :value="$kantor->wa_link ? str_replace('https://wa.me/', '', $kantor->wa_link) : '-'" :link="$kantor->wa_link" />
                        <x-info label="Instagram" :value="$kantor->ig_link ? '@'.basename($kantor->ig_link) : '-'" :link="$kantor->ig_link" />
                    </x-card>
                </div>
            @else
                <div class="text-center py-16 space-y-4">
                    <x-heroicon-o-building-office class="w-14 h-14 text-gray-300 mx-auto" />
                    <p class="text-gray-500">Data Kantor belum ada</p>
                    <x-button-link href="{{ route('admin.kantors.create') }}" variant="primary" icon="heroicon-o-plus-circle">
                        Tambah Kantor
                    </x-button-link>
                </div>
            @endif
        </x-card>
    </div>
</x-appadmin-layout>
