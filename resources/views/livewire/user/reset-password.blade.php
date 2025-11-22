<div class="flex justify-center px-4 py-10">
    
    @if(Auth::user()->is_password_reset)
    <div class="w-full max-w-md space-y-6">
        <!-- Title -->
        <h2 class="text-xl md:text-2xl font-semibold text-center text-zinc-800 dark:text-white">
            Reset Your Password
        </h2>

        <!-- Password Field -->
        <div class="space-y-1">
            <flux:input
                label="New Password"
                type="password"
                wire:model.live="password"
                viewable
                placeholder="Enter new password"
                required
            />
            @error('password')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1">
            <flux:input
                label="Confirm Password"
                type="password"
                wire:model.live="confirm_password"
                viewable
                placeholder="Re-enter password"
                required
            />
            @error('confirm_password')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <flux:button
            variant="primary"
            wire:click="save"
            class="w-full"
        >
            Update Password
        </flux:button>
    </div>
    @else
    {{ $default_message ? $default_message : ''}}
    @endif

    <script>
    window.addEventListener('redirect-after-toast', () => {
        setTimeout(() => {
            window.location.href = "{{ Auth::user()->hasRole('admin') ? route('dashboard') : route('user.dashboard') }}";
        }, 3000);
    });
</script>
</div>
