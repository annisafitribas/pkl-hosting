<table class="min-w-full text-sm">
    <thead class="bg-gray-50 text-gray-600">
        {{ $head ?? '' }}
    </thead>

    <tbody class="divide-y">
        {{ $slot }}
    </tbody>
</table>
