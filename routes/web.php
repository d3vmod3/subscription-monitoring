<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use Hashids\Hashids;
use App\Http\Controllers\PdfController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('login');

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

    // Subscriptions Routes
    Volt::route('subscriptions/list', 'subscriptions.subscriptions')->name('subscriptions');
    // Volt::route('subscriptions/edit/{hash}', 'subscriptions.add-napboxes')->name('subscription.add');
    Volt::route('subscriptions/edit/{hash}', 'subscriptions.edit-subscription')->name('subscription.edit');
    
    // Plans Routes
    Volt::route('plans/list', 'plans.plans')->name('plans');
    // Volt::route('plans/edit/{hash}', 'plans.add-napboxes')->name('plan.add');
    Volt::route('plans/edit/{hash}', 'plans.edit-plan')->name('plan.edit');

    // Payments Routes
    Volt::route('payments/list', 'payments.payments')->name('payments');
    // Volt::route('plans/edit/{hash}', 'plans.add-napboxes')->name('plan.add');
    Volt::route('payments/edit/{hash}', 'payments.edit-payment')->name('payment.edit');

    // Payments Routes
    Volt::route('advance-payments/list', 'advance-payments.payments')->name('payments');
    // Volt::route('plans/edit/{hash}', 'plans.add-napboxes')->name('plan.add');
    Volt::route('advance-payments/edit/{hash}', 'advance-payment.edit-payment')->name('payment.edit');

    // Billings Routes
    Volt::route('billings/view/{hash}', 'billings.billings')->name('view-billings');

    Volt::route('users/list', 'users.users')->name('users');
    Volt::route('users/add', 'users.add-user')->name('user.add');
    Volt::route('users/edit/{hash}', 'user.edit-user')->name('user.edit');

    //generate billing pdf copy
    Route::get('/pdf-billing/{subscriptionHash}/{monthCoverFrom}/{monthCoverTo}',[PdfController::class, 'generatePdf'])->name('pdf.billing');
});
