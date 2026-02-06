@props([
    'name',
    'value' => null,
    'options' => [],
    'placeholder' => 'Pilih',
    'submit' => false,
])

<div
    x-data="{ open:false, selected:@js($value) }"
    class="relative w-full"
    x-modelable="selected"
>
    <input type="hidden" name="{{ $name }}" :value="selected">

    {{-- trigger --}}
    <button type="button"
        @click="open = !open"
        class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl border
               bg-white text-sm font-medium text-gray-700 hover:border-[#0D1B2A]">
        <span
            x-text="
                selected
                ? Object.entries(@js($options)).find(([k]) => k == selected)?.[1]
                : '{{ $placeholder }}'
            "
        ></span>
        <x-heroicon-o-chevron-down class="w-4 h-4 text-gray-400" />
    </button>

    {{-- dropdown --}}
    <div x-show="open" x-transition @click.outside="open=false"
         class="absolute z-20 mt-2 w-full bg-white border rounded-xl shadow-lg p-1">

        @foreach ($options as $key => $label)
            <button
                type="button"
                @click="
                    selected='{{ $key }}';
                    open=false;
                    $dispatch('select-change', { name:'{{ $name }}', value:selected });
                    {{ $submit ? 'setTimeout(() => $el.closest(\'form\').submit(), 100)' : '' }}
                "
                class="
                    w-full text-left px-4 py-2 text-sm
                    rounded-lg
                    hover:bg-[#0D1B2A]/10
                    transition
                "
            >
                {{ $label }}
            </button>
        @endforeach
    </div>
</div>
