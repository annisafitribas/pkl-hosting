@props(['status'])

@if ($status)
    <div class="flex flex-col items-center gap-3 mb-4">

        {{-- LOGO --}}
        @php
            $logo = !empty(config('app.logo'))
                ? asset('storage/' . config('app.logo'))
                : asset('favicon.png');
        @endphp

        <img
            src="{{ $logo }}"
            alt="Logo"
            class="w-16 h-16 object-contain"
        >

        {{-- STATUS MESSAGE --}}
        <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600 text-center']) }}>
            {{ $status }}
        </div>

    </div>
@endif