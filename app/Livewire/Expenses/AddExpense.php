<?php

namespace App\Livewire\Expenses;

use Livewire\Component;
use App\Models\Expense;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AddExpense extends Component
{
    public $payment_methods = [];
    public $title;
    public $description;
    public $date_time_issued;
    public $amount;
    public $payment_method_id;
    public $reference_number;

    public function mount()
    {
        // Load active payment methods
        $this->payment_methods = PaymentMethod::where('is_active', true)->get();
        $this->date_time_issued = now()->format('Y-m-d\TH:i');
    }

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

    public function save()
    {
        if (!Auth::user()->can('add expenses'))
        {
            abort(403, 'Unauthorized action');
        }

        $this->validate();

        Expense::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'date_time_issued' => Carbon::parse($this->date_time_issued),
            'amount' => $this->amount,
            'payment_method_id' => $this->payment_method_id,
            'reference_number' => $this->reference_number,
            'is_approved' => false, // default
        ]);

        // Reset fields after submission
        $this->reset(['title', 'description', 'date_time_issued', 'amount', 'payment_method_id', 'reference_number']);
        $this->dispatch('expense-added');
        $this->dispatch('show-toast', [
            'message' => 'Payment added successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        return view('livewire.expenses.add-expense');
    }
}
