<?php

namespace App\Livewire\Splitters;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Splitter;
use Hashids\Hashids;
use App\Models\Napbox;

class Splitters extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    

    protected $paginationTheme = 'tailwind';
    protected $queryString = ['search'];

    protected $listeners = ['splitter-added' => '$refresh'];

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

        $splitters = Splitter::query()
            ->when($this->search, function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        foreach ($splitters as $splitter) {
            $splitter->hash = $hashids->encode($splitter->id);
        }

        return view('livewire.splitters.splitters', [
            'splitters' => $splitters,
        ]);
    }
}
