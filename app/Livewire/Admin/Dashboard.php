<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Subscriber;
use App\Models\Subscription;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $monthFrom;
    public $monthTo;

    public $chart_monthFrom;
    public $chart_monthTo;


    public $approvedPaymentsCount = 0;
    public $unpaidSubscriptionsCount = 0;
    public $activeSubscriptions = 0;
    public $netSales = 0;
    public $unpaidAmount = 0;

    public $salesLabels = [];
    public $salesData = [];
    public $unpaidSalesData = [];


    public $active_subscribers = 0;
    public $inactive_subscribers = 0;
    public $active_subscriptions = 0;
    public $inactive_subscriptions = 0;
    public $disconnected_subscriptions = 0;
    

    public function mount()
    {
        // Default to current month
        $this->monthFrom = Carbon::today()->format('Y-m');
        $this->monthTo   = Carbon::today()->format('Y-m');

        $this->chart_monthFrom = Carbon::now()->startOfYear()->format('Y-m'); // 2025-01
        $this->chart_monthTo   = Carbon::now()->format('Y-m'); // current month 2025-11

        $this->updateData();
        $this->countSubscribersAndSubscriptions();
        $this->dispatch('salesUpdated');
        $this->loadSalesChart();
    }

    public function updatedMonthFrom()
    {
        $this->autoUpdateIfValid();
    }

    public function updatedMonthTo()
    {
        $this->autoUpdateIfValid();
    }

    public function updatedChartMonthFrom()
    {
        $this->loadSalesChart();
        $this->dispatch('salesUpdated');
    }

    public function updatedChartMonthTo()
    {
        $this->loadSalesChart();
        $this->dispatch('salesUpdated');
    }


    /** Automatically update when both inputs are valid */
    private function autoUpdateIfValid()
    {
        if ($this->monthFrom && $this->monthTo) {
            $this->updateData();
        }
    }

    public function countSubscribersAndSubscriptions()
    {
        $this->active_subscribers = Subscriber::all()->where('is_active',true)->count();
        $this->inactive_subscribers = Subscriber::all()->where('is_active',false)->count();
        
        $this->active_subscriptions = Subscription::all()->where('status','active')->count();
        $this->inactive_subscriptions = Subscription::all()->where('status','inactive')->count();
        $this->disconnected_subscriptions = Subscription::all()->where('status','disconnected')->count();
    }

    public function updateData()
    {
        $this->resetErrorBag();

        $this->validate([
            'monthFrom' => 'required|date_format:Y-m',
            'monthTo'   => 'required|date_format:Y-m|after_or_equal:monthFrom',
        ]);

        $start = Carbon::parse($this->monthFrom)->startOfMonth();
        $end   = Carbon::parse($this->monthTo)->endOfMonth();

        // Total approved payments count & net sales
        $approvedPayments = Payment::where('status', 'Approved')
            ->whereBetween('paid_at', [$start, $end]);

        $this->approvedPaymentsCount = $approvedPayments->count();
        $this->netSales = $approvedPayments->sum('paid_amount');

        // Active subscriptions
        $activeSubscriptions = Subscription::with(['plan', 'payments'])
            ->where('status', 'active')
            ->get();

        $totalUnpaidAmount = 0;
        $unpaidSubscriptionsCount = 0;

        foreach ($activeSubscriptions as $subscription) {
            if (!$subscription->plan) continue;

            $planPrice = abs((float) $subscription->plan->price);

            $billingStart = $start->copy();
            $subscriptionUnpaid = false;

            while ($billingStart->lessThanOrEqualTo($end)) {
                $monthCover = $billingStart->format('Y-m');

                // First month prorated if subscription started mid-month
                $subscriptionStart = Carbon::parse($subscription->start_date);
                if ($billingStart->format('Y-m') === $subscriptionStart->format('Y-m') && $subscriptionStart->day !== 1) {
                    $daysInMonth = $billingStart->daysInMonth;
                    $daysUsed = $daysInMonth - $subscriptionStart->day + 1;
                    $expectedAmount = ceil(($planPrice / $daysInMonth) * $daysUsed);
                } else {
                    $expectedAmount = $planPrice;
                }

                // Paid amount in this month
                $paidAmount = $subscription->payments
                    ->where('status', 'Approved')
                    ->filter(fn($p) => Carbon::parse($p->month_year_cover . '-01')->format('Y-m') === $monthCover)
                    ->sum('paid_amount');

                $remaining = max($expectedAmount - $paidAmount, 0);

                if ($remaining > 0) {
                    $subscriptionUnpaid = true;
                    $totalUnpaidAmount += $remaining;
                }

                $billingStart->addMonth();
            }

            if ($subscriptionUnpaid) {
                $unpaidSubscriptionsCount++;
            }
        }

        $this->unpaidSubscriptionsCount = $unpaidSubscriptionsCount;
        $this->unpaidAmount = $totalUnpaidAmount;
    }

public function loadSalesChart()
{
    $start = Carbon::parse($this->chart_monthFrom)->startOfMonth();
    $end   = Carbon::parse($this->chart_monthTo)->endOfMonth();

    $period = collect();
    $current = $start->copy();

    while ($current->lessThanOrEqualTo($end)) {
        $period->push($current->copy()); // store Carbon instance
        $current->addMonth();
    }

    $this->salesLabels = $period->map(fn($date) => $date->format('F Y'))->toArray();
    $this->salesData = [];        // Paid
    $this->unpaidSalesData = [];  // Unpaid

    $activeSubscriptions = Subscription::with(['plan', 'payments'])
        ->where('status', 'active')
        ->get();

    foreach ($period as $monthDate) {
        $month = $monthDate->format('Y-m');

        // Total paid for this month
        $paidThisMonth = Payment::where('status', 'Approved')
            ->whereBetween('paid_at', [$monthDate->copy()->startOfMonth(), $monthDate->copy()->endOfMonth()])
            ->sum('paid_amount');

        $this->salesData[] = $paidThisMonth;

        // Compute unpaid for this month based on active subscriptions
        $unpaidThisMonth = 0;

        foreach ($activeSubscriptions as $subscription) {
            if (!$subscription->plan) continue;

            $planPrice = abs((float) $subscription->plan->price);
            $subscriptionStart = Carbon::parse($subscription->start_date);

            // Skip if subscription starts after this month
            if ($subscriptionStart->gt($monthDate->copy()->endOfMonth())) continue;

            // Determine expected amount for this month (prorate if first month)
            if ($subscriptionStart->format('Y-m') === $month && $subscriptionStart->day !== 1) {
                $daysInMonth = $monthDate->daysInMonth;
                $daysUsed = $daysInMonth - $subscriptionStart->day + 1;
                $expectedAmount = ceil(($planPrice / $daysInMonth) * $daysUsed);
            } else {
                $expectedAmount = $planPrice;
            }

            // Paid this month for this subscription
            $paidAmount = $subscription->payments
                ->where('status', 'Approved')
                ->filter(fn($p) => Carbon::parse($p->month_year_cover . '-01')->format('Y-m') === $month)
                ->sum('paid_amount');

            $remaining = max($expectedAmount - $paidAmount, 0);
            $unpaidThisMonth += $remaining;
        }
        $this->unpaidSalesData[] = $unpaidThisMonth;
    }

    // Dispatch event for chart update
    $this->dispatch('salesUpdated');
}









    public function render()
    {
        if (!Auth::user()->can('view dashboard')) {
            abort(403, 'You are not allowed to access this page');
        }

        return view('livewire.admin.dashboard');
    }
}
