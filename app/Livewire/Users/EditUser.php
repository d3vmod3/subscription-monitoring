<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Hashids\Hashids;
use Spatie\Permission\Models\Role;
use Auth;
use Illuminate\Support\Facades\Hash;

class EditUser extends Component
{
    public $user;
    public $user_id;
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
    public $is_password_reset;
    public $role;

    protected $listeners = [
        'showToast' => 'showToast',
        'region-updated' => 'setRegion',
        'province-updated' => 'setProvince',
        'municipality-updated' => 'setMunicipality',
        'barangay-updated' => 'setBarangay',
    ];

    protected function rules () {
        return [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'birthdate' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'contact_number' => 'nullable|string|max:30',
            'is_active' => 'required|boolean',
            'role' => 'required|exists:roles,name',
        ];
    }

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

    public function mount($hash)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $decoded = $hashids->decode($hash);
        $id = $decoded[0] ?? null;

        if (!$id) {
            abort(404);
        }

        $this->user = User::findOrFail($id);
        $this->user_id = $id;
        $this->first_name = $this->user->first_name;
        $this->middle_name = $this->user->middle_name;
        $this->last_name = $this->user->last_name;
        $this->email = $this->user->email;
        $this->birthdate = optional($this->user->birthdate)->format('Y-m-d');
        $this->gender = $this->user->gender;
        $this->contact_number = $this->user->contact_number;
        $this->address_line_1 = $this->user->address_line_1;
        $this->address_line_2 = $this->user->address_line_2;
        $this->region_id = $this->user->region_id;
        $this->province_id = $this->user->province_id;
        $this->municipality_id = $this->user->municipality_id;
        $this->barangay_id = $this->user->barangay_id;
        $this->is_active = (bool) $this->user->is_active;
        $this->is_password_reset = (bool) $this->user->is_password_reset;
        $this->role = $this->user->getRoleNames()->first();
    }

    public function save()
    {
        if (!Auth::user()->can('edit users'))
        {
            abort(403, 'Unauthorized action');
        }
        $this->validate();
        $this->user->update([
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

        $this->user->syncRoles([$this->role]);

        $this->dispatch('show-toast', [
            'message' => 'User updated successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function reset_password()
    {
        $this->user->update([
            'password' => Hash::make('password123'),
            'is_password_reset' => true,
        ]);

         $this->dispatch('show-toast', [
            'message' => 'Password reset successfully for ' . $this->first_name . "!",
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        if (!Auth::user()->can('edit users'))
        {
            abort(403, 'You are not allowed to this page');
        }
        return view('livewire.users.edit-user',['roles' => Role::all(),]);
    }
}
