<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Auth;
// use App\Http\Middleware\ForceResetPassword;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('login'))
    ->name('login');

/*
|--------------------------------------------------------------------------
| Authenticated & Verified Routes
|--------------------------------------------------------------------------
|
| NOTE:
| If user has "is_password_reset = true", they will automatically
| be redirected to 'user.reset.password' after login.
| (See middleware section below)
|
*/

Route::middleware(['auth', 'force.reset'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Volt::route('user/user-dashboard', 'user.user-dashboard')
        ->name('user.dashboard');

    Volt::route('user/reset-password', 'user.reset-password')
        ->name('user.reset.password');

    // Route::view('dashboard', 'dashboard')->name('dashboard');
    // Route::get('/dashboard', function (Request $request) {
    //     if (!Auth::user()->can('view dashboard')) {
    //        abort(403, 'You are not allowed to this page');
    //     }
    //     return view('livewire.admin.dashboard'); // Return the actual dashboard
    // })->name('dashboard');

     Volt::route('dashboard', 'admin.dashboard')->name('dashboard');


    Route::redirect('settings', 'settings/profile');


    /*
    |--------------------------------------------------------------------------
    | User Profile & Settings
    |--------------------------------------------------------------------------
    */
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(
                    Features::twoFactorAuthentication(),
                    'confirmPassword'
                ),
                ['password.confirm'],
                []
            )
        )
        ->name('two-factor.show');


    /*
    |--------------------------------------------------------------------------
    | Subscribers
    |--------------------------------------------------------------------------
    */
    Volt::route('subscribers/list', 'subscribers.subscribers')->name('subscribers');
    Volt::route('subscribers/add', 'subscribers.addsubscriber')->name('subscribers.add');
    Volt::route('subscribers/edit/{hash}', 'subscribers.editsubscriber')->name('subscribers.edit');


    /*
    |--------------------------------------------------------------------------
    | Payment Methods
    |--------------------------------------------------------------------------
    */
    Volt::route('payment-methods/list', 'payment-methods.payment-methods')->name('payment-methods');
    Volt::route('payment-method/edit/{hash}', 'payment-methods.edit-payment-method')->name('payment-methods.edit');


    /*
    |--------------------------------------------------------------------------
    | Sectors
    |--------------------------------------------------------------------------
    */
    Volt::route('sectors/list', 'sectors.sectors')->name('sectors');
    Volt::route('sectors/edit/{hash}', 'sectors.edit-sector')->name('sector.edit');


    /*
    |--------------------------------------------------------------------------
    | PONs
    |--------------------------------------------------------------------------
    */
    Volt::route('passive-optical-networks/list', 'passive-optical-networks.passive-optical-networks')->name('pons');
    Volt::route('passive-optical-networks/edit/{hash}', 'passive-optical-networks.edit-passive-optical-network')->name('pon.edit');


    /*
    |--------------------------------------------------------------------------
    | Napboxes
    |--------------------------------------------------------------------------
    */
    Volt::route('napboxes/list', 'napboxes.napboxes')->name('napboxes');
    Volt::route('napboxes/edit/{hash}', 'napboxes.edit-napbox')->name('napbox.edit');


    /*
    |--------------------------------------------------------------------------
    | Splitters
    |--------------------------------------------------------------------------
    */
    Volt::route('splitters/list', 'splitters.splitters')->name('splitters');
    Volt::route('splitters/edit/{hash}', 'splitters.edit-splitter')->name('splitter.edit');


    /*
    |--------------------------------------------------------------------------
    | Subscriptions
    |--------------------------------------------------------------------------
    */
    Volt::route('subscriptions/list', 'subscriptions.subscriptions')->name('subscriptions');
    Volt::route('subscriptions/edit/{hash}', 'subscriptions.edit-subscription')->name('subscription.edit');


    /*
    |--------------------------------------------------------------------------
    | Plans
    |--------------------------------------------------------------------------
    */
    Volt::route('plans/list', 'plans.plans')->name('plans');
    Volt::route('plans/edit/{hash}', 'plans.edit-plan')->name('plan.edit');


    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */
    Volt::route('payments/list', 'payments.payments')->name('payments');
    Volt::route('payments/add', 'payments.add-payment')->name('payment.add');
    Volt::route('payments/edit/{hash}', 'payments.edit-payment')->name('payment.edit');


    /*
    |--------------------------------------------------------------------------
    | Advance Payments
    |--------------------------------------------------------------------------
    */
    Volt::route('advance-payments/list', 'advance-payments.advance-payments')->name('advance-payments');
    Volt::route('advance-payments/edit/{hash}', 'advance-payments.edit-advance-payment')->name('advance-payment.edit');


    /*
    |--------------------------------------------------------------------------
    | Billings
    |--------------------------------------------------------------------------
    */
    Volt::route('billings/view/{hash}', 'billings.billings')->name('view-billings');

    Route::get(
        '/pdf-billing/{subscriptionHash}/{monthCoverFrom}/{monthCoverTo}',
        [PdfController::class, 'generatePdf']
    )->name('pdf.billing');


    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */
    Volt::route('users/list', 'users.users')->name('users');
    Volt::route('users/add', 'users.add-user')->name('user.add');
    Volt::route('users/edit/{hash}', 'users.edit-user')->name('user.edit');


    /*
    |--------------------------------------------------------------------------
    | Admin: User Access Control
    |--------------------------------------------------------------------------
    */
    Volt::route('admin/user-access-control', 'admin.user-access-control')->name('uac');


    /*
    |--------------------------------------------------------------------------
    | Expenses
    |--------------------------------------------------------------------------
    */
    Volt::route('expenses/list', 'expenses.expenses')->name('expenses');
    Volt::route('expenses/add', 'expenses.add-expense')->name('expense.add');
    Volt::route('expenses/edit/{hash}', 'expenses.edit-expense')->name('expense.edit');

});
