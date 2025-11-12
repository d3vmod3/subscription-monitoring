<?php

namespace App\Livewire\Payments;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\PaymentMethod;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AddPayment extends Component
{
    public $subscription_id;
    public $subscriber_search = '';
    public $subscriber_results = [];

    public $payment_method_id;
    public $reference_number;
    public $paid_at;
    public $date_cover_from;
    public $date_cover_to;
    public $amount;
    public $is_discounted = false;
    public $remarks;
    public $account_name; // who paid

    public $subscriptions = [];
    public $payment_methods = [];

    protected $rules = [
        'subscription_id' => 'required|exists:subscriptions,id',
        'payment_method_id' => 'required|exists:payment_methods,id',
        'reference_number' => 'nullable|string|max:50',
        'paid_at' => 'required|date|before_or_equal:today',
        'date_cover_from' => 'required|date',
        'date_cover_to' => 'required|date|after:date_cover_from',
        'amount' => 'required|numeric|min:0',
        'is_discounted' => 'boolean',
        'remarks' => 'nullable|string|required_if:is_discounted,true',
        'account_name' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->payment_methods = PaymentMethod::where('is_active', true)->get();
    }

    public function updatedSubscriberSearch()
    {
        if (strlen($this->subscriber_search) > 1) {
            $search = $this->subscriber_search;

            $this->subscriber_results = Subscription::with('subscriber')
                ->where('mikrotik_name', 'like', "%{$search}%")
                ->limit(8)
                ->get();
        } else {
            $this->subscriber_results = [];
        }
    }

    public function selectSubscriber($id, $mikrotik_name)
    {
        $this->subscription_id = $id;
        $this->subscriber_search = $mikrotik_name;
        $this->subscriber_results = [];
    }

    public function save()
    {
        $this->validate();

        // Validate that date_cover_to is in a different month than date_cover_from
        $from = Carbon::parse($this->date_cover_from);
        $to = Carbon::parse($this->date_cover_to);

        if ($from->month == $to->month && $from->year == $to->year) {
            $this->addError('date_cover_to', 'Date Cover To must be in a different month than Date Cover From.');
            return;
        }

        Payment::create([
            'subscription_id' => $this->subscription_id,
            'payment_method_id' => $this->payment_method_id,
            'reference_number' => $this->reference_number ?? Str::upper(Str::random(10)),
            'paid_at' => $this->paid_at,
            'date_cover_from' => $this->date_cover_from,
            'date_cover_to' => $this->date_cover_to,
            'amount' => $this->amount,
            'status' => 'Pending', // default status
            'is_discounted' => $this->is_discounted,
            'remarks' => $this->is_discounted ? $this->remarks : null,
            'account_name' => $this->account_name,
        ]);

        $this->reset([
            'subscription_id',
            'subscriber_search',
            'subscriber_results',
            'payment_method_id',
            'reference_number',
            'paid_at',
            'date_cover_from',
            'date_cover_to',
            'amount',
            'is_discounted',
            'remarks',
            'account_name',
        ]);

        $this->dispatch('payment-added');
        $this->dispatch('show-toast', [
            'message' => 'Payment added successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        return view('livewire.payments.add-payment');
    }
}
