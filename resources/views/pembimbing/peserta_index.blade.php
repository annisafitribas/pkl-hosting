<x-apppembimbing-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Daftar Peserta Bimbingan
        </h2>
    </x-slot>

    <x-card>

        <div class="mb-4 text-sm text-gray-600">
            Total Peserta :
            <span class="font-semibold text-gray-800">
                {{ $peserta->count() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left">Peserta</th>
                        <th class="px-4 py-3">Bagian</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Hadir</th>
                        <th class="px-4 py-3 text-center">Tidak Hadir</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                @forelse($peserta as $user)
                    <tr class="hover:bg-gray-50">

                        {{-- PESERTA --}}
                        <td class="px-4 py-3 flex items-center gap-3">
                            <img
                                src="{{ $user->foto
                                    ? asset('storage/'.$user->foto)
                                    : asset('default-user.png') }}"
                                class="w-10 h-10 rounded-full object-cover"
                            >
                            <div>
                                <p class="font-semibold">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </td>

                        {{-- BAGIAN --}}
                        <td class="px-4 py-3 text-center">
                            {{ $user->profile?->bagian?->nama ?? '-' }}
                        </td>

                        {{-- STATUS MAGANG --}}
                        <td class="px-4 py-3 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $user->profile?->status_magang === 'Aktif'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-gray-200 text-gray-600' }}">
                                {{ $user->profile?->status_magang ?? '-' }}
                            </span>
                        </td>

                        {{-- HADIR --}}
                        <td class="px-4 py-3 text-center font-semibold text-green-600">
                            {{ $user->total_hadir }}
                        </td>

                        {{-- TIDAK HADIR --}}
                        <td class="px-4 py-3 text-center font-semibold text-red-600">
                            {{ $user->total_tidak_hadir }}
                        </td>

                        {{-- AKSI --}}
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('pembimbing.peserta.show', $user->id) }}"
                            class="text-blue-600 hover:underline text-sm font-semibold">
                                Detail
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            Belum ada peserta bimbingan
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </x-card>

</x-apppembimbing-layout>