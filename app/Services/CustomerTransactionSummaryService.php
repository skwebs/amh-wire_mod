<?php

namespace App\Services;

use App\Models\Customer;
use App\Services\BillingPeriodCalculator;
use Exception;

class CustomerTransactionSummaryService
{
    /**
     * Get the sums for credit transactions in the current billing cycle,
     * debit transactions in the current billing cycle, and debit transactions
     * in the previous billing cycle.
     *
     * @param int $customerId
     * @return array
     * @throws Exception
     */
    public function getBillingCycleSums(int $customerId): array
    {
        $customer = Customer::find($customerId);

        if (!$customer) {
            throw new Exception('Customer not found');
        }

        if ($customer->type !== 'credit_card') {
            return [
                'current_debit' => 0.0,
                'current_credit' => 0.0,
                'previous_debit' => 0.0,
            ];
        }

        if ($customer->billing_date === null) {
            throw new Exception('Billing date not set for this credit card customer');
        }

        $currentPeriod = BillingPeriodCalculator::currentPeriod($customer->billing_date);
        $previousPeriod = BillingPeriodCalculator::previousPeriod($customer->billing_date);

        $currentDebit = $this->sumWithinPeriod($customer, $currentPeriod, 'debit');
        $currentCredit = $this->sumWithinPeriod($customer, $currentPeriod, 'credit');
        $previousDebit = $this->sumWithinPeriod($customer, $previousPeriod, 'debit');

        return [
            'current_debit' => $currentDebit,
            'current_credit' => $currentCredit,
            'previous_debit' => $previousDebit,
        ];
    }

    /**
     * Calculate the sum of transactions of a specific type within a given period.
     *
     * @param Customer $customer
     * @param ?object $period
     * @param string $type
     * @return float
     */
    private function sumWithinPeriod(Customer $customer, ?object $period, string $type): float
    {
        if (!$period || !$period->startDate || !$period->endDate) {
            return 0.0;
        }

        return $customer->transactions()
            ->where('type', $type)
            ->whereBetween('datetime', [$period->startDate, $period->endDate])
            ->sum('amount');
    }
}
