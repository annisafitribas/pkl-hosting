<div class="space-y-1">
    @if($label)
        <div class="text-sm font-semibold text-gray-600">{{ $label }}</div>
    @endif

    <div class="text-sm">
        @if($link)
            <a href="{{ $link }}" class="text-indigo-600 hover:underline" target="_blank">
                {{ $value }}
            </a>
        @else
            {{ $value }}
        @endif
    </div>
</div>
