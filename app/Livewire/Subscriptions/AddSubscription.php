<?php

namespace App\Livewire\Subscriptions;

use Livewire\Component;
use App\Models\Subscription;
use App\Models\Subscriber;
use App\Models\Plan;
use App\Models\Splitter;
use Auth;

class AddSubscription extends Component
{
    public $subscriber_id;
    public $subscriber_search = '';
    public $subscriber_results = [];

    public $plan_id;
    public $splitter_id;
    public $mikrotik_name;
    public $start_date;
    public $status = 'inactive';

    public $plans = [];

    protected $listeners = [
        'splitter-updated' => 'setSplitter',
    ];

    protected $rules = [
        'subscriber_id' => 'nullable|exists:subscribers,id',
        'plan_id' => 'required|exists:plans,id',
        'splitter_id' => 'nullable|exists:splitters,id',
        'mikrotik_name' => 'required|string|unique:subscriptions,mikrotik_name|max:255',
        'start_date' => 'required|date',
        'status' => 'required|in:active,inactive,disconnected',
    ];

    public function mount()
    {
        $this->plans = Plan::where('is_active', 1)->get();
    }

    public function updatedSubscriberSearch()
    {
        if (strlen($this->subscriber_search) > 1) {
            $search = $this->subscriber_search;

            $this->subscriber_results = Subscriber::where('is_active', 1)
                ->where(function($q) use ($search) {
                    // Flexible full name search
                    $q->whereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('middle_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->limit(8)
                ->get();
        } else {
            $this->subscriber_results = [];
        }
    }

    public function selectSubscriber($id, $name)
    {
        $this->subscriber_id = $id;
        $this->subscriber_search = $name;
        $this->subscriber_results = [];
    }

    public function setSplitter($data)
    {
        $this->splitter_id = $data['splitter_id'];
    }

    public function save()
    {
        if (!Auth::user()->can('add subscriptions'))
        {
            abort(403, 'Unauthorized action');
        }
        $this->validate();

        Subscription::create([
            'subscriber_id' => $this->subscriber_id,
            'plan_id' => $this->plan_id,
            'splitter_id' => $this->splitter_id,
            'mikrotik_name' => $this->mikrotik_name,
            'start_date' => $this->start_date,
            'status' => $this->status,
        ]);

        // Reset fields
        $this->reset([
            'subscriber_id',
            'subscriber_search',
            'subscriber_results',
            'plan_id',
            'splitter_id',
            'mikrotik_name',
            'start_date',
            'status'
        ]);

        $this->dispatch('subscription-added');
        $this->dispatch('show-toast', [
            'message' => 'Subscription added successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        return view('livewire.subscriptions.add-subscription');
    }
}
