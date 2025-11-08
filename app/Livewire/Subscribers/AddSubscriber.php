<?php

namespace App\Livewire\Subscribers;

use Livewire\Component;
use App\Models\Subscriber;
use Masmerise\Toaster\Toaster;

class AddSubscriber extends Component
{
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $birthdate;
    public $gender = 'male';
    public $contact_number;
    public $address;
    public $status = 'active';

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:subscribers,email',
        'birthdate' => 'required|date',
        'gender' => 'required|in:male,female,other',
        'contact_number' => 'required|string|max:20',
        'address' => 'required|string|max:500',
        'status' => 'required|in:active,inactive',
    ];

    public function save()
    {
        $this->validate();

        Subscriber::create([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'birthdate' => optional($this->birthdate)->format('Y-m-d'),
            'gender' => $this->gender,
            'contact_number' => $this->contact_number,
            'address' => $this->address,
            'status' => $this->status,
        ]);

        $hash = $hashids->encode($subscriber->id);
        return redirect()->route('subscribers.edit', ['hash' => $hash]);
    }

    public function render()
    {
        return view('livewire.subscribers.add-subscriber');
    }
}
