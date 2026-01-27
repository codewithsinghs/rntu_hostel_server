<?php

namespace App\Services\Billing;

use Carbon\Carbon;

class BillingDateResolver
{
    public static function countFullMonths(Carbon $from, Carbon $to): int
    {
        $start = $from->copy()->startOfMonth();
        $end   = $to->copy()->startOfMonth();

        return $start->diffInMonths($end) + 1;
    }

    public static function countMonthlyCycles(Carbon $from, Carbon $to): int
    {
        if ($from->gt($to)) {
            return 0;
        }

        // Move to next cycle boundary
        $cycleStart = $from->copy()->addMonthNoOverflow()->startOfDay();
        $months = 0;

        while ($cycleStart->lte($to)) {
            $months++;
            $cycleStart->addMonthNoOverflow();
        }

        return $months;
    }

     /**
     * Count billable months based on month-boundary crossings
     * Hostel-style monthly billing
     */
    // public static function countMonthlyCharges(Carbon $from, Carbon $to): int
    // {
    //     if ($from->gt($to)) {
    //         return 0;
    //     }

    //     $months = 0;
    //     $cursor = $from->copy();

    //     while (true) {
    //         $next = $cursor->copy()->addMonthNoOverflow();

    //         if ($next->gt($to)) {
    //             break;
    //         }

    //         $months++;
    //         $cursor = $next;
    //     }

    //     return $months;
    // }

     public static function countMonthlyCharges(Carbon $from, Carbon $to): int
    {
        if ($from->gt($to)) {
            return 0;
        }

        $months = 0;

        // Start from FIRST DAY of the NEXT MONTH
        $cursor = $from->copy()->startOfMonth()->addMonth();

        while ($cursor->lte($to)) {
            $months++;
            $cursor->addMonth();
        }

        return $months;
    }
}
