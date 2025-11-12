<?php

namespace App\Livewire\Payments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use Livewire\Attributes\Url;

class Payments extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public $sortField = 'paid_at';
    public $sortDirection = 'desc';

    protected $paginationTheme = 'tailwind';
    protected $listeners = [
        'payment-added' => '$refresh',
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
        $payments = Payment::query()
            ->with(['subscription', 'paymentMethod'])
            ->whereHas('subscription', function ($query) {
                $query->where('mikrotik_name', 'like', '%'.$this->search.'%');
            })
            ->orWhere('reference_number', 'like', '%'.$this->search.'%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.payments.payments', [
            'payments' => $payments,
        ]);
    }
}
