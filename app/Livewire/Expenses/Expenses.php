<?php

namespace App\Livewire\Expenses;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Expense;
use Hashids\Hashids;
use Auth;

class Expenses extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'asc';
    public $per_page = 10;

    protected $listeners = [
        'expense-added' => '$refresh',
    ];

    protected $paginationTheme = 'tailwind';

    /**
     * Reset pagination when search changes
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Sort table by a given field
     */

    public function mount()
    {
        $this->sortBy('created_at');
        $this->sortDirection='desc';
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
        if (!Auth::user()->can('view expenses'))
        {
            abort(403, 'You are not allowed to view this page');
        }
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));

        $expenses = Expense::query()
            ->with('user')
            ->when($this->search, function ($query) {
                $search = $this->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->whereRaw(
                            "CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?", 
                            ["%$search%"]
                        );
                    })
                    ->orWhere('amount', 'like', "%$search%")
                    ->orWhereDate('date_issued', 'like', "%$search%");
                });
            })
            ->leftJoin('users', 'users.id', '=', 'expenses.user_id') // only for sorting
            ->select('expenses.*')
            ->orderBy($this->sortField == 'user.first_name' ? 'users.first_name' : $this->sortField, $this->sortDirection)
            ->paginate($this->per_page);

        // Encode IDs for frontend routes
        foreach ($expenses as $expense) {
            $expense->hash = $hashids->encode($expense->id);
        }

        return view('livewire.expenses.expenses',[
            'expenses' => $expenses
        ]);
    }
}
