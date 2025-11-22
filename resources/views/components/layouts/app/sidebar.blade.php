<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @PwaHead
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route(Auth::user()->hasRole('admin') ? 'dashboard' : 'user.dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>
            <flux:navlist variant="outline">
                {{Auth::user()->getFullNameAttribute()}}
                <flux:navlist.group :heading="__('Menu')" class="grid">
                    
                    <flux:navlist.item icon="home" :href="route(Auth::user()->hasRole('admin') ? 'dashboard' : 'user.dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                    @can('view payment methods')
                    <flux:navlist.item icon="credit-card" :href="route('payment-methods')" :current="request()->routeIs('payment-methods')" wire:navigate>{{ __('Payment Methods') }}</flux:navlist.item>
                    @endcan
                    @if(Auth::user()->canAny(['view payments', 'view advance payments']))
                    <flux:sidebar.group expandable heading="Payments" class="grid">
                        @can('view payments')
                        <flux:sidebar.item icon="credit-card" :href="route('payments')" :current="request()->routeIs('payments')" wire:navigate>Payments</flux:sidebar.item>
                        @endcan
                        @can('view advance payments')
                        <flux:sidebar.item icon="banknotes" :href="route('advance-payments')" :current="request()->routeIs('advance-payments')" wire:navigate>Advance Payments</flux:sidebar.item>
                        @endcan
                    </flux:sidebar.group>
                    @endif
                    @can('view plans')
                    <flux:navlist.item icon="globe-alt" :href="route('plans')" :current="request()->routeIs('plans')" wire:navigate>{{ __('Plans') }}</flux:navlist.item>
                    @endcan
                    @can('view subscribers')
                    <flux:navlist.item icon="user-group" :href="route('subscribers')" :current="request()->routeIs('subscribers')" wire:navigate>{{ __('Subscribers') }}</flux:navlist.item>
                    @endcan
                    @can('view subscriptions')
                    <flux:navlist.item icon="bell-alert" :href="route('subscriptions')" :current="request()->routeIs('subscriptions')" wire:navigate>{{ __('Subscriptions') }}</flux:navlist.item>
                    @endcan
                    @canany(['view sectors', 'view passive optical networks', 'view napboxes', 'view splitters'])
                    <flux:sidebar.group expandable heading="Network" class="grid">
                        <flux:sidebar.item icon="chart-pie" :href="route('sectors')" :current="request()->routeIs('sectors')" wire:navigate>Sectors</flux:sidebar.item>
                        <flux:sidebar.item :href="route('pons')" :current="request()->routeIs('pons')" wire:navigate>PONs</flux:sidebar.item>
                        <flux:sidebar.item :href="route('napboxes')" :current="request()->routeIs('napboxes')"  wire:navigate>Napboxes</flux:sidebar.item>
                        <flux:sidebar.item :href="route('splitters')" :current="request()->routeIs('splitters')"  wire:navigate>Splitters</flux:sidebar.item>
                    </flux:sidebar.group>
                    @endcanany
                    @hasrole('admin')
                        <flux:navlist.item icon="cog" :href="route('uac')" :current="request()->routeIs('uac')" wire:navigate>{{ __('User Access Control') }}</flux:navlist.item>
                        @can('view users')
                        <flux:navlist.item icon="users" :href="route('users')" :current="request()->routeIs('users')" wire:navigate>{{ __('Users') }}</flux:navlist.item>
                        @endcan
                    @endhasrole
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>
    
        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>
        {{ $slot }}
        @fluxScripts
        @RegisterServiceWorkerScript
    </body>
</html>
