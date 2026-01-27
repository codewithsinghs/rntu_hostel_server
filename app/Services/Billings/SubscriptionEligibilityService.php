<?php

namespace App\Services\Billings;

use Illuminate\Support\Facades\Log;

class SubscriptionEligibilityService
{
    public static function isBillingDue($subscriptions, $today): bool
    {
        if ($subscriptions->isEmpty()) {
            return false;
        }

        $earliest = $subscriptions->min('end_date')->copy()->subDays(7);

        Log::debug('[BILLING] Subscription expiry window', [
            'earliest_expiry_minus_7' => $earliest->toDateString(),
            'today' => $today->toDateString(),
        ]);

        return $today->gte($earliest);
    }
}
