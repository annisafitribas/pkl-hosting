<x-apppembimbing-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Daftar Pengajuan Peserta
        </h2>
    </x-slot>

    <x-card>
        <div class="text-sm flex items-center justify-between mb-4">
            <div class="text-sm text-gray-600">
                Total Pengajuan :
                <span class="font-semibold text-gray-800">
                    {{ $pengajuans->count() }}
                </span>
            </div>

        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm table-fixed">

                {{-- HEAD --}}
                <thead class="bg-gray-100 border-b">
                    <tr class="text-gray-600 text-sm">

                        <th class="px-4 py-3 text-left w-[30%]">
                            Peserta
                        </th>

                        <th class="px-4 py-3 text-left w-[30%]">
                            Tanggal
                        </th>

                        <th class="px-4 py-3 text-left w-[15%]">
                            Jenis
                        </th>

                        <th class="px-4 py-3 text-center w-[15%]">
                            Status
                        </th>

                        <th class="px-4 py-3 text-center w-[10%]">
                            Aksi
                        </th>

                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody class="divide-y bg-white">
                @forelse($pengajuans as $item)
                    <tr class="hover:bg-gray-50 transition">

                        {{-- PESERTA --}}
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-800">
                                {{ $item->user->name }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">
                                {{ $item->user->email }}
                            </p>
                        </td>

                        {{-- TANGGAL --}}
                        <td class="px-4 py-3 whitespace-nowrap text-gray-800">
                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                            <span class="text-gray-400 mx-1">–</span>
                            {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                        </td>

                        {{-- JENIS --}}
                        <td class="px-4 py-3 text-gray-800">
                            {{ ucfirst($item->jenis ?? 'izin') }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex justify-center px-3 py-1 rounded-full text-xs font-semibold
                                @class([
                                    'bg-yellow-100 text-yellow-700' => $item->status === 'pending',
                                    'bg-green-100 text-green-700'  => $item->status === 'approved',
                                    'bg-red-100 text-red-700'      => $item->status === 'rejected',
                                ])">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>

                        {{-- AKSI --}}
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('pembimbing.pengajuan.show', $item->id) }}"
                               class="text-blue-600 hover:underline text-sm font-semibold">
                                Detail
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-500">
                            Tidak ada pengajuan
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </x-card>

    {{-- INFO --}}
    <x-card class="bg-blue-50 border mt-6 border-blue-100">
        <p class="text-sm text-blue-700 flex items-center gap-2">
            <x-heroicon-o-information-circle class="w-5 h-5"/>
            Pengajuan ini hanya dapat <b>disetujui atau ditolak oleh Admin</b>.
        </p>
    </x-card>

</x-apppembimbing-layout>