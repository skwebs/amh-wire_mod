<?php

namespace App\Livewire\V1\Transaction;

use App\Models\Customer;
use App\Models\Transaction;
use Livewire\Attributes\Title;
use Livewire\Component;

class UpdateTransaction extends Component
{
    public $customer;
    public $transaction;
    public $type;
    public $amount;
    public $datetime;
    public $particulars;

    public function mount(Customer $customer, Transaction $transaction)
    {
        $this->customer = $customer;
        $this->transaction = $transaction;
        $this->type = $transaction->type;
        // Format amount to remove .00 for whole numbers
        $amountFloat = (float) $transaction->amount;
        $this->amount = $amountFloat == floor($amountFloat)
            ? (int) $amountFloat
            : (float) number_format($amountFloat, 2, '.', '');
        $this->datetime = $transaction->datetime->format('Y-m-d\TH:i');
        $this->particulars = $transaction->particulars;
    }

    public function updateTransaction()
    {
        $this->validate([
            'amount' => 'required|numeric|min:0',
            'datetime' => 'required|date_format:Y-m-d\TH:i|before_or_equal:now',
            'particulars' => 'nullable|string|max:255',
        ]);

        // Convert amount to float for storage
        $amount = (float) $this->amount;

        $this->transaction->update([
            'amount' => $amount,
            'datetime' => $this->datetime,
            'particulars' => $this->particulars,
            'type' => $this->type,
        ]);

        session()->flash('message', 'Transaction updated successfully.');

        return $this->redirect(route('customer.transactions', $this->customer->id), navigate: true);
    }

    #[Title('Update Transaction')]
    public function render()
    {
        return view('livewire.v1.transaction.update-transaction');
    }
}
