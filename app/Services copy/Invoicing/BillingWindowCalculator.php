<?php

namespace App\Services\Billing;

use App\Models\ResidentSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BillingWindowCalculator
{
    public static function calculate(
        ResidentSubscription $sub,
        Carbon $lastCovered
    ): ?array {

        // Ignore one-time billing
        if ($sub->billing_type === 'one_time') {
            Log::info('[BILLING] Skipped one-time billing item', [
                'subscription_id' => $sub->id,
            ]);
            return null;
        }

        // FROM = next day after last coverage
        $from = $lastCovered->copy()->addDay();

        // Billing cycle
        $months = match ($sub->billing_cycle) {
            'monthly'   => 1,
            'quarterly' => 3,
            default     => 1,
        };

        // TO = next cycle
        $to = $from->copy()
            ->addMonthsNoOverflow($months)
            ->subDay();

        // Safety
        if ($from->gt($to)) {
            Log::debug('[BILLING] Invalid billing window (from > to)', [
                'from' => $from->toDateString(),
                'to'   => $to->toDateString(),
            ]);
            return null;
        }

        Log::info('[BILLING] Billing window calculated', [
            'subscription_id' => $sub->id,
            'from' => $from->toDateString(),
            'to'   => $to->toDateString(),
            'months' => $months,
        ]);

        return compact('from', 'to', 'months');
    }
}
