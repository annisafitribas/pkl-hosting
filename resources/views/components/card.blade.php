<div {{ $attributes->merge([
    'class' => 'bg-white p-6 rounded-2xl border border-gray-200 space-y-4'
]) }}>

    @if($title)
        <h3 class="font-semibold flex items-center gap-2 text-gray-800">
            @if($icon)
                <x-dynamic-component :component="$icon" class="w-5 h-5 text-indigo-600" />
            @endif
            {{ $title }}
        </h3>
    @endif

    {{ $slot }}
</div>