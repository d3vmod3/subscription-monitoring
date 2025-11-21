<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Livewire\Attributes\Url;
use Hashids\Hashids;
use Authl;

class Users extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public $sortField = 'created_at';   // default sort column
    public $sortDirection = 'desc';     // default sort direction

    protected $paginationTheme = 'tailwind';

    // Reset pagination on search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Toggle sorting
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            // Toggle direction
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        if (!Auth::user()->can('view users'))
        {
            abort(403, 'You are not allowed to view this page');
        }
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $search = $this->search;

        $users = User::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    // Flexible full name search
                    $q->whereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                      // Fallback to individual fields
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('middle_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('contact_number', 'like', "%{$search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        foreach ($users as $user) {
            $user->hash = $hashids->encode($user->id);
        }

        return view('livewire.users.users', [
            'users' => $users,
        ]);
    }
}
