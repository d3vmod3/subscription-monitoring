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
    // Filters
    public $monthFrom;
    public $monthTo;
    public $chart_monthFrom;
    public $chart_monthTo;

    // Summary metrics
    public $approvedPaymentsCount = 0;
    public $unpaidSubscriptionsCount = 0;
    public $activeSubscriptions = 0;
    public $netSales = 0;
    public $unpaidAmount = 0;

    // Chart data
    public $salesLabels = [];
    public $salesData = [];
    public $unpaidSalesData = [];

    // Subscribers & subscriptions counts
    public $active_subscribers = 0;
    public $inactive_subscribers = 0;
    public $active_subscriptions = 0;
    public $inactive_subscriptions = 0;
    public $disconnected_subscriptions = 0;

    public function mount()
    {
        $today = Carbon::today();
        $this->monthFrom = $today->format('Y-m');
        $this->monthTo = $today->format('Y-m');

        $this->chart_monthFrom = Carbon::now()->startOfYear()->format('Y-m');
        $this->chart_monthTo = Carbon::now()->format('Y-m');

        $this->updateData();
        $this->countSubscribersAndSubscriptions();
        $this->loadSalesChart();
    }

    // Auto-update when filters change
    public function updatedMonthFrom() { $this->autoUpdateIfValid(); }
    public function updatedMonthTo()   { $this->autoUpdateIfValid(); }
    public function updatedChartMonthFrom() { $this->loadSalesChart(); }
    public function updatedChartMonthTo()   { $this->loadSalesChart(); }

    private function autoUpdateIfValid()
    {
        if ($this->monthFrom && $this->monthTo) {
            $this->updateData();
        }
    }

    public function countSubscribersAndSubscriptions()
    {
        $this->active_subscribers = Subscriber::where('is_active', true)->count();
        $this->inactive_subscribers = Subscriber::where('is_active', false)->count();

        $this->active_subscriptions = Subscription::where('status', 'active')->count();
        $this->inactive_subscriptions = Subscription::where('status', 'inactive')->count();
        $this->disconnected_subscriptions = Subscription::where('status', 'disconnected')->count();
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

        // Approved payments
        $approvedPayments = Payment::where('status', 'Approved')
            ->whereBetween('paid_at', [$start, $end]);

        $this->approvedPaymentsCount = $approvedPayments->count();
        $this->netSales = $approvedPayments->sum('paid_amount');

        // Active subscriptions
        $activeSubscriptions = Subscription::with(['plan', 'payments'])
            ->where('status', 'active')->get();

        $totalUnpaid = 0;
        $unpaidCount = 0;

        foreach ($activeSubscriptions as $subscription) {
            if (!$subscription->plan) continue;

            $planPrice = abs((float) $subscription->plan->price);
            $billingStart = $start->copy();
            $subscriptionUnpaid = false;

            while ($billingStart->lessThanOrEqualTo($end)) {
                $monthCover = $billingStart->format('Y-m');
                $subscriptionStart = Carbon::parse($subscription->start_date);

                // Prorate first month if mid-month start
                $expectedAmount = $planPrice;
                if ($billingStart->format('Y-m') === $subscriptionStart->format('Y-m') && $subscriptionStart->day !== 1) {
                    $daysInMonth = $billingStart->daysInMonth;
                    $daysUsed = $daysInMonth - $subscriptionStart->day + 1;
                    $expectedAmount = ceil(($planPrice / $daysInMonth) * $daysUsed);
                }

                // Paid amount in this month
                $paidAmount = $subscription->payments
                    ->where('status', 'Approved')
                    ->filter(fn($p) => Carbon::parse($p->month_year_cover . '-01')->format('Y-m') === $monthCover)
                    ->sum('paid_amount');

                $remaining = max($expectedAmount - $paidAmount, 0);

                if ($remaining > 0) {
                    $subscriptionUnpaid = true;
                    $totalUnpaid += $remaining;
                }

                $billingStart->addMonth();
            }

            if ($subscriptionUnpaid) $unpaidCount++;
        }

        $this->unpaidSubscriptionsCount = $unpaidCount;
        $this->unpaidAmount = $totalUnpaid;
    }

    public function loadSalesChart()
    {
        $start = Carbon::parse($this->chart_monthFrom)->startOfMonth();
        $end   = Carbon::parse($this->chart_monthTo)->endOfMonth();

        $period = collect();
        $current = $start->copy();
        while ($current->lessThanOrEqualTo($end)) {
            $period->push($current->copy());
            $current->addMonth();
        }

        $this->salesLabels = $period->map(fn($d) => $d->format('F Y'))->toArray();
        $this->salesData = [];
        $this->unpaidSalesData = [];

        $activeSubscriptions = Subscription::with(['plan', 'payments'])
            ->where('status', 'active')->get();

        foreach ($period as $monthDate) {
            $month = $monthDate->format('Y-m');

            // Paid total
            $this->salesData[] = Payment::where('status', 'Approved')
                ->whereBetween('paid_at', [$monthDate->copy()->startOfMonth(), $monthDate->copy()->endOfMonth()])
                ->sum('paid_amount');

            // Unpaid total
            $unpaidThisMonth = 0;
            foreach ($activeSubscriptions as $subscription) {
                if (!$subscription->plan) continue;

                $planPrice = abs((float) $subscription->plan->price);
                $subscriptionStart = Carbon::parse($subscription->start_date);

                if ($subscriptionStart->gt($monthDate->copy()->endOfMonth())) continue;

                $expectedAmount = $planPrice;
                if ($subscriptionStart->format('Y-m') === $month && $subscriptionStart->day !== 1) {
                    $daysInMonth = $monthDate->daysInMonth;
                    $daysUsed = $daysInMonth - $subscriptionStart->day + 1;
                    $expectedAmount = ceil(($planPrice / $daysInMonth) * $daysUsed);
                }

                $paidAmount = $subscription->payments
                    ->where('status', 'Approved')
                    ->filter(fn($p) => Carbon::parse($p->month_year_cover . '-01')->format('Y-m') === $month)
                    ->sum('paid_amount');

                $unpaidThisMonth += max($expectedAmount - $paidAmount, 0);
            }

            $this->unpaidSalesData[] = $unpaidThisMonth;
        }

        // Trigger Livewire JS event for charts.js
        $this->dispatch('salesUpdated', [
            'labels' => $this->salesLabels,
            'paid' => $this->salesData,
            'unpaid' => $this->unpaidSalesData,
        ]);
    }

    public function render()
    {
        if (!Auth::user()->can('view dashboard')) {
            abort(403, 'You are not allowed to access this page');
        }

        return view('livewire.admin.dashboard');
    }
}
