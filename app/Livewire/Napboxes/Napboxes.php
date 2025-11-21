<?php

namespace App\Livewire\Napboxes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Napbox;
use Hashids\Hashids;
use Auth;

class Napboxes extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $queryString = ['search'];

    protected $paginationTheme = 'tailwind';

    public $listeners = [
        'napbox-added' => '$refresh'
    ];

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
        if (!Auth::user()->can('view napboxes'))
        {
            abort(403, 'You are not allowed to view this page');
        }

        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));

        $napboxes = Napbox::with('pon.sector') // load PON and sector
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhereHas('pon', function ($q) {
                          $q->where('name', 'like', "%{$this->search}%");
                      });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Add hash for Edit links
        foreach ($napboxes as $napbox) {
            $napbox->hash = $hashids->encode($napbox->id);
        }

        return view('livewire.napboxes.napboxes', [
            'napboxes' => $napboxes,
        ]);
    }
}
