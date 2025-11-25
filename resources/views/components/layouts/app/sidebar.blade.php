<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        
        @livewireStyles
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route(Auth::user()->hasRole('admin') ? 'dashboard' : 'user.dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>
            <!-- theme selection -->
            <div x-data="{ appearances: ['light', 'dark', 'system'], current: $flux.appearance }"
                x-init="$watch('current', value => $flux.appearance = value)">
                <flux:button 
                    @click="current = appearances[(appearances.indexOf(current) + 1) % appearances.length]"
                    class="transition w-full">
                    
                    <template x-if="current === 'light'">
                        <span class="flex items-center space-x-1">
                            <flux:icon.sun class="w-5 h-5" />
                            <span>Light</span>
                        </span>
                    </template>

                    <template x-if="current === 'dark'">
                        <span class="flex items-center space-x-1">
                            <flux:icon.moon class="w-5 h-5" />
                            <span>Dark</span>
                        </span>
                    </template>

                    <template x-if="current === 'system'">
                        <span class="flex items-center space-x-1">
                            <flux:icon.computer-desktop class="w-5 h-5" />
                            <span>System</span>
                        </span>
                    </template>
                </flux:button>
            </div>
            <flux:navlist variant="outline">
                {{Auth::user()->getFullNameAttribute()}}
                <flux:navlist.group :heading="__('Menu')" class="grid">
                    @can('view dashboard')
                    <flux:navlist.item icon="home" :href="route(Auth::user()->hasRole('admin') ? 'dashboard' : 'user.dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                    @endcan
                    @can('view dashboard')
                    <flux:navlist.item icon="clipboard-document-list" :href="route('expenses')" :current="request()->routeIs('expenses')" wire:navigate>{{ __('Expenses') }}</flux:navlist.item>
                    @endcan
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
            <div x-data="{ appearances: ['light', 'dark', 'system'], current: $flux.appearance }"
                x-init="$watch('current', value => $flux.appearance = value)">
                <flux:button 
                    @click="current = appearances[(appearances.indexOf(current) + 1) % appearances.length]"
                    class="px-4 py-2 bg-zinc-200 dark:bg-zinc-700 text-zinc-900 dark:text-white rounded-full flex items-center space-x-2 hover:bg-zinc-300 dark:hover:bg-zinc-600 transition">
                    
                    <template x-if="current === 'light'">
                        <span class="flex items-center space-x-1">
                            <flux:icon.sun class="w-5 h-5" />
                            <span>Light</span>
                        </span>
                    </template>

                    <template x-if="current === 'dark'">
                        <span class="flex items-center space-x-1">
                            <flux:icon.moon class="w-5 h-5" />
                            <span>Dark</span>
                        </span>
                    </template>

                    <template x-if="current === 'system'">
                        <span class="flex items-center space-x-1">
                            <flux:icon.computer-desktop class="w-5 h-5" />
                            <span>System</span>
                        </span>
                    </template>
                </flux:button>
            </div>
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
        
    </body>
</html>
