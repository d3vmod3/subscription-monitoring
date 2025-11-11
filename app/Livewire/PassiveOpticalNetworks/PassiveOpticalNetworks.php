<?php

namespace App\Livewire\PassiveOpticalNetworks;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PassiveOpticalNetwork;
use Livewire\Attributes\Url;
use Hashids\Hashids;

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
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));

        $pons = PassiveOpticalNetwork::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        foreach ($pons as $pon) {
            $pon->hash = $hashids->encode($pon->id);
        }

        return view('livewire.passive-optical-networks.passive-optical-networks', [
            'pons' => $pons,
        ]);
    }
}
