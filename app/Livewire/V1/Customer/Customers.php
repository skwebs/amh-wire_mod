<?php

namespace App\Livewire\V1\Customer;

use App\Models\Customer;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Customers extends Component
{
    public $customers;
    public $billing_date;

    public function mount()
    {
        $this->customers = Customer::with('transactions')
            ->select('customers.id', 'customers.name', 'customers.type')
            ->where('customers.user_id', Auth::id()) // Filter by logged-in user
            ->leftJoin(
                'transactions',
                fn($join) =>
                $join->on('customers.id', '=', 'transactions.customer_id')
                    ->whereNull('transactions.deleted_at')
            )
            ->selectRaw('MAX(transactions.datetime) as latest_transaction_date')
            ->selectRaw('SUM(CASE WHEN transactions.type = "debit" THEN transactions.amount ELSE 0 END) as total_debit')
            ->selectRaw('SUM(CASE WHEN transactions.type = "credit" THEN transactions.amount ELSE 0 END) as total_credit')
            ->groupBy('customers.id', 'customers.name', 'customers.type')
            ->orderBy('latest_transaction_date', 'desc')
            ->get();
    }

    #[Title('Customer List')]
    public function render()
    {
        return view('livewire.v1.customer.customers');
    }
}
