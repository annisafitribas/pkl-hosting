<x-appuser-layout>

    {{-- IFRAME DOWNLOAD --}}
    <iframe name="downloadFrame" class="hidden"></iframe>

    {{-- HEADER --}}
    <x-slot name="header">
        <span class="font-semibold text-[#0D1B2A]">
            Laporan Presensi
        </span>
    </x-slot>

    {{-- TOAST --}}
    <x-user-toast />

    <div
        x-data="{ openExport: false }"
        x-on:user-toast.window="openExport = false"
        class="mb-6 space-y-6"
    >

        {{-- ================= REKAP ================= --}}
        <div class="space-y-3">

            {{-- DESKTOP --}}
            <div class="hidden md:grid grid-cols-5 gap-4">
                <x-card class="text-center">
                    <p class="text-sm text-gray-500">Hadir</p>
                    <p class="text-2xl font-bold text-green-600">{{ $rekap->hadir }}</p>
                </x-card>

                <x-card class="text-center">
                    <p class="text-sm text-gray-500">Telat</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $rekap->telat }}</p>
                </x-card>

                <x-card class="text-center">
                    <p class="text-sm text-gray-500">Sakit</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $rekap->sakit }}</p>
                </x-card>

                <x-card class="text-center">
                    <p class="text-sm text-gray-500">Izin</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $rekap->izin }}</p>
                </x-card>

                <x-card class="text-center">
                    <p class="text-sm text-gray-500">Alpha</p>
                    <p class="text-2xl font-bold text-red-600">{{ $rekap->tidak_hadir }}</p>
                </x-card>
            </div>

            {{-- MOBILE --}}
            <x-card class="md:hidden px-2 py-3">
                <div class="flex text-xs font-semibold text-center">
                    <div class="flex-1">
                        <p class="text-gray-400">Hadir</p>
                        <p class="text-green-700">{{ $rekap->hadir }}</p>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-400">Telat</p>
                        <p class="text-yellow-700">{{ $rekap->telat }}</p>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-400">Sakit</p>
                        <p class="text-purple-700">{{ $rekap->sakit }}</p>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-400">Izin</p>
                        <p class="text-blue-700">{{ $rekap->izin }}</p>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-400">Alpha</p>
                        <p class="text-red-700">{{ $rekap->tidak_hadir }}</p>
                    </div>
                </div>
            </x-card>

        </div>

        {{-- ================= TABLE ================= --}}
        <x-card class="p-0 overflow-hidden">

            {{-- HEADER CARD --}}
            <div class="px-6 mt-6 flex justify-between items-center">
                <div class="flex items-center gap-2 text-[#123B6E] font-semibold">
                    <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                    Riwayat Presensi
                </div>

                <x-user-button
                    icon="heroicon-o-arrow-down-tray"
                    @click="openExport = true"
                >
                    Export PDF
                </x-user-button>
            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto mt-4">
                <table class="w-full text-sm">

                    {{-- HEAD --}}
                    <thead class="bg-gray-100">
                        <tr class="text-left text-gray-600 text-xs uppercase tracking-wide">
                            <th class="px-6 py-4 font-semibold">No</th>
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Jam Masuk</th>
                            <th class="px-6 py-4 font-semibold">Jam Keluar</th>
                            <th class="px-6 py-4 font-semibold">Keterangan</th>
                        </tr>
                    </thead>

                    {{-- BODY --}}
                    <tbody>
                        @forelse ($presensi as $i => $item)
                            @php
                                $statusMap = [
                                    'hadir' => 'bg-green-100 text-green-700',
                                    'telat' => 'bg-yellow-100 text-yellow-700',
                                    'izin' => 'bg-blue-100 text-blue-700',
                                    'sakit' => 'bg-purple-100 text-purple-700',
                                    'tidak_hadir' => 'bg-red-100 text-red-700',
                                ];
                            @endphp

                            <tr class="hover:bg-gray-50 transition">

                                <td class="px-6 py-4 font-semibold text-gray-700">
                                    {{ $i + 1 }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $statusMap[$item->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst(str_replace('_',' ', $item->status)) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    {{ $item->jam_masuk ?? '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $item->jam_keluar ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    @if ($item->status === 'tidak_hadir' && empty($item->keterangan))
                                        <span class="italic text-gray-400">
                                            Tanpa keterangan
                                        </span>
                                    @else
                                        {{ $item->keterangan ?? '-' }}
                                    @endif
                                </td>


                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-500">
                                    <x-heroicon-o-folder-minus class="w-10 h-10 mx-auto text-gray-300" />
                                    <p>Belum ada data presensi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </x-card>

        {{-- ================= MODAL EXPORT ================= --}}
        <div
            x-show="openExport"
            x-cloak
            x-transition.opacity
            class="fixed inset-0 z-40 flex items-center justify-center bg-black/50"
        >
            <x-card
                @click.away="openExport = false"
                class="w-full max-w-md space-y-5"
            >

                {{-- HEADER --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-[#123B6E]">
                        <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                        <h3 class="font-semibold text-lg">
                            Export Laporan Presensi
                        </h3>
                    </div>

                    <button @click="openExport = false">
                        <x-heroicon-o-x-mark class="w-5 h-5 text-gray-500" />
                    </button>
                </div>

                {{-- FORM --}}
                <form
                    method="GET"
                    action="{{ route('user.laporan.export') }}"
                    target="downloadFrame"
                    @submit.prevent="
                        openExport = false;
                        $el.submit();
                        $dispatch('user-toast', {
                            title: 'Export Berhasil',
                            message: 'Laporan presensi berhasil diunduh'
                        });
                    "
                    class="space-y-4"
                >
                    <div>
                        <x-input-label>Tanggal Mulai</x-input-label>
                        <x-input type="date" name="from" />
                    </div>

                    <div>
                        <x-input-label>Tanggal Akhir</x-input-label>
                        <x-input type="date" name="to" />
                    </div>

                    <div class="flex gap-2 pt-4">
                        <x-user-button-link
                            href="{{ route('user.laporan.export') }}"
                            target="downloadFrame"
                            variant="secondary"
                            class="flex-1 justify-center"
                            @click="$dispatch('user-toast', {
                                title: 'Export Berhasil',
                                message: 'Laporan presensi berhasil diunduh'
                            })"
                        >
                            Export Semua
                        </x-user-button-link>

                        <x-user-button type="submit" class="flex-1">
                            Export Filter
                        </x-user-button>
                    </div>
                </form>

            </x-card>
        </div>

    </div>

</x-appuser-layout>