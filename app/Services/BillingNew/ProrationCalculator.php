<?php

namespace App\Services\Billing;
use App\Models\Subscription;

class ProrationCalculator
{
    public function calculate(Subscription $sub, array $period): float
    {
        $daysInMonth = $period['from']->daysInMonth;

        if ($period['from']->day !== 1 || $period['to']->day !== $daysInMonth) {
            $usedDays = $period['from']->diffInDays($period['to']) + 1;
            return round(
                ($sub->unit_price / $daysInMonth) * $usedDays,
                2
            );
        }

        return $sub->unit_price * $period['months'];
    }
}
