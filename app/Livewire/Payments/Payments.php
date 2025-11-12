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
        $query = Payment::query()
            ->with(['subscription.subscriber', 'paymentMethod']);

        if ($this->search) {
            $query->whereHas('subscription', function ($q) {
                $q->where('mikrotik_name', 'like', '%'.$this->search.'%')
                ->orWhereHas('subscriber', function($sq) {
                    $sq->whereRaw(
                        "CONCAT(first_name, ' ', IFNULL(middle_name, ''), ' ', last_name) LIKE ?",
                        ['%' . $this->search . '%']
                    );
                });
            })
            ->orWhere('reference_number', 'like', '%'.$this->search.'%');
        }

        // Only allow sorting by actual columns
        $allowedSorts = [
            'paid_at' => 'paid_at',
            'amount' => 'amount',
            'reference_number' => 'reference_number',
            'mikrotik_name' => 'subscriptions.mikrotik_name',
            'payment_method' => 'payment_methods.name',
            'status' => 'status',
        ];

        $sortField = $allowedSorts[$this->sortField] ?? 'paid_at';

        $payments = $query
            ->leftJoin('subscriptions', 'payments.subscription_id', '=', 'subscriptions.id')
            ->leftJoin('payment_methods', 'payments.payment_method_id', '=', 'payment_methods.id')
            ->orderBy($sortField, $this->sortDirection)
            ->select('payments.*') // important to prevent ambiguous columns
            ->paginate(10);

        return view('livewire.payments.payments', [
            'payments' => $payments,
        ]);
    }

}
