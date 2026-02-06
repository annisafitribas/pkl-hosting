<x-apppembimbing-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Dashboard Pembimbing
        </h2>
    </x-slot>

    <div class="space-y-6">

{{--  STATISTIK  --}}
<x-card class="space-y-3">
    <h3 class="font-semibold text-gray-800">
        Rekap Presensi Hari Ini
    </h3>

    {{-- DESKTOP --}}
    <div class="hidden lg:grid grid-cols-5 gap-3">
        @foreach ([
            ['Hadir', $hadir, 'text-green-600'],
            ['Telat', $telat, 'text-yellow-600'],
            ['Sakit', $sakit, 'text-purple-600'],
            ['Izin', $izin, 'text-blue-600'],
            ['Belum', $belumPresensi, 'text-gray-700'],
        ] as [$label, $value, $colorClass])

            <x-card class="text-center py-2 px-2">
                <p class="text-sm text-gray-500 leading-none">
                    {{ $label }}
                </p>
                <p class="text-2xl font-semibold leading-tight {{ $colorClass }}">
                    {{ $value }}
                </p>
            </x-card>

        @endforeach
    </div>

    {{-- MOBILE --}}
    <x-card class="md:hidden px-2 py-3">
        <div class="flex text-xs font-semibold text-center">
            <div class="flex-1">
                <p class="text-gray-400">Hadir</p>
                <p class="text-green-700">{{ $hadir }}</p>
            </div>
            <div class="flex-1">
                <p class="text-gray-400">Telat</p>
                <p class="text-yellow-700">{{ $telat }}</p>
            </div>
            <div class="flex-1">
                <p class="text-gray-400">Sakit</p>
                <p class="text-purple-700">{{ $sakit }}</p>
            </div>
            <div class="flex-1">
                <p class="text-gray-400">Izin</p>
                <p class="text-blue-700">{{ $izin }}</p>
            </div>
            <div class="flex-1">
                <p class="text-gray-400">Belum</p>
                <p class="text-gray-700">{{ $belumPresensi }}</p>
            </div>
        </div>
    </x-card>
</x-card>

        {{--  PRESENSI HARI INI  --}}
        <x-card>
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">
                    Presensi Hari Ini
                </h3>

                <span class="text-sm font-semibold text-gray-600">
                    Total Peserta: {{ $peserta->count() }}
                </span>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Nama</th>
                        <th class="px-4 py-2 text-center">Masuk</th>
                        <th class="px-4 py-2 text-center">Keluar</th>
                        <th class="px-4 py-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($peserta as $user)
                        @php
                            $pHariIni = $presensiHariIni[$user->id] ?? null;
                        @endphp
                        <tr>
                            <td class="px-4 py-2 font-semibold">
                                {{ $user->name }}
                            </td>

                            <td class="px-4 py-2 text-center">
                                {{ $pHariIni?->jam_masuk ?? '-' }}
                            </td>

                            <td class="px-4 py-2 text-center">
                                {{ $pHariIni?->jam_keluar ?? '-' }}
                            </td>

                            <td class="px-4 py-2 text-center">
                                @if(!$pHariIni)
                                    <span class="text-gray-500">Belum Presensi</span>
                                @elseif($pHariIni->status === 'izin')
                                    <span class="text-yellow-600 font-semibold">Izin</span>
                                @elseif($pHariIni->status === 'sakit')
                                    <span class="text-red-600 font-semibold">Sakit</span>
                                @elseif($pHariIni->status === 'telat')
                                    <span class="text-orange-600 font-semibold">Telat</span>
                                @else
                                    <span class="text-green-600 font-semibold">Hadir</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>

    </div>

</x-apppembimbing-layout>