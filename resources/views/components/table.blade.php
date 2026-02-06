@props(['class' => ''])

<div class="overflow-x-auto">
    <table {{ $attributes->merge([
        'class' => 'min-w-full border border-[#CBD5E1] rounded-xl overflow-hidden shadow text-sm sm:text-base ' . $class
    ]) }}>
        {{ $slot }}
    </table>
</div>
