@props([
    'label' => null,
])

<td class="px-6 py-4 text-gray-700 align-top">
    @if($label)
        <div class="md:hidden text-xs text-gray-400 mb-1">
            {{ $label }}
        </div>
    @endif

    {{ $slot }}
</td>
