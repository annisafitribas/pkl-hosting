<x-appadmin-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <span class="font-semibold text-[#0D1B2A]">
                Rekap Presensi
            </span>
        </div>
    </x-slot>

    <div class="container mx-auto">
        <x-card>

            {{-- HEADER CARD --}}
            <div class="flex items-center gap-2 mb-4">
                <x-heroicon-o-clipboard-document-list class="w-6 h-6 text-[#0D1B2A]" />
                <h2 class="text-lg font-semibold text-[#0D1B2A]">
                    Presensi Peserta
                </h2>
            </div>

            @if ($users->count())
                <x-table>
                    <thead>
                        <tr>
                            <x-table-th class="w-12 whitespace-nowrap">No</x-table-th>
                            <x-table-th>Nama</x-table-th>
                            <x-table-th align="center">Hadir</x-table-th>
                            <x-table-th align="center">Telat</x-table-th>
                            <x-table-th align="center">Sakit</x-table-th>
                            <x-table-th align="center">Izin</x-table-th>
                            <x-table-th align="center">Alpha</x-table-th>
                            <x-table-th align="center">Aksi</x-table-th>
                        </tr>
                    </thead>

                    <tbody class="text-[#0D1B2A]">
                        @foreach ($users as $user)
                            <tr class="hover:bg-[#0D1B2A1A] even:bg-[#F8FAFC] transition">

                                <x-table-td>
                                    {{ $loop->iteration }}
                                </x-table-td>

                                <x-table-td class="font-semibold">
                                    {{ $user->name }}
                                </x-table-td>

                                <x-table-td align="center">
                                    {{ $user->total_hadir }}
                                </x-table-td>

                                <x-table-td align="center">
                                    {{ $user->total_telat }}
                                </x-table-td>

                                <x-table-td align="center">
                                    {{ $user->total_sakit }}
                                </x-table-td>

                                <x-table-td align="center">
                                    {{ $user->total_izin }}
                                </x-table-td>

                                <x-table-td align="center">
                                    {{ $user->total_alpha }}
                                </x-table-td>

                                <x-table-td align="center">
                                    <a
                                        href="{{ route('admin.presensi.show', $user->id) }}"
                                        class="text-blue-600 hover:underline text-sm font-semibold">
                                        Detail
                                    </a>
                                </x-table-td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table>
            @else
                <div class="text-center py-10 flex flex-col items-center gap-2">
                    <x-heroicon-o-folder-minus class="w-12 h-12 text-[#CBD5E1]" />
                    <span class="text-gray-500 font-semibold">
                        Belum ada data presensi
                    </span>
                </div>
            @endif

        </x-card>
    </div>

</x-appadmin-layout>
