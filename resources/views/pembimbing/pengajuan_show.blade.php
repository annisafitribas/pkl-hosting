<x-apppembimbing-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('pembimbing.pengajuan') }}"
               class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-semibold text-sm">
                <x-heroicon-o-arrow-left class="w-5 h-5"/>
                Kembali
            </a>

            <h2 class="text-xl font-semibold text-[#0D1B2A]">
                Detail Pengajuan
            </h2>
        </div>
    </x-slot>

    <div class="space-y-6 mb-6">

        {{-- GRID 2 KOLOM --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- ================= DATA PESERTA ================= --}}
            <x-card class="space-y-4">
                <h3 class="flex items-center gap-2 font-semibold text-lg text-gray-700">
                    <x-heroicon-o-user class="w-5 h-5 text-blue-600"/>
                    Data Peserta
                </h3>

                <div class="grid grid-cols-[140px_1fr] gap-y-3 text-sm">
                    <span class="text-gray-500 font-medium">Nama</span>
                    <span class="font-semibold text-gray-800">
                        {{ $pengajuan->user->name }}
                    </span>

                    <span class="text-gray-500 font-medium">Email</span>
                    <span class="text-gray-800">
                        {{ $pengajuan->user->email }}
                    </span>

                    <span class="text-gray-500 font-medium">Bagian</span>
                    <span class="text-gray-800">
                        {{ $pengajuan->user->profile?->bagian?->nama ?? '-' }}
                    </span>

                    <span class="text-gray-500 font-medium">Status Magang</span>
                    <span class="text-gray-800">
                        {{ $pengajuan->user->profile?->status_magang ?? '-' }}
                    </span>
                </div>
            </x-card>

            {{-- ================= DETAIL PENGAJUAN ================= --}}
            <x-card class="space-y-4">
                <h3 class="flex items-center gap-2 font-semibold text-lg text-gray-700">
                    <x-heroicon-o-document-text class="w-5 h-5 text-blue-600"/>
                    Detail Pengajuan
                </h3>

                <div class="grid grid-cols-[140px_1fr] gap-y-3 text-sm">

                    <span class="text-gray-500 font-medium">Status</span>
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                            {{ $pengajuan->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $pengajuan->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $pengajuan->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                        ">
                            {{ ucfirst($pengajuan->status) }}
                        </span>
                    </span>

                    <span class="text-gray-500 font-medium">Tanggal</span>
                    <span class="text-gray-800">
                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }}
                        –
                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}
                    </span>

                    <span class="text-gray-500 font-medium">Keterangan</span>
                    <span class="text-gray-800">
                        {{ $pengajuan->keterangan ?? '-' }}
                    </span>

                    <span class="text-gray-500 font-medium">Dokumen</span>
                    <span>
                        @if($pengajuan->file_pdf)
                            <a href="{{ asset('storage/'.$pengajuan->file_pdf) }}"
                               target="_blank"
                               class="inline-flex items-center gap-1 text-blue-600 hover:underline font-semibold">
                                <x-heroicon-o-document-arrow-down class="w-4 h-4"/>
                                Lihat Dokumen
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </span>
                </div>
            </x-card>

        </div>

        {{-- INFO --}}
        <x-card class="bg-blue-50 border border-blue-100">
            <p class="text-sm text-blue-700 flex items-center gap-2">
                <x-heroicon-o-information-circle class="w-5 h-5"/>
                Pengajuan ini hanya dapat <b>disetujui atau ditolak oleh Admin</b>.
            </p>
        </x-card>

    </div>

</x-apppembimbing-layout>