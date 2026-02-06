<x-appadmin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-[#0D1B2A]">
            <span class="font-semibold">Konfirmasi Pengajuan Izin</span>
        </div>
    </x-slot>

    <div class="container mx-auto">
        <x-card>

            {{-- HEADER --}}
            <div class="flex items-center gap-2 mb-4">
                <x-heroicon-o-document-text class="w-6 h-6 text-[#0D1B2A]" />
                <h2 class="text-lg font-semibold text-[#0D1B2A]">
                    Daftar Pengajuan Izin
                </h2>
            </div>

            @if ($pengajuans->count())
                <x-table>
                    <thead>
                        <tr>
                            <x-table-th align="center" class="w-12 whitespace-nowrap">No</x-table-th>
                            <x-table-th>Nama</x-table-th>
                            <x-table-th>Tanggal Mulai</x-table-th>
                            <x-table-th>Tanggal Selesai</x-table-th>
                            <x-table-th>Keterangan</x-table-th>
                            <x-table-th align="center">File</x-table-th>
                            <x-table-th align="center">Status</x-table-th>
                            <x-table-th align="center">Aksi</x-table-th>
                        </tr>
                    </thead>

                    <tbody class="text-[#0D1B2A]">
                        @foreach ($pengajuans as $pengajuan)
                        <tr class="hover:bg-gray-50">
                            <x-table-td align="center">{{ $loop->iteration }}</x-table-td>

                            <x-table-td class="font-semibold">
                                {{ $pengajuan->user->name }}
                            </x-table-td>

                            <x-table-td>{{ $pengajuan->tanggal_mulai->format('d-m-Y') }}</x-table-td>
                            <x-table-td>{{ $pengajuan->tanggal_selesai->format('d-m-Y') }}</x-table-td>
                            <x-table-td>{{ $pengajuan->keterangan ?? '-' }}</x-table-td>

                            {{-- FILE --}}
                            <x-table-td align="center">
                                @if ($pengajuan->file_pdf)
                                    <a href="{{ asset('storage/'.$pengajuan->file_pdf) }}"
                                       target="_blank"
                                        class="inline-flex items-center gap-1 text-[#123B6E] hover:underline">
                                        <x-heroicon-o-document-arrow-down class="w-4 h-4"/>
                                        Lihat PDF
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </x-table-td>

                            {{-- STATUS --}}
                            <x-table-td align="center">
                                @php
                                    $statusClasses = [
                                        'pending'  => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$pengajuan->status] }}">
                                    {{ ucfirst($pengajuan->status) }}
                                </span>
                            </x-table-td>

                            {{-- AKSI --}}
                            <x-table-td align="center">
                                @if ($pengajuan->status === 'pending')
                                <button
                                    type="button"
                                    onclick="openStatusModal({{ $pengajuan->id }})"
                                    class="text-blue-600 hover:underline text-sm font-semibold">
                                    Update
                                </button>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </x-table-td>
                        </tr>

                        {{-- MODAL UPDATE STATUS --}}
                        <div id="status-modal-{{ $pengajuan->id }}"
                             class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
                            <div class="bg-white p-6 rounded-xl w-11/12 max-w-md space-y-4">

                                <h3 class="font-semibold text-lg text-center">
                                    Update Status Pengajuan
                                </h3>

{{-- OPSI STATUS --}}
<div class="grid grid-cols-2 gap-3">
    <button
        type="button"
        id="btn-approved-{{ $pengajuan->id }}"
        onclick="setStatus({{ $pengajuan->id }}, 'approved')"
        class="w-full py-2 rounded-xl border text-sm font-medium transition
               border-green-300 text-green-700
               hover:bg-green-50"
    >
        Disetujui
    </button>

    <button
        type="button"
        id="btn-rejected-{{ $pengajuan->id }}"
        onclick="setStatus({{ $pengajuan->id }}, 'rejected')"
        class="w-full py-2 rounded-xl border text-sm font-medium transition
               border-red-300 text-red-700
               hover:bg-red-50"
    >
        Ditolak
    </button>
