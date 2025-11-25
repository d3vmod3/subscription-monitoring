<?php

namespace App\Livewire\Payments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use Livewire\Attributes\Url;
use Auth;

class Payments extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public $sortField = 'paid_at';
    public $sortDirection = 'desc';

    public $status="";

    public $per_page = 10;

    public $selectAll = false;
    public $selectedItems = [];

    protected $paginationTheme = 'tailwind';
    protected $listeners = [
        'payment-added' => '$refresh',
    ];

    public function updatingSearch()
    {
        $this->selectAll=false;
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

    public function updatedSelectAll($value)
    {
        if ($value) {
            $payments = $this->getPayments();
            $this->selectedItems = $payments->pluck('id')->toArray();
            $this->resetValidation();
        } else {
            $this->selectedItems = [];
        }
    }

    public function updatedSelectedItems()
    {
        $this->resetValidation();
        $payments = $this->getPayments();
        $this->selectAll = count($this->selectedItems) === $payments->count();
    }

    public function resetError()
    {
        $this->resetValidation();
    }

    public function applyBulkStatus()
    {
        //validate if there are selected
        if($this->validateBulkAction())
        {
            if (!Auth::user()->can('edit payments'))
            {
                abort(403, 'Unauthorized action');
            }
            foreach ($this->selectedItems as $id) {
                Payment::where('id', $id)
                ->where('status','Pending')
                ->orWhere('status','Disapproved')->update([
                    'status' => $this->status,
                ]);
            }

            // Reset selection
            $this->selectedItems = [];
            $this->selectAll = false;

            $this->dispatch('show-toast', [
                'message' => 'Selected payments status have been updated!',
                'type' => 'success',
                'duration' => 3000,
            ]);
            $this->dispatch('$refresh');
        }
    }

    public function validateBulkAction()
    {
        if(count($this->selectedItems)==0)
        {
            $this->addError('selectAll', "Please select at least one payment.");
            return false;
        }
        if(!$this->status)
        {
            $this->addError('status', "Please Select Status");
            return false;
        }
        return true;
    }

    public function getPayments()
    {
        $query = Payment::query()
            ->with(['subscription.subscriber','subscription.plan','paymentMethod']);

        if ($this->search) {
            $search = $this->search;

            $query->where(function ($q) use ($search) {
                // Search subscription's mikrotik_name if it exists
                $q->whereHas('subscription', function ($sq) use ($search) {
                    $sq->where('mikrotik_name', 'like', "%$search%");

                    // Only search subscriber if relationship exists
                    if (method_exists($sq->getModel(), 'subscriber')) {
                        $sq->orWhereHas('subscriber', function ($subq) use ($search) {
                            $subq->whereRaw(
                                "CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?", 
                                ["%$search%"]
                            );
                        });
                    }
                })
                // Search user full name
                ->orWhereHas('user', function ($uq) use ($search) {
                    $uq->whereRaw(
                        "CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?", 
                        ["%$search%"]
                    );
                })
                // Search payment reference number
                ->orWhere('reference_number', 'like', "%$search%");
            });
        }


        $allowedSorts = [
            'subscriber_name'    => 'subscribers.last_name', // or whatever field you want
            'subscription_id'    => 'subscriptions.mikrotik_name',
            'plan_id'            => 'plans.name',
            'reference_number'   => 'reference_number',
            'payment_method_id'  => 'payment_methods.name',
            'amount'             => 'amount',
            'created_at'         => 'payments.created_at',
            'is_approved'        => 'status',
            'paid_at'            => 'paid_at',
            'mikrotik_name'      => 'subscriptions.mikrotik_name',
            'status'             => 'status',
            'user'               => 'users.first_name'
        ];

        $sortField = $allowedSorts[$this->sortField] ?? 'paid_at';

        return $query
            ->leftJoin('subscriptions','payments.subscription_id','=','subscriptions.id')
            ->leftJoin('subscribers','subscriptions.subscriber_id','=','subscribers.id')
            ->leftJoin('plans','plans.id','=','subscriptions.plan_id')
            ->leftJoin('payment_methods','payments.payment_method_id','=','payment_methods.id')
            ->leftJoin('users','users.id','=','payments.user_id')
            ->orderBy($sortField, $this->sortDirection)
            ->select('payments.*')
            ->paginate($this->per_page);
    }


    public function render()
    {
        if (!Auth::user()->can('view payments'))
        {
            abort(403, 'You are not allowed to view this page');
        }
        
        return view('livewire.payments.payments', [
            'payments' => $this->getPayments(),
        ]);
    }

}
