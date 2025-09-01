<?php

namespace App\Services;

use Carbon\Carbon;

class BillingPeriodCalculator
{
    public static function currentPeriod(int $billingDay, ?Carbon $today = null): object
    {
        if ($billingDay < 1 || $billingDay > 28) {
            throw new \InvalidArgumentException('Invalid billing day');
        }

        if (!$today) {
            $today = Carbon::today();
        }

        $billingDate = $today->copy()->day($billingDay);
        $billingStartDate = $billingDate->copy()->addDay()->startOfDay();

        $startDate = $billingStartDate->isFuture()
            ? $today->copy()->subMonth()->day($billingDay + 1)->startOfDay()
            : $billingStartDate;

        $endDate = $billingStartDate->isFuture()
            ? $billingDate->endOfDay()
            : $today->copy()->addMonth()->day($billingDay)->endOfDay();

        $gracePeriod = $startDate ? $startDate->copy()->subDay()->addDays(20)->endOfDay() : null;

        return (object) [
            'startDate' => $startDate ? $startDate->format('Y-m-d H:i:s') : null,
            'endDate' => $endDate ? $endDate->format('Y-m-d H:i:s') : null,
            'gracePeriod' => $gracePeriod ? $gracePeriod->format('Y-m-d H:i:s') : null,
        ];
    }

    public static function previousPeriod(int $billingDay, ?Carbon $today = null): object
    {
        $current = self::currentPeriod($billingDay, $today);

        if (!$current->startDate || !$current->endDate) {
            return (object) [
                'startDate' => null,
                'endDate' => null,
            ];
        }

        $start = Carbon::parse($current->startDate)->subMonth();
        $end = Carbon::parse($current->endDate)->subMonth();

        return (object) [
            'startDate' => $start->format('Y-m-d H:i:s'),
            'endDate' => $end->format('Y-m-d H:i:s'),
        ];
    }
}
// ====================================================================================================================================================================
// namespace App\Services;

// use Carbon\Carbon;

// class BillingPeriodCalculator
// {
//     public static function currentPeriod(int $billingDay, Carbon $today = null): object
//     {
//         if ($billingDay < 1 || $billingDay > 28) {
//             throw new \InvalidArgumentException('Invalid billing day');
//         }

//         if (!$today) {
//             $today = Carbon::today();
//         }

//         $billingDate = $today->copy()->day($billingDay);
//         $billingStartDate = $billingDate->copy()->addDay()->startOfDay();

//         $startDate = $billingStartDate->isFuture()
//             ? $today->copy()->subMonth()->day($billingDay + 1)->startOfDay()
//             : $billingStartDate;

//         $endDate = $billingStartDate->isFuture()
//             ? $billingDate->endOfDay()
//             : $today->copy()->addMonth()->day($billingDay)->endOfDay();

//         $gracePeriod = $startDate ? $startDate->copy()->subDay()->addDays(20)->endOfDay() : null;

//         return (object) [
//             'startDate' => $startDate ? $startDate->format('Y-m-d H:i:s') : null,
//             'endDate' => $endDate ? $endDate->format('Y-m-d H:i:s') : null,
//             'gracePeriod' => $gracePeriod ? $gracePeriod->format('Y-m-d H:i:s') : null,
//         ];
//     }

//     public static function previousPeriod(int $billingDay, Carbon $today = null): object
//     {
//         $current = self::currentPeriod($billingDay, $today);

//         if (!$current->startDate || !$current->endDate) {
//             return (object) [
//                 'startDate' => null,
//                 'endDate' => null,
//             ];
//         }

//         $start = Carbon::parse($current->startDate)->subMonth();
//         $end = Carbon::parse($current->endDate)->subMonth();

//         return (object) [
//             'startDate' => $start->format('Y-m-d H:i:s'),
//             'endDate' => $end->format('Y-m-d H:i:s'),
//         ];
//     }
// }

// ====================================================================================================================================================================
