<x-appadmin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-[#0D1B2A]"><span class="font-semibold">Bagian</span></div>
    </x-slot>

    <div class="container mx-auto">
        <x-card>
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-[#0D1B2A]">Edit Data Bagian</h2>
                <p class="text-sm text-gray-600 mt-1">Perbarui data bagian sesuai kebutuhan</p>
            </div>

            @if ($errors->any())
                <x-info variant="danger">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </x-info>
            @endif

            <form x-data action="{{ route('admin.bagian.update', $bagian->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <x-card title="Informasi Bagian">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label value="Nama Bagian" />
                            <x-text-input name="nama_bagian" class="w-full mt-1"
                                placeholder="Contoh: Keuangan"
                                :value="old('nama_bagian', $bagian->nama_bagian)" />
                            <x-input-error :messages="$errors->get('nama_bagian')" />
                        </div>
                    </div>
                </x-card>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <x-button-link href="{{ route('admin.bagian.index') }}" variant="secondary">Batal</x-button-link>

                <x-button type="button" variant="primary" @click="window.dispatchEvent(
                    new CustomEvent('open-confirm', {detail: { id: 'update-bagian-{{ $bagian->id }}' }}) )">
                    Simpan
                </x-button>

                <x-confirm-modal id="update-bagian-{{ $bagian->id }}" title="Simpan Perubahan"
                    message="Apakah Anda yakin ingin menyimpan perubahan pada bagian '{{ $bagian->nama_bagian }}'?" variant="primary">
                    <x-button type="submit" variant="primary">
                        Ya, Simpan
                    </x-button>
                </x-confirm-modal>

            </form>
        </x-card>
    </div>
</x-appadmin-layout>
