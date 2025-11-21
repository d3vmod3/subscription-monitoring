<?php

namespace App\Livewire\Sectors;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sector;
use Hashids\Hashids;
use Auth;

class Sectors extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'asc';

    protected $listeners = [
        'sector-added' => '$refresh',
        'sector-updated' => '$refresh',
        'sector-deleted' => '$refresh',
    ];

    protected $paginationTheme = 'tailwind';

    /**
     * Reset pagination when search changes
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Sort table by a given field
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
     * Render sectors list
     */
    public function render()
    {
        if (!Auth::user()->can('view sectors'))
        {
            abort(403, 'You are not allowed to view this page');
        }
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));

        $sectors = Sector::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        // Encode IDs for frontend routes
        foreach ($sectors as $sector) {
            $sector->hash = $hashids->encode($sector->id);
        }

        return view('livewire.sectors.sectors', [
            'sectors' => $sectors,
        ]);
    }
}
