<x-appadmin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h1 class="text-xl font-semibold text-[#0D1B2A]">Dashboard Admin</h1>
        </div>
    </x-slot>

    <div class="space-y-6">

        <x-card class="space-y-4">

            {{-- HEADER --}}
            <div>
                <h2 class="text-lg font-semibold text-[#0D1B2A]">
                    Kehadiran Hari Ini
                </h2>
                <p class="text-sm text-gray-500">
                    Rekap kehadiran peserta magang hari ini
                </p>
            </div>

            {{-- DESKTOP --}}
            <div class="hidden lg:grid grid-cols-5 gap-4">
                @foreach ([
                    ['Hadir', $hadir, 'green'],
                    ['Terlambat', $terlambat, 'yellow'],
                    ['Izin', $izin, 'blue'],
                    ['Sakit', $sakit, 'indigo'],
                    ['Belum Absen', $belumAbsen, 'red'],
                ] as [$label, $value, $color])
                    <div class="rounded-xl border bg-white py-3 text-center">
                        <p class="text-xs text-gray-500">{{ $label }}</p>
                        <p class="text-2xl font-bold text-{{ $color }}-600">
                            {{ $value }}
                        </p>
                    </div>
                @endforeach
            </div>

            {{-- MOBILE --}}
            <div class="lg:hidden rounded-xl border bg-white py-3">
                <div class="flex text-xs font-semibold text-center">
                    <div class="flex-1 text-green-700">
                        <p class="text-gray-400">Hadir</p>{{ $hadir }}
                    </div>
                    <div class="flex-1 text-yellow-700">
                        <p class="text-gray-400">Telat</p>{{ $terlambat }}
                    </div>
                    <div class="flex-1 text-blue-700">
                        <p class="text-gray-400">Izin</p>{{ $izin }}
                    </div>
                    <div class="flex-1 text-indigo-700">
                        <p class="text-gray-400">Sakit</p>{{ $sakit }}
                    </div>
                    <div class="flex-1 text-red-700">
                        <p class="text-gray-400">Alpha</p>{{ $belumAbsen }}
                    </div>
                </div>
            </div>

        </x-card>

    <x-card class="space-y-4">

        {{-- HEADER --}}
        <div>
            <h2 class="text-lg font-semibold text-[#0D1B2A]">
                Ringkasan Data Sistem
            </h2>
            <p class="text-sm text-gray-500">
                Informasi umum data yang tersimpan dalam sistem
            </p>
        </div>

        {{-- ALERT --}}
        @if ($pengajuanPending > 0)
            <a href="{{ route('admin.pengajuan.index') }}"
            class="flex items-center justify-between gap-3 rounded-xl
                    border border-yellow-300 bg-yellow-50 p-4 text-yellow-800
                    hover:bg-yellow-100 transition">
                <div class="flex items-center gap-3">
                    <x-heroicon-o-exclamation-circle class="w-6 h-6"/>
                    <div>
                        <p class="font-semibold">
                            Ada {{ $pengajuanPending }} pengajuan izin
                        </p>
                        <p class="text-sm">
                            Menunggu konfirmasi admin
                        </p>
                    </div>
                </div>
                <span class="text-sm font-medium underline">Lihat</span>
            </a>
        @endif

        {{-- DESKTOP --}}
        <div class="hidden lg:grid grid-cols-3 gap-4">
            @foreach ([
                ['Peserta Magang', $totalPeserta],
                ['Pengguna Sistem', $totalPengguna],
                ['Bagian', $totalBagian],
            ] as [$label, $value])
                <div class="rounded-xl border bg-white py-4 text-center">
                    <p class="text-xs text-gray-500">{{ $label }}</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        {{-- MOBILE --}}
        <div class="lg:hidden rounded-xl border bg-white py-3">
            <div class="flex text-xs font-semibold text-center">
                <div class="flex-1">
                    <p class="text-gray-400">Peserta</p>{{ $totalPeserta }}
                </div>
                <div class="flex-1">
                    <p class="text-gray-400">Pengguna</p>{{ $totalPengguna }}
                </div>
                <div class="flex-1">
                    <p class="text-gray-400">Bagian</p>{{ $totalBagian }}
                </div>
            </div>
        </div>

    </x-card>


        {{-- ================= TABEL KEHADIRAN ================= --}}
        <x-card>
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-[#0D1B2A]">
                    Detail Kehadiran Hari Ini
                </h2>
                <p class="text-m text-gray-500">
                    Data presensi peserta magang berdasarkan hari berjalan
                </p>
            </div>

            @php
                $statumap = [
                    'hadir' => [
                        'label' => 'Hadir',
                        'class' => 'bg-green-100 text-green-700',
                    ],
                    'telat' => [
                        'label' => 'Terlambat',
                        'class' => 'bg-yellow-100 text-yellow-700',
                    ],
                    'izin' => [
                        'label' => 'Izin',
                        'class' => 'bg-blue-100 text-blue-700',
                    ],
                    'sakit' => [
                        'label' => 'Sakit',
                        'class' => 'bg-indigo-100 text-indigo-700',
                    ],
                    'tidak_hadir' => [
                        'label' => 'Tidak Hadir',
                        'class' => 'bg-red-100 text-red-700',
                    ],
                ];
            @endphp

            @if ($kehadiranHariIni->count())
                <x-table>
                    <thead>
                        <tr>
                            <x-table-th align="center" class="w-12 whitespace-nowrap">No</x-table-th>
                            <x-table-th>Nama</x-table-th>
                            <x-table-th>Bagian</x-table-th>
                            <x-table-th align="center">Masuk</x-table-th>
                            <x-table-th align="center">Pulang</x-table-th>
                            <x-table-th align="center">Status</x-table-th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kehadiranHariIni as $presensi)
                            <tr class="hover:bg-[#0D1B2A]/5 transition">
                                <x-table-td align="center">
                                    {{ $loop->iteration }}
                                </x-table-td>

                                <x-table-td>
                                    {{ $presensi->user->name }}
                                </x-table-td>

                                <x-table-td>
                                    {{ optional(optional($presensi->user->profile)->bagian)->nama ?? '-' }}
                                </x-table-td>

                                <x-table-td align="center">
                                    {{ $presensi->jam_masuk ?? '-' }}
                                </x-table-td>

                                <x-table-td align="center">
                                    {{ $presensi->jam_keluar ?? '-' }}
                                </x-table-td>

                                <x-table-td align="center">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $statumap[$presensi->status]['class'] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $statumap[$presensi->status]['label'] ?? '-' }}
                                    </span>
                                </x-table-td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table>
            @else
                <div class="py-12 flex flex-col items-center gap-3 text-gray-500">
                    <x-heroicon-o-folder-minus class="w-14 h-14 text-gray-300" />
                    <span class="font-medium">
                        Belum ada data kehadiran hari ini
                    </span>
                </div>
            @endif
        </x-card>

    </div>
</x-appadmin-layout>