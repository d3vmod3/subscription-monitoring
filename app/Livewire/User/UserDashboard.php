<?php

namespace App\Livewire\User;

use Livewire\Component;
use Auth;

class UserDashboard extends Component
{
    public function render()
    {
         if (!Auth::user()->can('view user dashboard'))
        {
            abort(403, 'You are not allowed to this page');
        }
        return view('livewire.user.user-dashboard');
    }

    public function redirectTo($route_name)
    {
        return redirect()->route($route_name);
    }
}
