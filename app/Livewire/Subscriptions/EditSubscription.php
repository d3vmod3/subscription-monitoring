<?php

namespace App\Livewire\Subscriptions;

use Livewire\Component;
use App\Models\Subscription;
use App\Models\Subscriber;
use App\Models\Plan;
use App\Models\PassiveOpticalNetwork;
use Hashids\Hashids;
use Carbon\Carbon;

class EditSubscription extends Component
{
    public $subscription;
    public $subscriber_id;
    public $subscriber_search = '';
    public $subscriber_results = [];
    public $plan_id;
    public $pon_id;
    public $mikrotik_name;
    public $start_date;
    public $status;

    public $plans = [];
    public $pons = [];

    public function mount($hash)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $decoded = $hashids->decode($hash);
        $id = $decoded[0] ?? null;

        if (!$id) {
            abort(404);
        }

        $this->subscription = Subscription::findOrFail($id);

        // Populate fields
        $this->subscriber_id = $this->subscription->subscriber_id;
        $this->subscriber_search = optional($this->subscription->subscriber)->first_name . ' ' . optional($this->subscription->subscriber)->last_name;
        $this->plan_id = $this->subscription->plan_id;
        $this->pon_id = $this->subscription->pon_id;
        $this->mikrotik_name = $this->subscription->mikrotik_name;
        $this->start_date = Carbon::parse($this->subscription->start_date)->format('Y-m-d');
        $this->status = $this->subscription->status;

        $this->plans = Plan::where('is_active', 1)->get();
        $this->pons = PassiveOpticalNetwork::all();
    }

    protected function rules()
    {
        return [
            'subscriber_id' => 'nullable|exists:subscribers,id',
            'plan_id' => 'required|exists:plans,id',
            'pon_id' => 'nullable|exists:pons,id',
            'mikrotik_name' => 'required|string|max:255|unique:subscriptions,mikrotik_name,' . $this->subscription->id,
            'start_date' => 'required|date',
            'status' => 'required|in:active,inactive,disconnected',
        ];
    }

    public function updatedSubscriberSearch()
    {
        if (strlen($this->subscriber_search) > 1) {
            $search = $this->subscriber_search;

            $this->subscriber_results = Subscriber::where('is_active', 1)
                ->where(function ($q) use ($search) {
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

    public function save()
    {
        $this->validate();

        $this->subscription->update([
            'subscriber_id' => $this->subscriber_id,
            'plan_id' => $this->plan_id,
            'pon_id' => $this->pon_id,
            'mikrotik_name' => $this->mikrotik_name,
            'start_date' => $this->start_date,
            'status' => $this->status,
        ]);

        $this->dispatch('show-toast', [
            'message' => 'Subscription updated successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        return view('livewire.subscriptions.edit-subscription');
    }
}
