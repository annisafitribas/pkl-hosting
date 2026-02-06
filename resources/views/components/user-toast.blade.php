@props([
    'duration' => 1500,
])

<div
    x-data="{
        show: false,
        title: 'Berhasil',
        message: '',
        progress: 0,
        start() {
            this.progress = 0;
            this.show = true;

            let step = 100 / ({{ $duration }} / 16);
            let interval = setInterval(() => {
                this.progress += step;
                if (this.progress >= 100) {
                    clearInterval(interval);
                    this.show = false;
                }
            }, 16);
        }
    }"
    x-on:user-toast.window="
        title = $event.detail.title ?? 'Berhasil';
        message = $event.detail.message ?? '';
        start();
    "
    x-show="show"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center"
>
    <!-- BACKDROP -->
    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>

    <!-- TOAST CARD -->
    <div class="relative bg-white rounded-2xl shadow-2xl px-7 py-6 w-full max-w-sm text-center">

        {{-- PROGRESS RING --}}
        <div class="flex justify-center mb-4 relative">
            <svg class="w-16 h-16 rotate-[-90deg]">
                <circle
                    cx="32"
                    cy="32"
                    r="28"
                    stroke-width="4"
                    class="text-gray-200"
                    stroke="currentColor"
                    fill="transparent"
                />
                <circle
                    cx="32"
                    cy="32"
                    r="28"
                    stroke-width="4"
                    class="text-green-600 transition-all duration-75"
                    stroke="currentColor"
                    fill="transparent"
                    stroke-dasharray="176"
                    :stroke-dashoffset="176 - (176 * progress / 100)"
                />
            </svg>

            {{-- ICON --}}
            <div class="absolute inset-0 flex items-center justify-center">
                <x-heroicon-o-check class="w-7 h-7 text-green-600" />
            </div>
        </div>

        {{-- TITLE --}}
        <h3 class="text-base font-semibold text-gray-800 mb-1"
            x-text="title">
        </h3>

        {{-- MESSAGE --}}
        <p class="text-sm text-gray-600"
           x-text="message">
        </p>
    </div>
</div>
