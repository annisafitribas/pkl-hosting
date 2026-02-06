@props([
    'align' => 'left', // left | center | right
    'width' => null,
])

@php
$alignClass = match($align) {
    'center' => 'text-center',
    'right' => 'text-right',
    default => 'text-left',
};
@endphp

<th {{ $attributes->merge([
    'class' => trim("
        px-4 py-3 font-semibold border-x border-[#2B3467]
        bg-[#BAD7E9] text-black
        {$alignClass}
        " . ($width ? "w-{$width}" : '')
    )
]) }}>
    {{ $slot }}
</th>
