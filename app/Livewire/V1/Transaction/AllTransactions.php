<?php

namespace App\Livewire\V1\Transaction;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Carbon\Carbon;

class AllTransactions extends Component
{
    public $user;
    public $transactions;
    public $filter = 'last_30_days'; // Options: 'last_30_days', 'current_month'

    public function mount()
    {
        if (!Auth::check()) {
            $this->redirectRoute('login');
            return;
        }

        $this->user = Auth::user();
        $this->fetchTransactions();
    }

    public function setFilter($filter)
    {
        $this->filter = in_array($filter, ['last_30_days', 'current_month']) ? $filter : 'last_30_days';
        $this->fetchTransactions();
    }

    public function fetchTransactions()
    {
        $query = Transaction::whereHas('customer', function ($query) {
            $query->where('user_id', $this->user->id);
        })->with('customer')->orderBy('datetime', 'desc');

        // Apply date filter
        if ($this->filter === 'last_30_days') {
            $query->where('datetime', '>=', Carbon::now()->subDays(30));
        } elseif ($this->filter === 'current_month') {
            $query->whereYear('datetime', Carbon::now()->year)
                ->whereMonth('datetime', Carbon::now()->month);
        }

        $this->transactions = $query->get()
            ->groupBy(fn($txn) => Carbon::parse($txn->datetime)->format('Y-m-d'))
            ->all();
    }

    #[Title('All Transactions')]
    public function render()
    {
        return view('livewire.v1.transaction.all-transactions');
    }
}
