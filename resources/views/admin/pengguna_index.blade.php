<x-appadmin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-[#0D1B2A]">
            <span>Pengguna</span>
        </div>
    </x-slot>

    <div class="container mx-auto">
        <x-card>
            <div class="flex flex-wrap items-center justify-between mb-4 gap-4">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-users class="w-6 h-6 text-[#0D1B2A]" />
                    <h2 class="text-lg font-semibold text-[#0D1B2A]">Daftar Pengguna</h2>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <form method="GET" action="{{ route('admin.pengguna.index') }}" class="w-full sm:w-[220px]">
                        <x-select-box name="role" :value="$role" :options="[
                                'all' => 'Semua Role',
                                'user' => 'Peserta Magang',
                                'admin' => 'Admin',
                                'pembimbing' => 'Pembimbing'
                            ]" placeholder="Filter Role" submit/>
                    </form>

                    <x-button-link href="{{ route('admin.pengguna.create') }}" variant="primary" icon="heroicon-o-plus-circle">
                        Tambah
                    </x-button-link>
                </div>
            </div>

            @if ($users->count())
                <x-table>
                    <thead>
                        <tr>
                            <x-table-th align="center" class="w-12 whitespace-nowrap">No</x-table-th>
                            <x-table-th>Nama</x-table-th>
                            <x-table-th>Email</x-table-th>

                            @if ($role === 'admin')
                                <x-table-th>No HP</x-table-th>
                                <x-table-th>Alamat</x-table-th>
                            @elseif ($role === 'pembimbing')
                                <x-table-th>NIP</x-table-th>
                                <x-table-th>Jabatan</x-table-th>
                                <x-table-th>Bagian</x-table-th>
                                <x-table-th align="center">Jumlah Peserta</x-table-th>
                            @elseif ($role === 'user')
                                <x-table-th>Pendidikan</x-table-th>
                                <x-table-th>Kelas</x-table-th>
                                <x-table-th>Jurusan</x-table-th>
                                <x-table-th>Bagian</x-table-th>
                                <x-table-th>Status</x-table-th>
                            @else
                                <x-table-th>Role</x-table-th>
                            @endif

                            <x-table-th align="center">Aksi</x-table-th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                            <tr class="hover:bg-[#0D1B2A1A] even:bg-[#F8FAFC] transition">
                                <x-table-td align="center">{{ $loop->iteration }}</x-table-td>
                                <x-table-td>{{ $user->name }}</x-table-td>
                                <x-table-td>{{ $user->email }}</x-table-td>

                                @if ($role === 'admin')
                                    <x-table-td>{{ $user->no_hp ?? '-' }}</x-table-td>
                                    <x-table-td>{{ $user->alamat ?? '-' }}</x-table-td>

                                @elseif ($role === 'pembimbing')
                                    <x-table-td>{{ optional($user->pembimbingProfile)->nip ?? '-' }}</x-table-td>
                                    <x-table-td>{{ optional($user->pembimbingProfile)->jabatan ?? '-' }}</x-table-td>
                                    <x-table-td>{{ optional(optional($user->pembimbingProfile)->bagian)->nama ?? '-' }}</x-table-td>
                                    <x-table-td align="center">{{ optional($user->pembimbingProfile)->usersDibimbing->count() ?? 0 }}</x-table-td>

                                @elseif ($role === 'user')
                                    <x-table-td>{{ optional($user->profile)->pendidikan ?? '-' }}</x-table-td>
                                    <x-table-td>{{ optional($user->profile)->kelas ?? '-' }}</x-table-td>
                                    <x-table-td>{{ optional($user->profile)->jurusan ?? '-' }}</x-table-td>
                                    <x-table-td>{{ optional(optional($user->profile)->bagian)->nama ?? '-' }}</x-table-td>
                            
                                    <x-table-td align="center">
                                        @php
                                            $status = optional($user->profile)->status_magang;
                                        @endphp

                                        @if ($status === 'Aktif')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                        bg-green-100 text-green-700">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                        bg-gray-100 text-gray-600">
                                                Tidak Aktif
                                            </span>
                                        @endif
                                    </x-table-td>

                                @else
                                    <x-table-td>{{ ucfirst($user->role) }}</x-table-td>
                                @endif

                                <x-table-td align="center">
                                    <div class="flex items-center justify-center gap-2">

                                        {{-- DETAIL --}}
                                        <a href="{{ route('admin.pengguna.show', $user->id) }}"
                                        class="p-1.5 rounded-md text-blue-500 hover:bg-blue-500/10">
                                            <x-heroicon-o-eye class="w-5 h-5" />
                                        </a>

                                        {{-- EDIT --}}
                                        <a href="{{ route('admin.pengguna.edit', $user->id) }}"
                                        class="p-1.5 rounded-md text-yellow-500 hover:bg-yellow-500/10">
                                            <x-heroicon-o-pencil-square class="w-5 h-5" />
                                        </a>

                                        {{-- HAPUS (TIDAK BISA HAPUS AKUN SENDIRI) --}}
                                        @if ($user->isDeletable())
                                            <button type="button"
                                                class="p-1.5 rounded-md text-red-500 hover:bg-red-500/10"
                                                x-data
                                                @click="window.dispatchEvent(
                                                    new CustomEvent('open-confirm', {
                                                        detail: { id: 'hapus-user-{{ $user->id }}' }
                                                    })
                                                )">
                                                <x-heroicon-o-trash class="w-5 h-5" />
                                            </button>
                                        @else
                                            <span
                                                class="p-1.5 rounded-md text-gray-400 cursor-not-allowed"
                                                title="
                                                    {{ $user->role === 'pembimbing'
                                                        ? 'Pembimbing masih memiliki peserta bimbingan'
                                                        : ($user->role === 'user'
                                                            ? 'Peserta masih aktif magang'
                                                            : 'Tidak dapat menghapus akun admin')
                                                    }}">
                                                <x-heroicon-o-trash class="w-5 h-5" />
                                            </span>
                                        @endif

                                    </div>

                                    {{-- MODAL HAPUS --}}
                                    @if ($user->isDeletable())
                                        <x-confirm-modal
                                            id="hapus-user-{{ $user->id }}"
                                            title="Hapus Pengguna"
                                            message="Apakah Anda yakin ingin menghapus pengguna '{{ $user->name }}'?"
                                            variant="danger">
                                            <form action="{{ route('admin.pengguna.destroy', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <x-button type="submit" variant="danger">
                                                    Ya, Hapus
                                                </x-button>
                                            </form>
                                        </x-confirm-modal>
                                    @endif
                                </x-table-td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table>
            @else
                <div class="text-center font-semibold py-10 flex flex-col items-center gap-2">
                    <x-heroicon-o-folder-minus class="w-12 h-12 text-[#CBD5E1]" />
                    <span>Data pengguna belum ada</span>
                </div>
            @endif
        </x-card>
    </div>
</x-appadmin-layout>
