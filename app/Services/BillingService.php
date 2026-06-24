<?php

namespace App\Services;

use Carbon\Carbon;
use Hashids\Hashids;
use App\Models\Subscription;

class BillingService
{
    public function calculate($subscriber, $from, $to)
    {
        $result = [];
        $hashids = new Hashids(
            config('hashids.salt'),
            config('hashids.min_length')
        );

        // Normalize date range
        $from = $from instanceof Carbon
            ? $from->copy()->startOfMonth()
            : Carbon::parse($from)->startOfMonth();

        $to = $to instanceof Carbon
            ? $to->copy()->endOfMonth()
            : Carbon::parse($to)->endOfMonth();

        foreach ($subscriber->subscriptions as $subscription) {

            $planPrice = abs((float) ($subscription->plan->price ?? 0));
            $subscriptionStart = Carbon::parse($subscription->start_date);

            // Start billing from FROM or subscription start month
            $billingStart = $from->copy()->greaterThan($subscriptionStart)
                ? $from->copy()
                : $subscriptionStart->copy()->startOfMonth();

            $totalExpected = 0;
            $totalPaid = 0;
            $totalDiscount = 0;

            while ($billingStart->lessThanOrEqualTo($to)) {

                $monthCover = $billingStart->format('Y-m');

                // =========================
                // MATCH LIVEWIRE PRORATION
                // =========================
                if (
                    $billingStart->format('Y-m') === $subscriptionStart->format('Y-m')
                    && $subscriptionStart->day !== 1
                ) {
                    // SAME AS LIVEWIRE:
                    // start_date → 1st day of next month
                    $firstBillingEnd = $subscriptionStart
                        ->copy()
                        ->addMonthNoOverflow()
                        ->startOfMonth();

                    $daysUsed = $subscriptionStart->diffInDays($firstBillingEnd);

                    $expectedAmount = ceil(
                        ($planPrice / $subscriptionStart->daysInMonth) * $daysUsed
                    );
                } else {
                    // FULL MONTH
                    $expectedAmount = $planPrice;
                }

                // =========================
                // PAYMENTS
                // =========================
                $paidAmount = $subscription->payments()
                    ->where('month_year_cover', $monthCover)
                    ->where('status', 'Approved')
                    ->sum('paid_amount');

                $discountAmount = $subscription->payments()
                    ->where('month_year_cover', $monthCover)
                    ->where('status', 'Approved')
                    ->sum('discount_amount');

                // =========================
                // TOTALS
                // =========================
                $totalExpected += $expectedAmount;
                $totalPaid += $paidAmount;
                $totalDiscount += $discountAmount;

                $billingStart->addMonth();
            }

            $result[] = [
                'subscription_id' => $hashids->encode($subscription->id),
                'plan_name' => $subscription->plan->name ?? null,
                'mikrotik_name' => $subscription->mikrotik_name,
                'start_date' => $subscription->start_date,

                'expected_total' => $totalExpected,
                'total_paid' => $totalPaid,
                'total_discount' => $totalDiscount,

                'balance' => $totalExpected - $totalPaid - $totalDiscount,
            ];
        }

        return [
            'year' => now()->year,
            'subscriptions' => $result,

            'grand_total_expected' => collect($result)->sum('expected_total'),
            'grand_total_paid' => collect($result)->sum('total_paid'),
            'grand_total_discount' => collect($result)->sum('total_discount'),
        ];
    }

    public function generate(Subscription $subscription, $from = null, $to = null)
    {
        $planPrice = abs((float) ($subscription->plan->price ?? 0));
        $subscriptionStart = Carbon::parse($subscription->start_date);

        // -------------------------
        // NORMALIZE RANGE
        // -------------------------
        $from = $from
            ? Carbon::parse($from . '-01')->startOfMonth()
            : $subscriptionStart->copy()->startOfMonth();

        $to = $to
            ? Carbon::parse($to . '-01')->endOfMonth()
            : now()->endOfMonth();

        // -------------------------
        // START
        // -------------------------
        $billingStart = $from->copy();

        $billingSummary = collect();

        $totalExpected = 0;
        $totalPaid = 0;
        $totalDiscount = 0;

        while ($billingStart->lessThanOrEqualTo($to)) {

            $monthCover = $billingStart->format('Y-m');

            // -------------------------
            // PRORATION (same logic everywhere)
            // -------------------------
            if (
                $billingStart->format('Y-m') === $subscriptionStart->format('Y-m')
                && $subscriptionStart->day !== 1
            ) {
                $firstBillingEnd = $subscriptionStart
                    ->copy()
                    ->addMonthNoOverflow()
                    ->startOfMonth();

                $daysUsed = $subscriptionStart->diffInDays($firstBillingEnd);

                $expectedAmount = ceil(
                    ($planPrice / $subscriptionStart->daysInMonth) * $daysUsed
                );
            } else {
                $expectedAmount = $planPrice;
            }

            // -------------------------
            // PAYMENTS
            // -------------------------
            $paidAmount = $subscription->payments()
                ->where('month_year_cover', $monthCover)
                ->where('status', 'Approved')
                ->sum('paid_amount');

            $discountAmount = $subscription->payments()
                ->where('month_year_cover', $monthCover)
                ->where('status', 'Approved')
                ->sum('discount_amount');

            $remaining = max($expectedAmount - $paidAmount - $discountAmount, 0);
            $status = ($paidAmount + $discountAmount >= $expectedAmount)
                ? 'Paid'
                : 'Not Paid';

            // -------------------------
            // PUSH ROW
            // -------------------------
            $billingSummary->push([
                'month' => $billingStart->format('F Y'),
                'expected_amount' => $expectedAmount,
                'paid_amount' => $paidAmount,
                'discount_amount' => $discountAmount,
                'remaining_balance' => $remaining,
                'status' => $status,
            ]);

            // -------------------------
            // TOTALS
            // -------------------------
            $totalExpected += $expectedAmount;
            $totalPaid += $paidAmount;
            $totalDiscount += $discountAmount;

            $billingStart->addMonth();
        }

        return [
            'billing_summary' => $billingSummary,

            'totals' => [
                'expected_total' => $totalExpected,
                'total_paid' => $totalPaid,
                'total_discount' => $totalDiscount,
                'remaining_balance' => max($totalExpected - $totalPaid - $totalDiscount, 0),
            ],
        ];
    }
}