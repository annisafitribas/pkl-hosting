@props(['href' => '#', 'variant' => 'secondary', 'icon' => null, 'class' => ''])

@php
$baseClass = 'inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg font-semibold transition duration-300 min-h-[40px]';
$variants = [
    'primary'   => 'bg-[#0D1B2A] text-white hover:bg-[#324463]',
    'secondary' => 'bg-gray-400 text-white hover:bg-gray-500',
    'danger'    => 'bg-red-600 text-white hover:bg-red-700',
];
$variantClass = $variants[$variant] ?? $variants['secondary'];
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => "$baseClass $variantClass $class"]) }}>
    @if($icon)
        <x-dynamic-component :component="$icon" class="w-5 h-5" />
    @endif
    {{ $slot }}
</a>
