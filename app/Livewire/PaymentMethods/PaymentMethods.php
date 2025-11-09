<?php

namespace App\Livewire\PaymentMethods;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PaymentMethod;
use Livewire\Attributes\Url;

class PaymentMethods extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public $sortField = 'created_at';   // default sort column
    public $sortDirection = 'desc';     // default sort direction

    protected $paginationTheme = 'tailwind';

    // Reset pagination when search updates
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Toggle sorting
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
        $paymentMethods = PaymentMethod::query()
            ->where(function($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('description', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.payment-methods.payment-methods', [
            'paymentMethods' => $paymentMethods,
        ]);
    }
}
