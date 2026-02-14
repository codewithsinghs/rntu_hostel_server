<?php

namespace App\Services\Billing;
use Carbon\Carbon;
use App\Models\Subscription;

class BillingPeriodCalculator
{
    public function calculate(Subscription $subscription): array
    {
        $from = Carbon::parse(
            $subscription->last_billed_at ?? $subscription->start_date
        )->addDay();

        $maxTo = $from->copy()->addMonths(3)->subDay();

        if ($subscription->end_date) {
            $maxTo = min($maxTo, Carbon::parse($subscription->end_date));
        }

        return [
            'from' => $from,
            'to'   => $maxTo,
            'months' => $from->diffInMonths($maxTo) + 1,
        ];
    }
}
