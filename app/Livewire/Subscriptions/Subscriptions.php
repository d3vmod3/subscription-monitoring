<?php

namespace App\Livewire\Subscriptions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subscription;
use Hashids\Hashids;

class Subscriptions extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $paginationTheme = 'tailwind';
    protected $queryString = ['search'];

    protected $listeners = ['subscription-added' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));

        $subscriptions = Subscription::with(['subscriber', 'plan', 'splitter'])
            ->when($this->search, function($q) {
                $q->where('mikrotik_name', 'like', "%{$this->search}%")
                ->orWhereHas('subscriber', function($query) {
                    $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$this->search}%"]);
                })
                ->orWhereHas('plan', function($query) {
                    $query->where('name', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        foreach ($subscriptions as $subscription) {
            $subscription->hash = $hashids->encode($subscription->id);
        }

        return view('livewire.subscriptions.subscriptions', [
            'subscriptions' => $subscriptions,
        ]);
    }

}
