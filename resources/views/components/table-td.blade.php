@props([
    'align' => 'left', // left | center | right
])

@php
$alignClass = match($align) {
    'center' => 'text-center',
    'right' => 'text-right',
    default => 'text-left',
};
@endphp

<td {{ $attributes->merge([
    'class' => "
        px-4 py-2 border-t border-[#CBD5E1]
        {$alignClass}
    "
]) }}>
    {{ $slot }}
</td>
