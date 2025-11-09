<div>
    @if($show)
        <div
            wire:transition.opacity
            class="fixed top-4 right-4 rounded shadow text-white p-4 {{ $type === 'success' ? 'bg-green-600' : 'bg-red-500'}}"
            style="z-index: 9999;"
        >
            {{ $message }}
        </div>
    @endif
</div>

