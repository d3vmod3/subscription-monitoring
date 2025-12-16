<?php

namespace App\Livewire\Payments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;

class Payments extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public $sortField = 'paid_at';
    public $sortDirection = 'desc';

    public $statusAll = false;
    public $status;
    public $statusApproved = false;
    public $statusDisapproved = false;
    public $statusPending = true;
    public $selectedStatuses = [];
    public $totalSelectedAmount=0;

    public $filter_status;

    public $dateFrom;
    public $dateTo;

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

    public function updatedStatusAll()
    {
        if ($this->statusAll)
        {
            $this->statusApproved = true;
            $this->statusDisapproved = true;
            $this->statusPending = true;
        }
        
    }

    public function updatedStatusApproved()
    {
        if($this->statusApproved === false)
        {
            $this->statusAll = false;
        }
        if ($this->statusApproved && $this->statusDisapproved &&  $this->statusPending)
        {
            $this->statusAll = true;
        }
    }

    public function updatedStatusDisapproved()
    {
        if($this->statusDisapproved === false)
        {
            $this->statusAll = false;
        }
        if ($this->statusApproved && $this->statusDisapproved &&  $this->statusPending)
        {
            $this->statusAll = true;
        }
    }

    public function updatedStatusPending()
    {
        if($this->statusPending === false)
        {
            $this->statusAll = false;
        }
        if ($this->statusApproved && $this->statusDisapproved &&  $this->statusPending)
        {
            $this->statusAll = true;
        }
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
            // dd($this->selectedItems);
            $this->totalSelectedAmount = $payments->sum('paid_amount');
            
            
            $this->resetValidation();
        } else {
            
            $this->selectedItems = [];
            $this->totalSelectedAmount = 0;
        }
    }

    public function updatedSelectedItems($value)
    {
        $this->resetValidation();
        $payments = $this->getPayments();
        $this->selectAll = count($this->selectedItems) === $payments->count();
        // dd("working")
        $this->totalSelectedAmount = Payment::whereIn('id', $this->selectedItems)->sum('paid_amount');
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
                    ->update([
                    'status' => $this->status,
                ]);
            }

            // Reset selection
            // $this->selectedItems = [];
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

    public function updatedDateFrom()
    {
        $this->validateOnly('dateFrom');
        $this->validateOnly('dateTo'); // re-check relation
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->validateOnly('dateTo');
        $this->resetPage();
    }

    protected function rules()
    {
        return [
            'dateFrom' => 'nullable|date',
            'dateTo'   => 'nullable|date|after_or_equal:dateFrom',
        ];
    }

    protected $messages = [
        'dateTo.after_or_equal' => 'Date To must be the same or later than Date From.',
    ];
    
    public function getPayments()
    {
        $query = Payment::query()
            ->with(['subscription.subscriber','subscription.plan','paymentMethod']);

        // 🔍 Search logic (unchanged)
        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('subscription', function ($sq) use ($search) {
                    $sq->where('mikrotik_name', 'like', "%$search%")
                    ->orWhereHas('subscriber', function ($subq) use ($search) {
                            $subq->whereRaw("CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?", ["%$search%"]);
                        });
                })
                ->orWhereHas('user', function ($uq) use ($search) {
                    $uq->whereRaw("CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?", ["%$search%"]);
                })
                ->orWhere('reference_number', 'like', "%$search%");
            });
        }

        if ($this->dateFrom && $this->dateTo) {
            $query->whereBetween('payments.paid_at', [
                Carbon::parse($this->dateFrom)->startOfDay(),
                Carbon::parse($this->dateTo)->endOfDay(),
            ]);
        } elseif ($this->dateFrom) {
            $query->whereDate('payments.paid_at', '>=', $this->dateFrom);
        } elseif ($this->dateTo) {
            $query->whereDate('payments.paid_at', '<=', $this->dateTo);
        }

        // ✅ STATUS FILTER
        $selectedStatuses = [];

        if ($this->statusApproved)     $selectedStatuses[] = 'Approved';
        if ($this->statusDisapproved)  $selectedStatuses[] = 'Disapproved';
        if ($this->statusPending)      $selectedStatuses[] = 'Pending';
        

        // If none selected → show nothing
        if (empty($selectedStatuses)) {
            return Payment::where('id', null)->paginate($this->per_page);
        }
        
        $query->whereIn('payments.status', $selectedStatuses);

        // 📌 Sorting logic (unchanged)
        $allowedSorts = [/* ... existing ... */];
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

    public function mount()
    {
        $this->dateFrom = Carbon::parse(now())->format('Y-m-d');
        $this->dateTo = Carbon::parse(now())->format('Y-m-d');
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
