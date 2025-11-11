<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use Hashids\Hashids;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
    Volt::route('subscribers/list', 'subscribers.subscribers')->name('subscribers');
    Volt::route('subscribers/add', 'subscribers.addsubscriber')->name('subscribers.add');
    Volt::route('subscribers/edit/{hash}', 'subscribers.editsubscriber')->name('subscribers.edit');

    // Payment Methods Routes
    Volt::route('payment-methods/list', 'payment-methods.payment-methods')->name('payment-methods');
    // Volt::route('payment-methods/add', 'payment-methods.add-payment-method')->name('payment-methods.add');
    Volt::route('payment-method/edit/{hash}', 'payment-methods.edit-payment-method')->name('payment-methods.edit');

    // Sectors Routes
    Volt::route('sectors/list', 'sectors.sectors')->name('sectors');
    // Volt::route('sectors/add-sector', 'sectors.add-sector')->name('sector.add');
    Volt::route('sectors/edit/{hash}', 'sectors.edit-sector')->name('sector.edit');

    // PONs Routes
    Volt::route('passive-optical-networks/list', 'passive-optical-networks.passive-optical-networks')->name('pons');
    // Volt::route('passive-optical-networks/add-sector', 'passive-optical-networks.add-passive-optical-networks')->name('pon.add');
    Volt::route('passive-optical-networks/edit/{hash}', 'passive-optical-networks.edit-passive-optical-network')->name('pon.edit');

    // Napboxes Routes
    Volt::route('napboxes/list', 'napboxes.napboxes')->name('napboxes');
    // Volt::route('napboxes/edit/{hash}', 'napboxes.add-napboxes')->name('pon.edit');
    Volt::route('napboxes/edit/{hash}', 'napboxes.edit-napbox')->name('napbox.edit');

    // Splitters Routes
    Volt::route('splitters/list', 'splitters.splitters')->name('splitters');
    // Volt::route('napboxes/edit/{hash}', 'napboxes.add-napboxes')->name('splitter.edit');
    Volt::route('splitters/edit/{hash}', 'splitters.edit-splitter')->name('splitter.edit');
});
