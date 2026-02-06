<x-apppembimbing-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('pembimbing.peserta') }}"
               class="flex items-center gap-1 text-blue-600 hover:text-blue-800 font-semibold text-sm">
                <x-heroicon-o-arrow-left class="w-5 h-5"/>
                Kembali
            </a>

            <span class="font-semibold text-[#0D1B2A]">
                Detail Peserta
            </span>
        </div>
    </x-slot>

    <div class="space-y-6 mb-6">

<x-card class="space-y-6">

    {{-- ================= PROFIL ATAS ================= --}}
    <div class="flex items-center gap-4">

        {{-- FOTO KECIL --}}
        <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-blue-100 shadow">
            <img
                src="{{ $user->foto
                    ? asset('storage/'.$user->foto)
                    : asset('default-user.png') }}"
                class="w-full h-full object-cover"
            >
        </div>

        {{-- NAMA & EMAIL --}}
        <div>
            <h3 class="text-lg font-semibold text-gray-800">
                {{ $user->name }}
            </h3>
            <p class="text-sm text-gray-500">
                {{ $user->email }}
            </p>
        </div>

    </div>

    {{-- ================= DATA PRIBADI ================= --}}
    <div class="border-t pt-5 space-y-4">
        <h4 class="font-semibold text-gray-700">
            Data Pribadi & Magang
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <x-show-item
                label="Bagian"
                :value="$user->profile?->bagian?->nama ?? '-'"
            />

            <x-show-item
                label="Status Magang"
                :value="$user->profile?->status_magang ?? '-'"
            />

            <x-show-item
                label="Tanggal Masuk"
                :value="optional($user->profile?->tgl_masuk)->format('d M Y') ?? '-'"
            />

            <x-show-item
                label="Tanggal Keluar"
                :value="optional($user->profile?->tgl_keluar)->format('d M Y') ?? '-'"
            />
        </div>
    </div>

    {{-- ================= DATA AKADEMIK ================= --}}
    <div class="border-t pt-5 space-y-4">
        <h4 class="font-semibold text-gray-700">
            Data Akademik
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <x-show-item
                label="Pendidikan"
                :value="$user->profile?->pendidikan ?? '-'"
            />

            <x-show-item
                label="Jurusan"
                :value="$user->profile?->jurusan ?? '-'"
            />

            <x-show-item
                label="Kelas"
                :value="$user->profile?->kelas ?? '-'"
            />

            <x-show-item
                label="Nomor Induk"
                :value="$user->profile?->nomor_induk ?? '-'"
            />
        </div>
    </div>

    </x-card>
            
    {{-- ================= REKAP PRESENSI ================= --}}
    <x-card class="space-y-3">
        <h3 class="font-semibold text-gray-800">
            Rekap Presensi Selama Magang
        </h3>

{{-- DESKTOP --}}
<div class="hidden lg:grid grid-cols-5 gap-3">
    @foreach ([
        ['Hadir', $hadir, 'text-green-600'],
        ['Telat', $telat, 'text-orange-500'],
        ['Izin', $izin, 'text-yellow-600'],
        ['Sakit', $sakit, 'text-red-600'],
        ['Alpha', $alpha, 'text-gray-700'],
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
                    <p class="text-orange-700">{{ $telat }}</p>
                </div>
                <div class="flex-1">
                    <p class="text-gray-400">Izin</p>
                    <p class="text-yellow-700">{{ $izin }}</p>
                </div>
                <div class="flex-1">
                    <p class="text-gray-400">Sakit</p>
                    <p class="text-red-700">{{ $sakit }}</p>
                </div>
                <div class="flex-1">
                    <p class="text-gray-400">Alpha</p>
                    <p class="text-gray-700">{{ $alpha }}</p>
                </div>
            </div>
        </x-card>
    </x-card>

        {{-- ================= RIWAYAT PRESENSI ================= --}}
        <x-card class="space-y-4">
            <h3 class="flex items-center gap-2 font-semibold text-lg text-gray-700">
                <x-heroicon-o-calendar-days class="w-5 h-5 text-blue-600"/>
                Riwayat Presensi
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-center">Masuk</th>
                            <th class="px-4 py-2 text-center">Keluar</th>
                            <th class="px-4 py-2 text-center">Status</th>
                            <th class="px-4 py-2 text-left">Keterangan</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                    @forelse($presensi as $p)
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-2">
                                {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                            </td>

                            <td class="px-4 py-2 text-center">
                                {{ $p->jam_masuk ?? '-' }}
                            </td>

                            <td class="px-4 py-2 text-center">
                                {{ $p->jam_keluar ?? '-' }}
                            </td>

                            <td class="px-4 py-2 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @class([
                                        'bg-green-100 text-green-700'   => $p->status === 'hadir',
                                        'bg-orange-100 text-orange-700' => $p->status === 'telat',
                                        'bg-yellow-100 text-yellow-700' => $p->status === 'izin',
                                        'bg-red-100 text-red-700'       => $p->status === 'sakit',
                                        'bg-gray-200 text-gray-700'     => $p->status === 'tidak_hadir',
                                    ])">
                                    {{ ucfirst(str_replace('_',' ', $p->status)) }}
                                </span>
                            </td>

                            <td class="px-4 py-2">
                                {{ $p->keterangan ?? '-' }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">
                                Belum ada data presensi
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

    </div>

</x-apppembimbing-layout>
