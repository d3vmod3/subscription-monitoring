<?php

namespace App\Livewire\AdvancePayments;

use Livewire\Component;
use App\Models\AdvancePayment;
use App\Models\Subscription;
use App\Models\PaymentMethod;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;

class AddAdvancePayment extends Component
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

    public function mount()
    {
        $this->payment_methods = PaymentMethod::where('is_active', true)->get();
        $this->month_year_cover = now()->format('Y-m');
        $this->paid_at = now()->format('Y-m-d');
    }

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
    }

    

     public function computeExpectedAmount()
    {
        $this->expected_amount = 0;

        if (!$this->selectedSubscription || !$this->selectedSubscription->plan || !$this->month_year_cover) {
            return;
        }

        $planPrice = $this->selectedSubscription->plan->price;
        

        $this->expected_amount = max($planPrice, 2);
    }

    public function save()
    {
        if (!Auth::user()->can('add advance payments'))
        {
            abort(403, 'Unauthorized action');
        }
        $this->validate();

        if ($this->paid_amount < $this->expected_amount) {
            $this->addError('paid_amount', "Paid amount must be exactly ₱{$this->expected_amount}.");
            return;
        }

        AdvancePayment::create([
            'subscription_id' => $this->subscription_id,
            'user_id' => Auth::user()->id,
            'payment_method_id' => $this->payment_method_id,
            'reference_number' => $this->reference_number ?? Str::upper(Str::random(10)),
            'paid_at' => $this->paid_at,
            'paid_amount' => $this->paid_amount,
            'status' => 'Pending',
            'is_discounted' => $this->is_discounted,
            'discount_amount' => $this->discount_amount,
            'remarks' => $this->is_discounted ? $this->remarks : null,
            'account_name' => $this->account_name,
            'is_used' => false,
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
        return view('livewire.advance-payments.add-advance-payment');
    }
}
