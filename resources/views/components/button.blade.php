@props([
    'type' => 'button',
    'variant' => 'secondary',
    'size' => 'md',
    'icon' => null,
    'class' => '',
])

@php
$baseClass = 'inline-flex items-center justify-center gap-2 rounded-lg font-semibold transition duration-200 focus:outline-none';

$variants = [
    'primary'   => 'bg-[#0D1B2A] text-white hover:bg-[#324463]',
    'secondary' => 'bg-gray-400 text-white hover:bg-gray-500',
    'danger'    => 'bg-red-600 text-white hover:bg-red-700',
    'success'   => 'bg-green-600 text-white hover:bg-green-700',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-sm min-h-[32px]',
    'md' => 'px-4 py-2 text-sm min-h-[40px]',
    'lg' => 'px-5 py-3 text-base min-h-[48px]',
];

$variantClass = $variants[$variant] ?? $variants['secondary'];
$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "$baseClass $variantClass $sizeClass $class"]) }}
>
    @if($icon)
        <x-dynamic-component :component="$icon" class="w-4 h-4" />
    @endif

    {{ $slot }}
</button>