<?php

namespace App\Livewire\V1\Transaction;

use App\Models\Customer;
use App\Services\BillingPeriodCalculator;
use Carbon\Carbon;
use Livewire\Attributes\Title;
use Livewire\Component;

class Transactions extends Component
{
    public $customer;
    public $transactions;
    public $sortDir = 'desc';
    public $sortField = 'datetime';
    public $filter = 'current'; // 'current'|'previous'|'all'
    public ?Carbon $billingStartDate = null;
    public ?Carbon $billingEndDate = null;

    public function setFilter($filter)
    {
        $this->filter = in_array($filter, ['current', 'previous', 'all']) ? $filter : 'current';
        if (in_array($this->filter, ['current', 'previous'])) {
            $this->setBillingDates();
        }
        $this->fetchTransactions();
    }

    private function setBillingDates(): void
    {
        if ($this->customer->type !== 'credit_card') {
            $this->billingStartDate = null;
            $this->billingEndDate = null;
            return;
        }

        $period = $this->filter === 'current'
            ? BillingPeriodCalculator::currentPeriod($this->customer->billing_date)
            : BillingPeriodCalculator::previousPeriod($this->customer->billing_date);

        $this->billingStartDate = $period->startDate ? Carbon::parse($period->startDate) : null;
        $this->billingEndDate = $period->endDate ? Carbon::parse($period->endDate) : null;
    }

    private function getPeriodForFilter(): ?object
    {
        if ($this->customer->type !== 'credit_card') {
            return null;
        }

        if ($this->filter === 'current') {
            return BillingPeriodCalculator::currentPeriod($this->customer->billing_date);
        }

        if ($this->filter === 'previous') {
            return BillingPeriodCalculator::previousPeriod($this->customer->billing_date);
        }

        return null;
    }

    private function balanceWithinPeriod(?object $period): float
    {
        $query = $this->customer->transactions()->select('type', 'amount', 'datetime');

        if ($period && $period->startDate && $period->endDate) {
            $query->whereBetween('datetime', [$period->startDate, $period->endDate]);
        }

        return $query->get()->sum(fn($transaction) => $transaction->type === 'debit' ? $transaction->amount : -$transaction->amount);
    }

    private function sumWithinPeriod(?object $period, string $type): float
    {
        if (!$period || !$period->startDate || !$period->endDate) {
            return 0;
        }

        return $this->customer->transactions()
            ->select('type', 'amount', 'datetime')
            ->where('type', $type)
            ->whereBetween('datetime', [$period->startDate, $period->endDate])
            ->sum('amount');
    }

    public function calculateBalance(): float
    {
        if ($this->filter === 'current' && $this->customer->type === 'credit_card') {
            return $this->previousExpenses() + $this->currentExpenses() - $this->currentPayments();
        }

        $period = $this->getPeriodForFilter();
        return $this->balanceWithinPeriod($period);
    }

    public function currentExpenses(): float
    {
        if ($this->customer->type !== 'credit_card') {
            return 0;
        }

        $period = BillingPeriodCalculator::currentPeriod($this->customer->billing_date);
        return $this->sumWithinPeriod($period, 'debit');
    }

    public function previousExpenses(): float
    {
        if ($this->customer->type !== 'credit_card') {
            return 0;
        }

        $period = BillingPeriodCalculator::previousPeriod($this->customer->billing_date);
        return $this->sumWithinPeriod($period, 'debit');
    }

    public function currentPayments(): float
    {
        if ($this->customer->type !== 'credit_card') {
            return 0;
        }

        $period = BillingPeriodCalculator::currentPeriod($this->customer->billing_date);
        return $this->sumWithinPeriod($period, 'credit');
    }

    public function sortBy($field)
    {
        $allowedFields = ['id', 'amount', 'type', 'datetime'];
        $this->sortField = in_array($field, $allowedFields) ? $field : 'datetime';
        $this->sortDir = ($field === $this->sortField) ? ($this->sortDir === 'asc' ? 'desc' : 'asc') : 'asc';
        $this->fetchTransactions();
    }

    public function mount(Customer $customer)
    {
        if (!$customer->exists) {
            abort(404, 'Customer not found');
        }

        if ($this->customer->type === 'credit_card' && $customer->billing_date === null) {
            return redirect()->route('customer.update', [$customer, 'm' => 'Please set Billing Date for this Credit Card']);
        }

        $this->customer = $customer;
        if (in_array($this->filter, ['current', 'previous'])) {
            $this->setBillingDates();
        }
        $this->fetchTransactions();
    }

    public function fetchTransactions()
    {
        $query = $this->customer->transactions()->select('id', 'customer_id', 'particulars', 'amount', 'type', 'datetime');

        $period = $this->getPeriodForFilter();

        if ($period) {
            if ($period->startDate && $period->endDate) {
                $query->whereBetween('datetime', [$period->startDate, $period->endDate]);
            } else {
                $this->transactions = [];
                return;
            }
        }

        $this->transactions = $query
            ->orderBy($this->sortField, $this->sortDir)
            ->get()
            ->groupBy(fn($txn) => $txn->datetime->format('Y-m-d'))
            ->all();
    }

    #[Title('Transactions')]
    public function render()
    {
        return view('livewire.v1.transaction.transactions', [
            'balance' => $this->calculateBalance(),
            'currentExpenses' => $this->currentExpenses(),
            'previousExpenses' => $this->previousExpenses(),
            'currentPayments' => $this->currentPayments(),
        ]);
    }
}
