<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentService
{
    public function create(array $data, $user)
    {
        if (! $user->can('add payments')) {
            abort(403, 'Unauthorized action');
        }

        return Payment::create([
            'subscription_id' => $data['subscription_id'],
            'user_id' => $user->id,
            'payment_method_id' => $data['payment_method_id'],
            'reference_number' => $data['reference_number'] ?? null,
            'paid_at' => $data['paid_at'],
            'month_year_cover' => $data['month_year_cover'],
            'paid_amount' => $data['paid_amount'],
            'status' => 'Pending',
            'is_discounted' => $data['is_discounted'] ?? false,
            'discount_amount' => $data['discount_amount'] ?? 0,
            'remarks' => $data['remarks'] ?? null,
            'account_name' => $data['account_name'],
        ]);
    }
    
    public function computeExpectedAmount(Subscription $subscription, string $monthYear): int
    {
        if (! $subscription->plan || ! $monthYear) {
            return 0;
        }

        $planPrice = $subscription->plan->price;

        $subscriptionStart = Carbon::parse($subscription->start_date);
        $coverMonthStart = Carbon::parse($monthYear . '-01');

        // First month scenario
        if ($subscriptionStart->format('Y-m') === $coverMonthStart->format('Y-m')) {

            $coverMonthEnd = $coverMonthStart->copy()->addMonth()->day(1);

            $activeDays = $subscriptionStart->diffInDays($coverMonthEnd);
            $totalDaysInMonth = $coverMonthStart->daysInMonth;

            $expected = ($planPrice / $totalDaysInMonth) * $activeDays;
        } else {
            $expected = $planPrice;
        }

        // subtract already paid
        $alreadyPaid = Payment::where('subscription_id', $subscription->id)
            ->where('month_year_cover', $monthYear)
            ->where('status', 'Approved')
            ->sum('paid_amount');

        return (int) ceil(max($expected - $alreadyPaid, 0));
    }
}