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
    public $address_line_1;
    public $address_line_2;
    public $region_id;
    public $province_id;
    public $municipality_id;
    public $barangay_id;
    public $is_active=false;

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

    public function mount($hash, Hashids $hashids)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
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
        $this->address_line_1 = $this->subscriber->address_line_1;
        $this->address_line_2 = $this->subscriber->address_line_2;
        $this->region_id = $this->subscriber->region_id;
        $this->province_id = $this->subscriber->province_id;
        $this->municipality_id = $this->subscriber->municipality_id;
        $this->barangay_id = $this->subscriber->barangay_id;
        $this->is_active = (bool) $this->subscriber->is_active;
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
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'region_id' => 'nullable|integer',
            'province_id' => 'nullable|integer',
            'municipality_id' => 'nullable|integer',
            'barangay_id' => 'nullable|integer',
            'is_active' => 'required|boolean',
        ]);
        // dd($this->barangay_id);
        $this->subscriber->update([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'birthdate' => $this->birthdate,
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

        $this->dispatch('show-toast', [
            'message' => 'Subscriber updated successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        return view('livewire.subscribers.edit-subscriber');
    }
}
