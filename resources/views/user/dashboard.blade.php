<x-appuser-layout>

    <x-slot name="header">
        <span class="font-semibold text-[#0D1B2A]">Dashboard</span>
    </x-slot>

    <div class="space-y-4 mb-6">

        {{-- ================= WELCOME ================= --}}
        <div class="bg-gradient-to-r from-[#0F2F57] via-[#123B6E] to-[#1E4F8F] text-white p-6 rounded-xl shadow">
            <h2 class="text-xl font-semibold flex items-center gap-2">
                <x-heroicon-o-hand-raised class="w-6 h-6"/>
                Halo, {{ $user->name }}
            </h2>
            <p class="text-sm opacity-90">
                Peserta Magang di {{ $kantor?->nama_apk ?? 'Perusahaan' }}
            </p>
            <p class="text-sm opacity-90">
                Jam kerja kantor
                {{ $kantor?->jam_masuk ? \Carbon\Carbon::parse($kantor->jam_masuk)->format('H:i') : '07:45' }}
                –
                {{ $kantor?->jam_keluar ? \Carbon\Carbon::parse($kantor->jam_keluar)->format('H:i') : '17:00' }}
            </p>
        </div>

        {{-- ================= INFO LIBUR ================= --}}
        @if (!$kantor->isHariKerjaFinal())
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-xl shadow flex gap-3">
                <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-yellow-500"/>
                <div>
                    <p class="font-semibold text-yellow-700">
                        Hari ini tidak ada presensi
                    </p>
                    <ul class="list-disc list-inside text-sm text-yellow-600 mt-1 space-y-1">
                        @if (!$kantor->isHariKerja())
                            <li>Bukan hari kerja sesuai jadwal kantor</li>
                        @endif
                        @if ($kantor->isHariLibur())
                            <li>
                                Hari libur nasional:
                                <span class="font-semibold">
                                    {{ $kantor->hariLiburDetail()?->nama ?? 'Tanggal Merah' }}
                                </span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        @endif

        {{-- ================= JAM MASUK & KELUAR ================= --}}
        <div class="bg-white p-6 rounded-xl shadow flex min-h-[96px]">

            <div class="w-1/2 flex flex-col items-center justify-center border-r">
                <p class="text-sm text-gray-500">Jam Masuk</p>
                <p class="font-bold text-lg text-[#123B6E] leading-none">
                    {{ $jamMasuk }}
                </p>
            </div>

            <div class="w-1/2 flex flex-col items-center justify-center">
                <p class="text-sm text-gray-500">Jam Keluar</p>
                <p class="font-bold text-lg text-[#123B6E] leading-none">
                    {{ $jamKeluar }}
                </p>
            </div>

        </div>

        {{-- ================= STATUS & AKSI ================= --}}
        <div class="flex rounded-xl shadow overflow-hidden">

            {{-- STATUS --}}
            <div class="flex-1 p-4 flex flex-col items-center justify-center text-center
                {{ !$kantor->isHariKerjaFinal() ? 'bg-yellow-50' : 'bg-[#123B6E]/10' }}">
                <p class="text-sm text-gray-600">Status</p>
                <p class="font-bold mt-1 text-[#123B6E]">
                    @if (!$kantor->isHariKerjaFinal())
                        Libur
                    @elseif ($presensiHariIni?->status)
                        {{ ucfirst(str_replace('_', ' ', $presensiHariIni->status)) }}
                    @else
                        Belum Absen
                    @endif
                </p>
            </div>

            {{-- AKSI --}}
            <div class="flex-1 p-4 flex items-center justify-center
                {{ !$kantor->isHariKerjaFinal() ? 'bg-yellow-50' : 'bg-[#123B6E]/5' }}">

                @if (!$kantor->isHariKerjaFinal())
                    <div class="flex flex-col items-center gap-0.5 text-yellow-700 font-semibold">
                        <x-heroicon-o-no-symbol class="w-6 h-6"/>
                        <span class="text-sm">Presensi tidak tersedia</span>
                    </div>

                @else
                    @if (is_null($presensiHariIni))
                        <a href="{{ route('user.presensi.create') }}"
                           class="flex items-center gap-2
                                  bg-[#123B6E] hover:bg-[#0F2F57]
                                  text-white px-6 py-2 rounded-lg font-semibold">
                            <x-heroicon-o-arrow-right-circle class="w-5 h-5"/>
                            Presensi Masuk
                        </a>

                    @elseif (!is_null($presensiHariIni->jam_masuk) && is_null($presensiHariIni->jam_keluar))
                        <a href="{{ route('user.presensi.create') }}"
                           class="flex items-center gap-2
                                  bg-[#123B6E] hover:bg-[#0F2F57]
                                  text-white px-6 py-2 rounded-lg font-semibold">
                            <x-heroicon-o-clock class="w-5 h-5"/>
                            Presensi Keluar
                        </a>

                    @else
                        <div class="flex flex-col items-center gap-0.5 text-[#123B6E] font-semibold">
                            <x-heroicon-o-check-circle class="w-6 h-6 stroke-1"/>
                            <span class="text-sm">Presensi selesai</span>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        {{-- ================= REKAP ================= --}}
        <div class="bg-white p-6 rounded-xl shadow flex">

            <div class="w-1/2 flex flex-col items-center justify-center border-r">
                <p class="text-sm text-gray-500">Hadir</p>
                <p class="font-bold text-lg text-[#123B6E]">
                    {{ $rekap->hadir ?? 0 }}
                </p>
                <p class="text-xs text-gray-400">Hadir & Telat</p>
            </div>

            <div class="w-1/2 flex flex-col items-center justify-center">
                <p class="text-sm text-gray-500">Tidak Hadir</p>
                <p class="font-bold text-lg text-red-600">
                    {{ $rekap->tidak_hadir ?? 0 }}
                </p>
                <p class="text-xs text-gray-400">Sakit, Izin, Alpha</p>
            </div>

        </div>

        {{-- ================= INFO MAGANG ================= --}}
        <div class="bg-white p-4 rounded-xl shadow flex gap-4 text-sm
                    border-l-4 border-[#123B6E]/40">

            <div class="flex-1 flex flex-col items-center">
                <p class="text-gray-500">Status Magang</p>
                <p class="font-bold text-[#123B6E] text-lg">
                    {{ $user->profile?->status_magang ?? '-' }}
                </p>
            </div>

            <div class="flex-1 flex flex-col items-center">
                <p class="text-gray-500">Tanggal Mulai</p>
                <p class="font-bold text-[#123B6E] text-lg">
                    {{ $user->profile?->tgl_masuk?->format('d-m-Y') ?? '-' }}
                </p>
            </div>

            <div class="flex-1 flex flex-col items-center">
                <p class="text-gray-500">Tanggal Selesai</p>
                <p class="font-bold text-[#123B6E] text-lg">
                    {{ $user->profile?->tgl_keluar?->format('d-m-Y') ?? '-' }}
                </p>
            </div>
        </div>

    </div>
</x-appuser-layout>