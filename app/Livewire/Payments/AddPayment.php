<?php

namespace App\Livewire\Payments;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\PaymentMethod;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;

class AddPayment extends Component
{
    public $subscription_id;
    public $subscriber_search = '';
    public $subscriber_results = [];

    public $payment_method_id;
    public $reference_number;
    public $paid_at;
    public $month_year_cover;
    public $paid_amount;
    public $is_discounted = false;
    public $discount_amount = 0;
    public $remarks;
    public $account_name;

    public $subscriptions = [];
    public $payment_methods = [];
    public $selectedSubscription;
    public $expected_amount = 0; // computed based on month_year_cover

    protected $rules = [
        'subscription_id' => 'required|exists:subscriptions,id',
        'payment_method_id' => 'required|exists:payment_methods,id',
        'reference_number' => 'nullable|string|max:50',
        'paid_at' => 'required|date|before_or_equal:today',
        'month_year_cover' => 'required|date_format:Y-m',
        'paid_amount' => 'required|numeric|min:0',
        'is_discounted' => 'boolean',
        'discount_amount' => 'nullable|numeric|required_if:is_discounted,true',
        'account_name' => 'required|string|max:255',
    ];

    protected $messages = [
        'subscription_id.required' => 'The Mikrotik Name field is required.',
        'payment_method_id.required' => 'The Payment Method field is required.',
    ];

    public function mount()
    {
        $this->payment_methods = PaymentMethod::where('is_active', true)->get();
        $this->month_year_cover = now()->format('Y-m');
        $this->paid_at = now()->format('Y-m-d');
    }

    public function updatedMonthYearCover()
    {
        $this->computeExpectedAmount();
    }

    public function updatedSubscriberSearch()
    {
        if (strlen($this->subscriber_search) > 1) {
            $search = $this->subscriber_search;

            $this->subscriber_results = Subscription::with('subscriber', 'plan')
                ->where('mikrotik_name', 'like', "%{$search}%")
                ->orWhereHas('subscriber', function ($query) use ($search) {
                    $query->whereRaw(
                        "CONCAT(first_name, ' ', IFNULL(middle_name, ''), ' ', last_name) LIKE ?",
                        ['%' . $search . '%']
                    );
                })
                ->orWhereHas('subscriber', function ($query) use ($search) {
                    $query->whereRaw(
                        "CONCAT(first_name, ' ' , last_name) LIKE ?",
                        ['%' . $search . '%']
                    );
                })
                ->limit(8)
                ->get()
                ->map(function ($sub) {
                    $fullName = $sub->subscriber
                        ? trim("{$sub->subscriber->first_name} " . ($sub->subscriber->middle_name ?? '') . " {$sub->subscriber->last_name}")
                        : 'N/A';
                    $sub->display_name = "{$fullName} - {$sub->mikrotik_name}";
                    return $sub;
                });
        } else {
            $this->subscriber_results = [];
        }
    }

    public function selectSubscriber($id, $mikrotik_name)
    {
        $this->subscription_id = $id;
        $this->subscriber_search = $mikrotik_name;
        $this->subscriber_results = [];

        $this->selectedSubscription = Subscription::with('plan')->find($id);

        // Recompute expected amount if month_year_cover is already set
        $this->computeExpectedAmount();
    }

    public function computeExpectedAmount()
    {
        $this->expected_amount = 0;

        if (!$this->selectedSubscription || !$this->selectedSubscription->plan || !$this->month_year_cover) {
            return;
        }

        $planPrice = $this->selectedSubscription->plan->price;
        $subscriptionStart = Carbon::parse($this->selectedSubscription->start_date);
        $coverMonthStart = Carbon::parse($this->month_year_cover . '-01');

        // First month scenario: subscription starts mid-month
        if ($subscriptionStart->format('Y-m') == $coverMonthStart->format('Y-m')) {
            // Set the end date as the 7th of next month
            $coverMonthEnd = $coverMonthStart->copy()->addMonth()->day(1);

            // Calculate active days from start date until 7th of next month
            $activeDays = $subscriptionStart->diffInDays($coverMonthEnd);

            $totalDaysInMonth = $coverMonthStart->daysInMonth;
            $expected = round(($planPrice / $totalDaysInMonth) * $activeDays);

        } else {
            $expected = $planPrice;
        }

        // Subtract any payments already made for this subscription in this month
        $alreadyPaid = Payment::where('subscription_id', $this->selectedSubscription->id)
            ->where('month_year_cover', $this->month_year_cover)
            ->where('status', 'Approved')
            ->sum('paid_amount');

        // $this->expected_amount = max($expected - $alreadyPaid, 0);
        $remaining = max($expected - $alreadyPaid, 0);
        $this->expected_amount = $remaining + (fmod($remaining, 1) !== 0 ? 1 : 0);
    }



    public function save()
    {
        if (!Auth::user()->can('add payments'))
        {
            abort(403, 'Unauthorized action');
        }
        $this->validate();
        
        if ($this->paid_amount > $this->expected_amount) {
            $this->addError('paid_amount', "The amount ₱{$this->paid_amount} exceeds the expected ₱{$this->expected_amount} for {$this->month_year_cover}.");
            return;
        }

        Payment::create([
            'subscription_id' => $this->subscription_id,
            'user_id' => Auth::user()->id,
            'payment_method_id' => $this->payment_method_id,
            'reference_number' => $this->reference_number,
            'paid_at' => $this->paid_at,
            'month_year_cover' => $this->month_year_cover,
            'paid_amount' => $this->paid_amount,
            'status' => 'Pending',
            'is_discounted' => $this->is_discounted,
            'discount_amount' => $this->discount_amount,
            'remarks' => $this->remarks,
            'account_name' => $this->account_name,
        ]);

        $this->reset([
            'subscription_id',
            'subscriber_search',
            'subscriber_results',
            'selectedSubscription',
            'payment_method_id',
            'reference_number',
            'paid_at',
            'paid_amount',
            'is_discounted',
            'discount_amount',
            'remarks',
            'account_name',
            'expected_amount',
        ]);

        $this->subscriber_results=[];

        $this->dispatch('payment-added');
        $this->dispatch('show-toast', [
            'message' => 'Payment added successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        if (!Auth::user()->can('add payments'))
        {
            abort(403, 'You are not allowed to this page');
        }
        return view('livewire.payments.add-payment');
    }
}
