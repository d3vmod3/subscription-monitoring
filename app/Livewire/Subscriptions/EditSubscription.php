<?php

namespace App\Livewire\Subscriptions;

use Livewire\Component;
use App\Models\Subscription;
use App\Models\Subscriber;
use App\Models\Plan;
use App\Models\Splitter;
use App\Models\Sector;
use App\Models\PassiveOpticalNetwork as Pon;
use App\Models\Napbox;
use Hashids\Hashids;
use Carbon\Carbon;
use Auth;

class EditSubscription extends Component
{
    public $subscription;
    public $subscriber_id;
    public $subscriber_search = '';
    public $subscriber_results = [];
    public $plan_id;

    public $sector_id;
    public $pon_id;
    public $napbox_id;
    public $splitter;
    public $splitter_id;
    public $mikrotik_name;
    public $start_date;
    public $status;

    public $plans = [];

    protected $listeners = [
        'splitter-updated' => 'setSplitter',
    ];


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


        $this->splitter_id = $this->subscription->splitter_id;
        $this->splitter = Splitter::findOrFail($this->splitter_id);
        $this->sector_id = $this->splitter->napbox->pon->sector->id;
        $this->pon_id = $this->splitter->napbox->pon->id;
        $this->napbox_id = $this->splitter->napbox->id;




        $this->mikrotik_name = $this->subscription->mikrotik_name;
        $this->start_date = Carbon::parse($this->subscription->start_date)->format('Y-m-d');
        $this->status = $this->subscription->status;

        $this->plans = Plan::where('is_active', 1)->get();
    }

    protected function rules()
    {
        return [
            'subscriber_id' => 'nullable|exists:subscribers,id',
            'plan_id' => 'required|exists:plans,id',
            'splitter_id' => 'nullable|exists:splitters,id',
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

    public function setSplitter($data)
    {
        $this->splitter_id = $data['splitter_id'];
    }

    public function save()
    {
        if (!Auth::user()->can('edit subscriptions'))
        {
            abort(403, 'Unauthorized action');
        }
        $this->validate();
        $this->subscription->update([
            'subscriber_id' => $this->subscriber_id,
            'plan_id' => $this->plan_id,
            'splitter_id' => $this->splitter_id,
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
        if (!Auth::user()->can('edit subscriptions'))
        {
            abort(403, 'You are not allowed to this page');
        }
        return view('livewire.subscriptions.edit-subscription');
    }
}
