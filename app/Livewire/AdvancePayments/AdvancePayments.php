<?php

namespace App\Livewire\AdvancePayments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AdvancePayment;
use Livewire\Attributes\Url;
use Auth;

class AdvancePayments extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public $sortField = 'paid_at';
    public $sortDirection = 'desc';

    public $per_page = 10;

    protected $paginationTheme = 'tailwind';
    protected $listeners = [
        'advance-payment-added' => '$refresh',
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
        if (!Auth::user()->can('view advance payments'))
        {
            abort(403, 'You are not allowed to view this page');
        }

        $query = AdvancePayment::query()
            ->with(['subscription.subscriber','subscription.plan', 'paymentMethod']);

        if ($this->search) {
            $query->whereHas('subscription', function ($q) {
                $q->where('mikrotik_name', 'like', '%'.$this->search.'%')
                ->orWhereHas('subscriber', function($sq) {
                    $sq->whereRaw(
                        "CONCAT(first_name, ' ', IFNULL(middle_name, ''), ' ', last_name) LIKE ?",
                        ['%' . $this->search . '%']
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]));
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

        $advancePayments = $query
            ->leftJoin('subscriptions', 'advance_payments.subscription_id', '=', 'subscriptions.id')
            ->leftJoin('plans', 'plans.id', '=', 'subscriptions.plan_id')
            ->leftJoin('payment_methods', 'advance_payments.payment_method_id', '=', 'payment_methods.id')
            ->orderBy($sortField, $this->sortDirection)
            ->select('advance_payments.*') // important to prevent ambiguous columns
            ->paginate($this->per_page);
        

        return view('livewire.advance-payments.advance-payments', [
            'advance_payments' => $advancePayments,
        ]);
    }
}
