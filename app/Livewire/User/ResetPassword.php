<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetPassword extends Component
{
    public string $password = '';
    public string $confirm_password = '';
    public $default_message;



    public function mount()
    {
        $this->default_message = 'Please contact your administrator to reset your password';
    }
    public function save()
    {
        // 🔐 Validate password fields
        $this->validate([
            'password' => ['required', 'string', 'min:8'],
            'confirm_password' => ['same:password'],
        ], [
            'confirm_password.same' => 'Password confirmation does not match.',
        ]);

        $user = Auth::user();

        // ❗ Remove hard-coded password: use user input
        $user->password = Hash::make($this->password);
        $user->is_password_reset = false;
        $user->save();
        $this->default_message = null;
        // 🔔 Toast event
        $this->dispatch('show-toast', [
            'message' => 'Password reset successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
        // 🔁 Redirect based on role
        $this->dispatch('redirect-after-toast');
        // if ($user->hasRole('admin')) {
        //     return redirect()->route('dashboard');
        // }

        // if ($user->hasRole('user')) {
        //     return redirect()->route('user.dashboard');
        // }

        // Default fallback
        // return redirect()->to('/');
    }

    public function render()
    {
        return view('livewire.user.reset-password');
    }
}
