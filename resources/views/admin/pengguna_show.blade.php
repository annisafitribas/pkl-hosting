<x-appadmin-layout>
    <x-slot name="header">
        <span class="font-semibold text-lg">Detail Pengguna</span>
    </x-slot>

    @php
        $fotoUser = $pengguna->foto
            ? asset('storage/'.$pengguna->foto)
            : asset('default-user.png');
    @endphp

    <div class="bg-white p-8 rounded-2xl shadow border space-y-8">

        {{-- ================= HEADER PROFIL ================= --}}
        <div class="flex flex-col items-center text-center space-y-3">
            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-300 shadow">
                <img
                    src="{{ $fotoUser }}"
                    alt="Foto {{ $pengguna->name }}"
                    onerror="this.src='{{ asset('default-user.png') }}'"
                    class="w-full h-full object-cover"
                >
            </div>

            <div>
                <h2 class="text-xl font-bold">{{ $pengguna->name }}</h2>
                <span class="inline-block mt-1 px-4 py-1 text-sm rounded-full bg-gray-200 font-semibold">
                    {{ ucfirst($pengguna->role) }}
                </span>
            </div>
        </div>

        {{-- ================= DATA PRIBADI ================= --}}
        <section class="space-y-4">
            <h3 class="flex items-center gap-2 font-semibold text-lg border-b pb-2">
                <x-heroicon-o-identification class="w-5 h-5"/>
                Data Pribadi
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-show-item label="Nama Lengkap" :value="$pengguna->name" />
                <x-show-item label="Usernamr" :value="$pengguna->email" />
                <x-show-item
                    label="Jenis Kelamin"
                    :value="$pengguna->gender === 'L' ? 'Laki-laki' : ($pengguna->gender === 'P' ? 'Perempuan' : '-')"
                />
                <x-show-item
                    label="Tanggal Lahir"
                    :value="optional($pengguna->tgl_lahir)->format('d-m-Y') ?? '-'"
                />
                <x-show-item label="Nomor HP" :value="$pengguna->no_hp ?? '-'" />
                <x-show-item label="Alamat" :value="$pengguna->alamat ?? '-'" full />
            </div>
        </section>

        {{-- ================= AKADEMIK ================= --}}
        @if($pengguna->isUser() && $pengguna->profile)
        <section class="space-y-4">
            <h3 class="flex items-center gap-2 font-semibold text-lg border-b pb-2">
                <x-heroicon-o-academic-cap class="w-5 h-5"/>
                Akademik
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-show-item label="Nomor Induk" :value="$pengguna->profile->nomor_induk ?? '-'" />
                <x-show-item label="Pendidikan" :value="$pengguna->profile->pendidikan ?? '-'" />
                <x-show-item label="Tingkatan" :value="$pengguna->profile->tingkatan ?? '-'" />
                <x-show-item label="Kelas / Semester" :value="$pengguna->profile->kelas ?? '-'" />
                <x-show-item label="Jurusan" :value="$pengguna->profile->jurusan ?? '-'" />
            </div>
        </section>
        @endif

        {{-- ================= PENEMPATAN MAGANG ================= --}}
        @if($pengguna->isUser() && $pengguna->profile)
        <section class="space-y-4">
            <h3 class="flex items-center gap-2 font-semibold text-lg border-b pb-2">
                <x-heroicon-o-rectangle-stack class="w-5 h-5"/>
                Penempatan Divisi Magang
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-show-item
                    label="Bagian"
                    :value="optional($pengguna->profile->bagian)->nama_bagian ?? '-'"
                />
                <x-show-item
                    label="Pembimbing"
                    :value="optional($pengguna->profile->pembimbing)->user->name ?? '-'"
                />
                <x-show-item
                    label="Tanggal Masuk"
                    :value="optional($pengguna->profile->tgl_masuk)->format('d-m-Y') ?? '-'"
                />
                <x-show-item
                    label="Tanggal Keluar"
                    :value="optional($pengguna->profile->tgl_keluar)->format('d-m-Y') ?? '-'"
                />
            </div>
        </section>
        @endif

        {{-- ================= DATA PEMBIMBING ================= --}}
        @if($pengguna->isPembimbing() && $pengguna->pembimbingProfile)
        <section class="space-y-4">
            <h3 class="flex items-center gap-2 font-semibold text-lg border-b pb-2">
                <x-heroicon-o-user-group class="w-5 h-5"/>
                Data Pembimbing
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-show-item label="NIP" :value="$pengguna->pembimbingProfile->nip ?? '-'" />
                <x-show-item label="Jabatan" :value="$pengguna->pembimbingProfile->jabatan ?? '-'" />
                <x-show-item
                    label="Bagian"
                    :value="optional($pengguna->pembimbingProfile->bagian)->nama_bagian ?? '-'"
                />
            </div>
        </section>
        @endif

        {{-- ================= ACTION ================= --}}
        <div class="flex justify-end gap-3 pt-6 border-t">
            <x-button-link href="{{ route('admin.pengguna.index') }}" variant="secondary">
                Kembali
            </x-button-link>

            <x-button-link
                href="{{ route('admin.pengguna.edit', $pengguna->id) }}"
                variant="primary"
                icon="heroicon-o-pencil-square"
            >
                Edit
            </x-button-link>
        </div>

    </div>
</x-appadmin-layout>
