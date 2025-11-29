<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Hashids\Hashids;
use Auth;

class AddUser extends Component
{
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
    public $is_active = false;
    public $is_password_reset;
    public $role;

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'birthdate' => 'nullable|date',
        'gender' => 'required|in:male,female,other',
        'contact_number' => 'nullable|string|max:30',
        'is_active' => 'required|boolean',
        'role' => 'required|exists:roles,name', // <--- NEW
    ];

    protected $listeners = [
        'showToast' => 'showToast',
        'region-updated' => 'setRegion',
        'province-updated' => 'setProvince',
        'municipality-updated' => 'setMunicipality',
        'barangay-updated' => 'setBarangay',
    ];

    public function save()
    {
        if (!Auth::user()->can('add users'))
        {
            abort(403, 'Unauthorized action');
        }
        $this->validate();
        // Create User
        $user = User::create([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => Hash::make('password123'),
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

        // Assign Role
        $user->assignRole($this->role);
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $hash = $hashids->encode($user->id);
        $this->dispatch('show-toast', [
            'message' => 'User added successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
        
        return redirect()->route('user.edit', ['hash' => $hash]);
    }

    public function render()
    {
        if (!Auth::user()->can('add users'))
        {
            abort(403, 'You are not allowed to this page');
        }
        return view('livewire.users.add-user', [
            'roles' => Role::all(), // <--- send roles to view
        ]);
    }
}
