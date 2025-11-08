<?php

namespace App\Livewire\Subscribers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subscriber;
use Livewire\Attributes\Url;
use Hashids\Hashids;

class Subscribers extends Component
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
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));

        $subscribers = Subscriber::query()
            ->where(function($query) {
                $query->where('first_name', 'like', '%'.$this->search.'%')
                    ->orWhere('middle_name', 'like', '%'.$this->search.'%')
                    ->orWhere('last_name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('contact_number', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        // Add hashed IDs for easy access in the Blade
        foreach ($subscribers as $subscriber) {
            $subscriber->hash = $hashids->encode($subscriber->id);
        }

        return view('livewire.subscribers.subscribers', [
            'subscribers' => $subscribers,
        ]);
    }
}
