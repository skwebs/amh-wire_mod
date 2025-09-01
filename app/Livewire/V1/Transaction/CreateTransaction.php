<?php

namespace App\Livewire\V1\Transaction;

use App\Models\Customer;
use Livewire\Attributes\Title;
use Livewire\Component;

class CreateTransaction extends Component
{
    public $customer;
    public $type;
    public $amount;
    public $datetime;
    public $particulars;

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
        $this->type = request('type') === 'd' ? 'debit' : (request('type') === 'c' ? 'credit' : null);
        $this->datetime = now()->format('Y-m-d\TH:i');
    }

    public function saveTransaction()
    {
        $this->validate([
            'amount' => 'required|numeric|min:0',
            'datetime' => 'required|date_format:Y-m-d\TH:i|before_or_equal:now',
            'particulars' => 'nullable|string|max:255',
        ]);

        $this->customer->transactions()->create([
            'amount' => $this->amount,
            'datetime' => $this->datetime,
            'particulars' => $this->particulars,
            'type' => $this->type,
        ]);

        session()->flash('message', 'Transaction created successfully.');

        return $this->redirect(route('customer.transactions', $this->customer->id), navigate: true);
    }

    #[Title('Create Transaction')]
    public function render()
    {
        return view('livewire.v1.transaction.create-transaction', [
            'transactionType' => $this->type,
            'customer' => $this->customer,
        ]);
    }
}
