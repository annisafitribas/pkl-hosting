@props([
    'href' => '#',
    'variant' => 'primary',
    'icon' => null,
    'class' => '',
])

@php
$baseClass = 'inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg font-semibold transition duration-200 min-h-[40px]';

$variants = [
    'primary'   => 'bg-[#123B6E] text-white hover:bg-[#0F325C]',
    'secondary' => 'bg-gray-100 text-gray-700 hover:bg-gray-200',
    'danger'    => 'bg-red-600 text-white hover:bg-red-700',
];

$variantClass = $variants[$variant] ?? $variants['primary'];
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => "$baseClass $variantClass $class"]) }}
>
    @if($icon)
        <x-dynamic-component :component="$icon" class="w-5 h-5" />
    @endif

    {{ $slot }}
</a>
