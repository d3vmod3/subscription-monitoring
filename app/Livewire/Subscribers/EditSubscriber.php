<?php

namespace App\Livewire\Subscribers;

use Livewire\Component;
use App\Models\Subscriber;
use Hashids\Hashids;

class EditSubscriber extends Component
{
    public $subscriber;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $birthdate;
    public $gender;
    public $contact_number;
    public $address;
    public $status;

    public $listeners = [
        'showToast' => 'showToast'
    ];

    public function mount($hash, Hashids $hashids)
    {
        $decoded = $hashids->decode($hash);
        $id = $decoded[0] ?? null;

        if (!$id) {
            abort(404);
        }

        $this->subscriber = Subscriber::findOrFail($id);

        $this->first_name = $this->subscriber->first_name;
        $this->middle_name = $this->subscriber->middle_name;
        $this->last_name = $this->subscriber->last_name;
        $this->email = $this->subscriber->email;
        $this->birthdate = optional($this->subscriber->birthdate)->format('Y-m-d');
        $this->gender = $this->subscriber->gender;
        $this->contact_number = $this->subscriber->contact_number;
        $this->address = $this->subscriber->address;
        $this->status = $this->subscriber->status;
    }

    public function update()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
        ]);

        $this->subscriber->update([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'birthdate' => $this->birthdate,
            'gender' => $this->gender,
            'contact_number' => $this->contact_number,
            'address' => $this->address,
            'status' => $this->status,
        ]);

        $toasData = [
            'message' => 'Subscriber updated successfully!',
            'type' => 'success',
            'duration' => 4000,
        ];
        $this->dispatch('show-toast', data:$toasData);
    }

    public function render()
    {
        return view('livewire.subscribers.edit-subscriber');
    }
}
