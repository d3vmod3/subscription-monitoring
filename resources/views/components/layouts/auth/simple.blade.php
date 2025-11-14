<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white antialiased dark:bg-gradient-to-b dark:from-neutral-950 dark:to-neutral-900">
    <div class="flex min-h-screen items-center justify-center p-6 md:p-10">
        <div class="w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-md xl:max-w-md flex flex-col gap-6 bg-background p-1 sm:p-1 md:p-4 lg:p-6 xl:p-6 2xl:p-6 rounded-lg border-0 sm:border-0 md:border lg:border xl:border 2xl:border dark:bg-neutral-900 shadow-none sm:shadow-none md:shadow-none lg:shadow-lg xl:shadow-lg 2xl:shadow-lg">
            
            <!-- Logo & App Name -->
            <a href="{{ route('login') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                <span class="flex w-42 items-center justify-center rounded-md">
                    <img src="/logo.png" class="w-full h-full object-contain" alt="logo"/>
                </span>
                <span class="text-black dark:text-white text-lg sm:text-xl font-semibold">{{ config('app.name', 'Laravel') }}</span>
            </a>

            <!-- Slot / Form -->
            <div class="flex flex-col gap-6">
                {{ $slot }}
            </div>

        </div>
    </div>
    @fluxScripts
</body>
</html>
