<x-appuser-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <span class="font-semibold text-[#0D1B2A]">Pengajuan Izin</span>
    </x-slot>

    {{-- TOAST --}}
    <x-user-toast />

    @if(session('success'))
        <script>
            window.addEventListener('load', () => {
                window.dispatchEvent(new CustomEvent('user-toast', {
                    detail: {
                        title: 'Berhasil',
                        message: @json(session('success'))
                    }
                }));
            });
        </script>
    @endif

    @php
        $minDate = now()->format('Y-m-d');
    @endphp

    <div
        x-data="{
            openForm: {{ $errors->any() ? 'true' : 'false' }},
            startDate: '{{ old('tanggal_mulai') }}'
        }"
        class="mb-6 space-y-6"
    >

        {{-- CARD --}}
        <x-card class="p-0 overflow-hidden">

            {{-- HEADER CARD --}}
            <div class="px-6 mt-6 flex justify-between items-center">
                <div class="flex items-center gap-2 text-[#123B6E] font-semibold">
                    <x-heroicon-o-document-text class="w-5 h-5"/>
                    Riwayat Pengajuan Izin
                </div>

                <x-user-button
                    icon="heroicon-o-plus-circle"
                    @click="openForm = true"
                >
                    Ajukan Izin
                </x-user-button>
            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b">
                        <tr class="text-left text-gray-600 text-xs uppercase tracking-wide">
                            <th class="px-6 py-3 font-medium">Tanggal</th>
                            <th class="px-6 py-3 font-medium">Keterangan</th>
                            <th class="px-6 py-3 font-medium">Dokumen</th>
                            <th class="px-6 py-3 font-medium text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse($pengajuans as $item)
                            <tr class="hover:bg-gray-50">

                                {{-- TANGGAL --}}
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800">
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                    –
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                                </td>

                                {{-- KETERANGAN --}}
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $item->keterangan ?? '-' }}
                                </td>

                                {{-- FILE --}}
                                <td class="px-6 py-4">
                                    @if($item->file_pdf)
                                        <a
                                            href="{{ asset('storage/'.$item->file_pdf) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1 text-[#123B6E] hover:underline"
                                        >
                                            <x-heroicon-o-document-arrow-down class="w-4 h-4"/>
                                            Lihat PDF
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusClass = [
                                            'pending'  => 'bg-yellow-100 text-yellow-700',
                                            'approved' => 'bg-green-100 text-green-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                        ];
                                    @endphp

                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $statusClass[$item->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-gray-500">
                                    <x-heroicon-o-folder-minus class="w-10 h-10 mx-auto text-gray-300"/>
                                    <p>Belum ada pengajuan izin</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </x-card>


        {{-- MODAL FORM --}}
        <div
            x-show="openForm"
            x-cloak
            x-transition.opacity
            class="fixed inset-0 z-40 flex items-center justify-center bg-black/50"
        >
            <x-card class="w-full max-w-md space-y-5">

                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-lg text-[#123B6E]">
                        Ajukan Izin
                    </h3>
                    <button @click="openForm = false">
                        <x-heroicon-o-x-mark class="w-5 h-5"/>
                    </button>
                </div>

                {{-- ERROR --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700">
                        <ul class="list-disc pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('user.pengajuan.store') }}"
                    enctype="multipart/form-data"
                    x-ref="pengajuanForm"
                    @submit-pengajuan.window="$el.submit()"
                    class="space-y-4"
                >
                    @csrf

                    <div>
                        <x-input-label>Tanggal Mulai</x-input-label>
                        <x-input
                            type="date"
                            name="tanggal_mulai"
                            min="{{ $minDate }}"
                            x-model="startDate"
                            value="{{ old('tanggal_mulai') }}"
                            required
                        />
                    </div>

                    <div>
                        <x-input-label>Tanggal Selesai</x-input-label>
                        <x-input
                            type="date"
                            name="tanggal_selesai"
                            x-bind:min="startDate || '{{ $minDate }}'"
                            value="{{ old('tanggal_selesai') }}"
                            required
                        />
                    </div>

                    <div>
                        <x-input-label>Keterangan</x-input-label>
                        <textarea
                            name="keterangan"
                            class="w-full rounded-lg border-gray-300"
                        >{{ old('keterangan') }}</textarea>
                    </div>

                    <div>
                        <x-input-label>Dokumen (PDF)</x-input-label>
                        <x-input type="file" name="file_pdf" accept="application/pdf" />
                    </div>

                    <div class="flex gap-2 pt-4 border-t">
                        <x-user-button
                            type="button"
                            variant="secondary"
                            class="flex-1"
                            @click="openForm = false"
                        >
                            Batal
                        </x-user-button>

                        <x-user-button
                            type="button"
                            class="flex-1"
                            @click="$dispatch('open-user-confirm', { id: 'confirm-pengajuan' })"
                        >
                            Kirim
                        </x-user-button>
                    </div>
                </form>
            </x-card>
        </div>

        {{-- CONFIRM --}}
        <x-user-confirm-modal
            id="confirm-pengajuan"
            title="Kirim Pengajuan"
            message="Apakah Anda yakin ingin mengirim pengajuan izin ini?"
        >
            <x-user-button
                type="button"
                @click="
                    openForm = false;
                    $dispatch('close-user-confirm', { id: 'confirm-pengajuan' });
                    $dispatch('submit-pengajuan');
                "
            >
                Ya, Kirim
            </x-user-button>
        </x-user-confirm-modal>

    </div>

</x-appuser-layout>
