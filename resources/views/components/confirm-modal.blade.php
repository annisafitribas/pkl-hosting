@props([
    'id',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin?',
    'cancelText' => 'Batal',
    'variant' => 'danger',
])

@php
$colors = [
    'danger' => 'text-red-600 bg-red-500/10',
    'primary' => 'text-blue-600 bg-blue-500/10',
];
@endphp

<div
    x-data="{ open: false }"
    x-cloak
    x-show="open"
    x-on:open-confirm.window="
        if ($event.detail.id === '{{ $id }}') open = true
    "
    x-on:keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center"
>
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
         @click="open = false"></div>

    <div x-transition
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex justify-center mb-4">
            <div class="w-14 h-14 flex items-center justify-center rounded-full {{ $colors[$variant] }}">
                <x-heroicon-o-exclamation-triangle class="w-7 h-7" />
            </div>
        </div>

        <h3 class="text-lg font-semibold text-center mb-2">{{ $title }}</h3>
        <p class="text-sm text-center text-gray-600 mb-6">{{ $message }}</p>

        <div class="flex justify-center gap-3">
            <button type="button"
                @click="open = false"
                class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
                {{ $cancelText ?? 'Batal' }}
            </button>

            {{ $slot }}
        </div>
    </div>
</div>
