<?php

namespace App\Livewire\Plans;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Plan;
use Hashids\Hashids;

class Plans extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Live listener for when a plan is added via modal
    public $listeners = [
        'plan-added' => '$refresh'
    ];

    protected $queryString = ['search']; // optional: keep search in URL
    protected $paginationTheme = 'tailwind'; // match Tailwind styling

    /**
     * Reset page when search updates
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Toggle sorting by column
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Render the plan list
     */
    public function render()
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));

        $plans = Plan::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Add hash for Edit links
        foreach ($plans as $plan) {
            $plan->hash = $hashids->encode($plan->id);
        }

        return view('livewire.plans.plans', [
            'plans' => $plans,
        ]);
    }
}
