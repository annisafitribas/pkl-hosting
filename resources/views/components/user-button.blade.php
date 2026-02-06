@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'class' => '',
])

@php
$baseClass = 'inline-flex items-center justify-center gap-2 rounded-lg font-semibold transition duration-200 focus:outline-none';

$variants = [
    'primary'   => 'bg-[#123B6E] text-white hover:bg-[#0F325C]',
    'secondary' => 'bg-gray-100 text-gray-700 hover:bg-gray-200',
    'danger'    => 'bg-red-600 text-white hover:bg-red-700',
    'success'   => 'bg-green-600 text-white hover:bg-green-700',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-sm min-h-[32px]',
    'md' => 'px-4 py-2 text-sm min-h-[40px]',
    'lg' => 'px-5 py-3 text-base min-h-[48px]',
];

$variantClass = $variants[$variant] ?? $variants['primary'];
$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "$baseClass $variantClass $sizeClass $class"]) }}
>
    @if($icon)
        <x-dynamic-component :component="$icon" class="w-5 h-5" />
    @endif

    {{ $slot }}
</button>
