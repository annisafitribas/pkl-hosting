@props([
    'label',
    'value' => '-',
    'full' => false,
])

<div class="{{ $full ? 'md:col-span-2' : '' }} space-y-1">
    <p class="text-sm text-gray-500">{{ $label }}</p>
    <p class="font-semibold text-gray-800">
        {{ $value ?: '-' }}
    </p>
</div>
