<x-appadmin-layout>

    {{-- HEADER --}}
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
                <x-heroicon-o-user class="w-6 h-6 text-[#0D1B2A]" />
                <h2 class="text-lg font-semibold text-[#0D1B2A]">
                    {{ $user->name }}
                </h2>
            </div>

            @if ($presensis->count())
                <x-table>
                    <thead>
                        <tr>
                            <x-table-th>Tanggal</x-table-th>
                            <x-table-th align="center">Status</x-table-th>
                            <x-table-th>Keterangan</x-table-th>
                            <x-table-th align="center">Jam Masuk</x-table-th>
                            <x-table-th align="center">Jam Keluar</x-table-th>
                            <x-table-th align="center">Aksi</x-table-th>
                        </tr>
                    </thead>

                    <tbody class="text-[#0D1B2A]">
                        @foreach ($presensis as $p)
                            <tr class="hover:bg-[#0D1B2A1A] even:bg-[#F8FAFC] transition">

                                {{-- TANGGAL --}}
                                <x-table-td>
                                    {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                                </x-table-td>

                                {{-- STATUS BADGE --}}
                                <x-table-td align="center">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                        @if ($p->status === 'hadir') bg-green-100 text-green-700
                                        @elseif ($p->status === 'telat') bg-yellow-100 text-yellow-700
                                        @elseif ($p->status === 'sakit') bg-purple-100 text-purple-700
                                        @elseif ($p->status === 'izin') bg-blue-100 text-blue-700
                                        @else bg-red-100 text-red-700
                                        @endif">
                                        {{ $p->status === 'tidak_hadir' ? 'Alpha' : ucfirst($p->status) }}
                                    </span>
                                </x-table-td>

                                {{-- KETERANGAN --}}
                                <x-table-td>
                                    {{ $p->keterangan ?? '-' }}
                                </x-table-td>

                                {{-- JAM --}}
                                <x-table-td align="center">
                                    {{ $p->jam_masuk ?? '-' }}
                                </x-table-td>

                                <x-table-td align="center">
                                    {{ $p->jam_keluar ?? '-' }}
                                </x-table-td>

                                {{-- AKSI --}}
                                <x-table-td align="center">
                                    <button
                                        type="button"
                                        onclick="openEditModal({{ $p->id }})"
                                        class="text-blue-600 hover:underline text-m font-semibold">
                                        Edit
                                    </button>
                                </x-table-td>
                            </tr>

                            {{-- MODAL EDIT --}}
                            <div
                                id="edit-modal-{{ $p->id }}"
                                class="fixed inset-0 hidden z-40 bg-black/50 flex items-center justify-center"
                            >
                                <div class="bg-white p-6 rounded-xl w-11/12 max-w-md space-y-4"
                                     x-data="{ status: '{{ $p->status }}' }"
                                     @select-change.window="
                                        if ($event.detail.name === 'status') {
                                            status = $event.detail.value;
                                            if (['sakit','izin','tidak_hadir'].includes(status)) {
                                                $el.querySelector('[name=jam_masuk]').value = '';
                                                $el.querySelector('[name=jam_keluar]').value = '';
                                            }
                                        }
                                     "
                                >

                                    <h3 class="font-semibold text-lg text-center">
                                        Update Presensi
                                        {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                                    </h3>

                                    <form method="POST"
                                          id="form-presensi-{{ $p->id }}"
                                          action="{{ route('admin.presensi.update', $p->id) }}"
                                          class="space-y-3">
                                        @csrf
                                        @method('PATCH')

                                        {{-- STATUS --}}
                                        <div>
                                            <label class="text-sm text-gray-600 mb-1 block">
                                                Status
                                            </label>

                                            <x-select-box
                                                name="status"
                                                :value="$p->status"
                                                :options="[
                                                    'hadir' => 'Hadir',
                                                    'telat' => 'Telat',
                                                    'sakit' => 'Sakit',
                                                    'izin' => 'Izin',
                                                    'tidak_hadir' => 'Alpha'
                                                ]"
                                                placeholder="Pilih status"
                                            />
                                        </div>

                                        {{-- JAM MASUK --}}
                                        <div>
                                            <label class="text-sm text-gray-600">
                                                Jam Masuk
                                            </label>
                                            <input
                                                type="time"
                                                name="jam_masuk"
                                                value="{{ $p->jam_masuk }}"
                                                class="w-full border rounded-lg p-2"
                                                :disabled="['sakit','izin','tidak_hadir'].includes(status)"
                                            >
                                        </div>

                                        {{-- JAM KELUAR --}}
                                        <div>
                                            <label class="text-sm text-gray-600">
                                                Jam Keluar
                                            </label>
                                            <input
                                                type="time"
                                                name="jam_keluar"
                                                value="{{ $p->jam_keluar }}"
                                                class="w-full border rounded-lg p-2"
                                                :disabled="['sakit','izin','tidak_hadir'].includes(status)"
                                            >
                                        </div>
                                            <p
                                                x-show="['hadir','telat'].includes(status)"
                                                class="text-xs text-green-600 mt-1"
                                            >
                                                Silahkan rubah jam masuk dan jam keluar.
                                            </p>

                                            <p
                                                x-show="['sakit','izin','tidak_hadir'].includes(status)"
                                                class="text-xs text-red-600 font-semibold mt-1"
                                            >
                                                Untuk status ini, jam masuk dan jam keluar TIDAK digunakan.
                                            </p>


                                        {{-- KETERANGAN --}}
                                        <div>
                                            <label class="text-sm text-gray-600">
                                                Keterangan
                                            </label>
                                            <textarea
                                                name="keterangan"
                                                rows="2"
                                                class="w-full border rounded-lg p-2"
                                            >{{ $p->keterangan }}</textarea>
                                        </div>

                                        {{-- ACTION --}}
                                        <div class="flex justify-end gap-2 pt-2">
                                            <button
                                                type="button"
                                                onclick="closeEditModal({{ $p->id }})"
                                                class="px-4 py-2 bg-gray-200 rounded-lg">
                                                Batal
                                            </button>

                                            <button
                                                type="button"
                                                onclick="
                                                    window.dispatchEvent(
                                                        new CustomEvent('open-confirm', {
                                                            detail: { id: 'confirm-presensi-{{ $p->id }}' }
                                                        })
                                                    )
                                                "
                                                class="px-4 py-2 bg-[#0D1B2A] text-white rounded-lg">
                                                Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- MODAL KONFIRMASI --}}
                            <x-confirm-modal
                                id="confirm-presensi-{{ $p->id }}"
                                title="Konfirmasi Perubahan"
                                message="Apakah Anda yakin ingin memperbarui presensi tanggal {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}?"
                                variant="primary"
                            >
                                <button
                                    type="button"
                                    class="px-4 py-2 rounded-lg bg-[#0D1B2A] text-white"
                                    @click="
                                        $dispatch('close-confirm', { id: 'confirm-presensi-{{ $p->id }}' });
                                        document.getElementById('form-presensi-{{ $p->id }}').submit();
                                    "
                                >
                                    Ya, Yakin
                                </button>
                            </x-confirm-modal>

                        @endforeach
                    </tbody>
                </x-table>
            @else
                <div class="text-center py-10 text-gray-500">
                    Belum ada presensi
                </div>
            @endif

        </x-card>
    </div>

    {{-- SCRIPT --}}
    <script>
        function openEditModal(id) {
            document.getElementById('edit-modal-' + id).classList.remove('hidden');
        }

        function closeEditModal(id) {
            document.getElementById('edit-modal-' + id).classList.add('hidden');
        }
    </script>

</x-appadmin-layout>
