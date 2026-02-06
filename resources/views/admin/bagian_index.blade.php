<x-appadmin-layout>
    <x-slot name="header">
        <span class="text-[#0D1B2A]">Bagian</span>
    </x-slot>

    <div class="container mx-auto">
        <x-card>

            {{-- HEADER --}}
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold flex items-center gap-2">
                    <x-heroicon-o-rectangle-stack class="w-6 h-6" />
                    Daftar Bagian
                </h2>

                <x-button
                    variant="primary"
                    icon="heroicon-o-plus-circle"
                    x-data
                    @click="window.dispatchEvent(
                        new CustomEvent('open-modal', { detail: 'modal-create-bagian' })
                    )">
                    Tambah Bagian
                </x-button>
            </div>

            {{-- TABLE --}}
            @if ($bagians->count())
                <x-table>
                    <thead>
                        <tr>
                            <x-table-th align="center" class="w-12 whitespace-nowrap">No</x-table-th>
                            <x-table-th>Nama Bagian</x-table-th>
                            <x-table-th>Kepala Bagian</x-table-th>
                            <x-table-th align="center">Aksi</x-table-th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($bagians as $bagian)
                            <tr class="hover:bg-slate-50">
                                <x-table-td align="center">
                                    {{ $loop->iteration }}
                                </x-table-td>

                                <x-table-td>
                                    {{ $bagian->nama }}
                                </x-table-td>

                                <x-table-td>
                                    {{ $bagian->kepala }}
                                </x-table-td>

                                <x-table-td align="center">
                                    <div class="flex justify-center gap-2">

                                    <div class="flex items-center justify-center gap-2">

                                    {{-- EDIT --}}
                                    <button type="button"
                                        class="p-1.5 rounded-md text-yellow-500 hover:bg-yellow-500/10"
                                        x-data
                                        @click="window.dispatchEvent(
                                            new CustomEvent('open-modal', {
                                                detail: 'modal-edit-bagian-{{ $bagian->id }}'
                                            })
                                        )">
                                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                                    </button>

                                    {{-- DELETE --}}
                                    @if (! $bagian->isUsed())
                                        <button type="button"
                                            class="p-1.5 rounded-md text-red-500 hover:bg-red-500/10"
                                            x-data
                                            @click="window.dispatchEvent(
                                                new CustomEvent('open-confirm', {
                                                    detail: { id: 'hapus-bagian-{{ $bagian->id }}' }
                                                })
                                            )">
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </button>
                                    @else
                                        <span
                                            class="p-1.5 rounded-md text-gray-400 cursor-not-allowed"
                                            title="Bagian sudah digunakan oleh user / pembimbing">
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </span>
                                    @endif

                                    </div>

                                    {{-- CONFIRM DELETE --}}
                                    <x-confirm-modal
                                        id="hapus-bagian-{{ $bagian->id }}"
                                        title="Hapus Bagian"
                                        message="Yakin ingin menghapus '{{ $bagian->nama }}'?"
                                        variant="danger"
                                    >
                                        <form
                                            action="{{ route('admin.bagian.destroy', $bagian->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <x-button type="submit" variant="danger">
                                                Ya, Hapus
                                            </x-button>
                                        </form>
                                    </x-confirm-modal>
                                </x-table-td>
                            </tr>

                            {{-- ================= EDIT MODAL ================= --}}
                            <x-modal
                                name="modal-edit-bagian-{{ $bagian->id }}"
                                maxWidth="lg"
                                focusable>
                                <div class="p-6">
                                    <h2 class="text-lg font-semibold mb-4">
                                        Edit Bagian
                                    </h2>

                                    <form
                                        action="{{ route('admin.bagian.update', $bagian->id) }}"
                                        method="POST"
                                        class="space-y-4">
                                        @csrf
                                        @method('PUT')

                                        <div>
                                            <x-input-label value="Nama Bagian" />
                                            <x-text-input
                                                name="nama"
                                                class="w-full mt-1"
                                                value="{{ $bagian->nama }}"
                                                required
                                            />
                                        </div>

                                        <div>
                                            <x-input-label value="Kepala Bagian" />
                                            <x-text-input
                                                name="kepala"
                                                class="w-full mt-1"
                                                value="{{ $bagian->kepala }}"
                                                required
                                            />
                                        </div>

                                        <div class="flex justify-end gap-2 pt-4">
                                            <x-button
                                                type="button"
                                                variant="secondary"
                                                x-data
                                                @click="window.dispatchEvent(
                                                    new CustomEvent('close-modal', {
                                                        detail: 'modal-edit-bagian-{{ $bagian->id }}'
                                                    })
                                                )">
                                                Batal
                                            </x-button>

                                            <x-button type="submit" variant="primary">
                                                Update
                                            </x-button>
                                        </div>
                                    </form>
                                </div>
                            </x-modal>
                        @endforeach
                    </tbody>
                </x-table>
            @else
                <div class="text-center py-10 text-gray-400">
                    Data bagian belum ada
                </div>
            @endif
        </x-card>
    </div>

    {{-- ================= CREATE MODAL ================= --}}
    <x-modal name="modal-create-bagian" maxWidth="lg" focusable>
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4">
                Tambah Bagian
            </h2>

            <form
                action="{{ route('admin.bagian.store') }}"
                method="POST"
                class="space-y-4">
                @csrf

                <div>
                    <x-input-label value="Nama Bagian" />
                    <x-text-input
                        name="nama"
                        class="w-full mt-1"
                        required
                    />
                </div>

                <div>
                    <x-input-label value="Kepala Bagian" />
                    <x-text-input
                        name="kepala"
                        class="w-full mt-1"
                        required
                    />
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <x-button
                        type="button"
                        variant="secondary"
                        x-data
                        @click="window.dispatchEvent(
                            new CustomEvent('close-modal', { detail: 'modal-create-bagian' })
                        )">
                        Batal
                    </x-button>

                    <x-button type="submit" variant="primary">
                        Simpan
                    </x-button>
                </div>
            </form>
        </div>
    </x-modal>
</x-appadmin-layout>
