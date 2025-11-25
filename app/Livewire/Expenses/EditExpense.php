<?php

namespace App\Livewire\Expenses;

use Livewire\Component;
use App\Models\Expense;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Hashids\Hashids;

class EditExpense extends Component
{
    public $payment_methods = [];
    public $title;
    public $description;
    public $date_time_issued;
    public $amount;
    public $expense_id;
    public $expense;
    public $payment_method_id;
    public $reference_number;
    public $status;
    public $added_by_fullname;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_time_issued' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'reference_number' => 'nullable|string|max:100',
        ];
    }

    public function mount($hash)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $decoded = $hashids->decode($hash);

        abort_if(empty($decoded), 404, 'Invalid Payment');

        $this->expense_id = $decoded[0];
        $this->payment_methods = PaymentMethod::where('is_active', true)->get();
        $this->expense = Expense::with('payment_method','user')
            ->findOrFail($this->expense_id);
        $this->title = $this->expense->title;
        $this->description = $this->expense->description;
        $this->reference_number = $this->expense->reference_number;
        $this->amount = $this->expense->amount;
        $this->date_time_issued = $this->expense->date_time_issued->format('Y-m-d\TH:i');
        $this->status = $this->expense->status;
        $this->payment_method_id = $this->expense->payment_method_id;
        $this->added_by_fullname = $this->expense->user->getFullNameAttribute();

    }

    public function save()
    {
        if (!Auth::user()->can('edit expenses'))
        {
            abort(403, 'Unauthorized action');
        }

        $this->validate();

        $this->expense->update([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'date_time_issued' => Carbon::parse($this->date_time_issued),
            'amount' => $this->amount,
            'payment_method_id' => $this->payment_method_id,
            'reference_number' => $this->reference_number,
            'status' => $this->status,
        ]);

        
        $this->dispatch('show-toast', [
            'message' => 'Expenses Updated successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        return view('livewire.expenses.edit-expense');
    }

    public function delete()
    {
        if (!Auth::user()->can('delete expenses'))
        {
            abort(403, 'Unauthorized action');
        }
        $this->expense->delete();
        $this->dispatch('expense-deleted');
        $this->dispatch('show-toast', [
            'message' => 'Expenses deleted successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
        $this->expense=null;
    }
}
