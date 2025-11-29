<?php

namespace App\Livewire\Subscribers;

use Livewire\Component;
use App\Models\Subscriber;
use Masmerise\Toaster\Toaster;
use Hashids\Hashids;
use Auth;

class AddSubscriber extends Component
{
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $birthdate;
    public $gender = 'male';
    public $contact_number;
    public $address_line_1;
    public $address_line_2;

    // Address IDs
    public $region_id;
    public $province_id;
    public $municipality_id;
    public $barangay_id;

    public $is_active = true;

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:subscribers,email',
        'birthdate' => 'required|date',
        'gender' => 'required|in:male,female,other',
        'contact_number' => 'required|string|max:20',
        'address_line_1' => 'nullable|string|max:255',
        'address_line_2' => 'nullable|string|max:255',
        'region_id' => 'nullable|integer',
        'province_id' => 'nullable|integer',
        'municipality_id' => 'nullable|integer',
        'barangay_id' => 'nullable|integer',
        'is_active' => 'boolean',
    ];

    protected $listeners = [
        'showToast' => 'showToast',
        'region-updated' => 'setRegion',
        'province-updated' => 'setProvince',
        'municipality-updated' => 'setMunicipality',
        'barangay-updated' => 'setBarangay',
    ];

    public function setRegion($data)
    {
        $this->region_id = $data['region_id'];
    }

    public function setProvince($data)
    {
        $this->province_id = $data['province_id'];
    }

    public function setMunicipality($data)
    {
        $this->municipality_id = $data['municipality_id'];
    }

    public function setBarangay($data)
    {
        $this->barangay_id = $data['barangay_id'];
    }

    public function save()
    {
        if (!Auth::user()->can('add subscribers'))
        {
            abort(403, 'Unauthorized action');
        }
        $this->validate();

        $subscriber = Subscriber::create([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'birthdate' => $this->birthdate ? date('Y-m-d', strtotime($this->birthdate)) : null,
            'gender' => $this->gender,
            'contact_number' => $this->contact_number,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'region_id' => $this->region_id,
            'province_id' => $this->province_id,
            'municipality_id' => $this->municipality_id,
            'barangay_id' => $this->barangay_id,
            'is_active' => $this->is_active,
        ]);

        // Redirect to edit page (if using hashids, inject or instantiate $hashids)
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $hash = $hashids->encode($subscriber->id);
        $this->dispatch('show-toast', [
            'message' => 'Subscriber added successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
        return redirect()->route('subscribers.edit', ['hash' => $hash]);
    }

    public function render()
    {
        return view('livewire.subscribers.add-subscriber');
    }
}
