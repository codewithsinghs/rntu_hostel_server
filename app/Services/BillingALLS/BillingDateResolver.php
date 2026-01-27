<?php

namespace App\Services\Billing;

use Carbon\Carbon;

class BillingDateResolver
{
    /**
     * Counts FULL billable months between two dates
     */
    public static function countMonthlyCharges(Carbon $from, Carbon $to): int
    {

        $from = $from->copy()->startOfDay();
        $to   = $to->copy()->startOfDay();

        if ($from->gt($to)) {
            return 0;
        }

        $months = 0;
        $cursor = $from->copy();

        while (true) {
            $periodEnd = $cursor->copy()->addMonth()->subDay();

            if ($periodEnd->gt($to)) {
                break;
            }

            $months++;
            $cursor->addMonth();
        }

        return $months;
    }
}
