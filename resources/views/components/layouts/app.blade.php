<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        <livewire:toast />
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
