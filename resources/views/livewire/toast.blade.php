<div
    @if(!$show) style="display: none;" @endif
    wire:transition.opacity
    class="fixed top-4 right-4 p-4 rounded shadow text-white {{ $type === 'success' ? 'bg-green-600' : 'bg-red-500' }}"
    style="z-index: 9999;"
    @toast-hide.window="setTimeout(() => $wire.hide(), event.detail.duration)"
>
    {{ $message }}
</div>