</div>

                                {{-- CATATAN --}}
                                <textarea
                                    id="catatan-{{ $pengajuan->id }}"
                                    class="w-full border rounded-xl p-2"
                                    placeholder="Catatan admin (opsional)"
                                    rows="3"></textarea>

                                {{-- ACTION --}}
                                <div class="flex justify-end gap-2">
                                    <button
                                        type="button"
                                        onclick="closeStatusModal({{ $pengajuan->id }})"
                                        class="px-4 py-2 bg-gray-200 rounded-xl">
                                        Batal
                                    </button>

                                    <x-button
                                        ype="button" variant="primary"
                                        onclick="openConfirmModal({{ $pengajuan->id }})">
                                        Konfirmasi
                                    </x-button>
                                </div>

                                <input type="hidden" id="status-value-{{ $pengajuan->id }}">
                            </div>
                        </div>

                        {{-- MODAL KONFIRMASI FINAL --}}
                        <x-confirm-modal
                            id="confirm-{{ $pengajuan->id }}"
                            title="Update Status Pengajuan"
                            message="Apakah Anda yakin ingin memperbarui status pengajuan izin dari {{ $pengajuan->user->name }}?"
                            variant="primary">
                            <x-button
                                type="button"
                                @click="
                                    $dispatch('close-confirm', { id: 'confirm-{{ $pengajuan->id }}' });
                                    $dispatch('toast', {
                                        title: 'Diproses',
                                        message: 'Status pengajuan sedang diperbarui...'
                                    });
                                    document.getElementById('form-update-{{ $pengajuan->id }}').submit();
                                "
                                variant="primary">
                                Ya, Yakin
                            </x-button>

                            <form
                                id="form-update-{{ $pengajuan->id }}"
                                method="POST"
                                action="{{ route('admin.pengajuan.updateStatus', $pengajuan->id) }}"
                                class="hidden">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" id="final-status-{{ $pengajuan->id }}">
                                <input type="hidden" name="catatan_admin" id="final-catatan-{{ $pengajuan->id }}">
                            </form>
                        </x-confirm-modal>
                        @endforeach
                    </tbody>
                </x-table>
            @else
                <div class="text-center py-10 text-gray-500">
                    Belum ada pengajuan izin
                </div>
            @endif

        </x-card>
    </div>

    {{-- SCRIPT --}}
    <script>
        function openStatusModal(id) {
            document.getElementById('status-modal-' + id).classList.remove('hidden');
        }

        function closeStatusModal(id) {
            document.getElementById('status-modal-' + id).classList.add('hidden');
        }
            
        function setStatus(id, status) {
            document.getElementById('status-value-' + id).value = status;

            const btnApproved = document.getElementById('btn-approved-' + id);
            const btnRejected = document.getElementById('btn-rejected-' + id);

            // reset state
            btnApproved.classList.remove(
                'bg-green-100', 'border-green-500', 'text-green-800'
            );
            btnRejected.classList.remove(
                'bg-red-100', 'border-red-500', 'text-red-800'
            );

            // active state
            if (status === 'approved') {
                btnApproved.classList.add(
                    'bg-green-100', 'border-green-500', 'text-green-800'
                );
            } else if (status === 'rejected') {
                btnRejected.classList.add(
                    'bg-red-100', 'border-red-500', 'text-red-800'
                );
            }
        }


        function openConfirmModal(id) {
            document.getElementById('final-status-' + id).value =
                document.getElementById('status-value-' + id).value;

            document.getElementById('final-catatan-' + id).value =
                document.getElementById('catatan-' + id).value;

            closeStatusModal(id);

            window.dispatchEvent(new CustomEvent('open-confirm', {
                detail: { id: 'confirm-' + id }
            }));
        }
    </script>
</x-appadmin-layout>
