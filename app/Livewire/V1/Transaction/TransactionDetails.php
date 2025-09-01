<?php

namespace App\Livewire\V1\Transaction;

use App\Models\Customer;
use App\Models\Transaction;
use Livewire\Attributes\Title;
use Livewire\Component;

class TransactionDetails extends Component
{
    public $customer;
    public $transaction;

    public function mount(Customer $customer, Transaction $transaction)
    {
        $this->customer = $customer;
        $this->transaction = $transaction;
    }

    public function delete()
    {
        $this->transaction->delete();
        session()->flash('message', 'Transaction deleted successfully.');

        return $this->redirect(route('customer.transactions', $this->customer->id), navigate: true);
    }

    #[Title('Transaction Details')]
    public function render()
    {
        return view('livewire.v1.transaction.transaction-details');
    }
}
