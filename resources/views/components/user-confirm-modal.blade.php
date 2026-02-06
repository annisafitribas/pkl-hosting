@props([
    'id',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin?',
])

<div
    x-data="{ open: false }"
    x-cloak
    x-show="open"
    x-on:open-user-confirm.window="
        if ($event.detail.id === '{{ $id }}') open = true
    "
    x-on:close-user-confirm.window="
        if ($event.detail.id === '{{ $id }}') open = false
    "
    class="fixed inset-0 z-50 flex items-center justify-center"
>
    {{-- BACKDROP --}}
    <div
        class="absolute inset-0 bg-black/40 backdrop-blur-sm"
        @click="$dispatch('close-user-confirm', { id: '{{ $id }}' })"
    ></div>

    {{-- MODAL --}}
    <div
        x-transition
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6"
    >
        {{-- ICON --}}
        <div class="flex justify-center mb-4">
            <div class="w-14 h-14 flex items-center justify-center rounded-full bg-[#123B6E]/10">
                <x-heroicon-o-check-circle class="w-7 h-7 text-[#123B6E]" />
            </div>
        </div>

        {{-- TITLE --}}
        <h3 class="text-lg font-semibold text-center mb-2">
            {{ $title }}
        </h3>

        {{-- MESSAGE --}}
        <p class="text-sm text-center text-gray-600 mb-6">
            {{ $message }}
        </p>

        {{-- ACTION --}}
        <div class="flex justify-center gap-3">
            <x-user-button
                variant="secondary"
                @click="$dispatch('close-user-confirm', { id: '{{ $id }}' })"
            >
                Batal
            </x-user-button>

            {{ $slot }}
        </div>
    </div>
</div>
