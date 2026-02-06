@props([
    'duration' => 1500,
])

<div
    x-data="{
        show: false,
        title: '',
        message: '',
        icon: 'check',
        open(payload) {
            this.title = payload.title ?? 'Berhasil';
            this.message = payload.message ?? '';
            this.icon = payload.icon ?? 'check';
            this.show = true;

            setTimeout(() => {
                this.show = false;
            }, {{ $duration }});
        }
    }"
    x-on:user-toast.window="open($event.detail)"
    x-show="show"
    x-transition.opacity.scale
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
>
    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm text-center space-y-3">

        {{-- ICON --}}
        <div class="mx-auto flex items-center justify-center
                    w-14 h-14 rounded-full
                    bg-green-100">
            <x-heroicon-o-check-circle
                x-show="icon === 'check'"
                class="w-8 h-8 text-green-600"
            />
        </div>

        {{-- TITLE --}}
        <h3 class="text-lg font-semibold text-gray-800"
            x-text="title">
        </h3>

        {{-- MESSAGE --}}
        <p class="text-sm text-gray-500"
           x-text="message">
        </p>
    </div>
</div>
