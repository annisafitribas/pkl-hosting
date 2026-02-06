@props([
    'label' => '',
    'type' => 'text',
    'name',
    'value' => ''
])

<div>
    <label class="block text-sm font-medium text-gray-700">
        {{ $label }}
    </label>

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $value }}"
        {{ $attributes->merge([
            'class' => 'mt-1 w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-blue-500 focus:border-blue-500'
        ]) }}
    >
</div>
