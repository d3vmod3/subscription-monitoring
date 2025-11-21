<?php

namespace App\Livewire\PassiveOpticalNetworks;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PassiveOpticalNetwork;
use Livewire\Attributes\Url;
use Hashids\Hashids;
use Auth;

class PassiveOpticalNetworks extends Component
{
    use WithPagination;

    // ✅ URL search param binding (optional)
    #[Url(as: 'search')]
    public ?string $search = '';

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $paginationTheme = 'tailwind';

    protected $listeners = [
        'pon-added' => '$refresh',
    ];

    // Reset pagination when search changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Sorting handler
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
        if (!Auth::user()->can('view passive optical networks'))
        {
            abort(403, 'You are not allowed to view this page');
        }
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));

        $query = PassiveOpticalNetwork::query()
            ->leftJoin('sectors', 'pons.sector_id', '=', 'sectors.id')
            ->select('pons.*')
            ->with('sector')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('pons.name', 'like', "%{$this->search}%")
                    ->orWhere('pons.description', 'like', "%{$this->search}%");
                });
            });

        // Handle sorting
        if ($this->sortField === 'sector_name') {
            $query->orderBy('sectors.name', $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $pons = $query->paginate(10);

        foreach ($pons as $pon) {
            $pon->hash = $hashids->encode($pon->id);
        }

        return view('livewire.passive-optical-networks.passive-optical-networks', [
            'pons' => $pons,
        ]);
    }

}
