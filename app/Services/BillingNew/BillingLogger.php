<?php

namespace App\Services\Billing;
use Illuminate\Support\Facades\Log;

class BillingLogger
{
    public static function skip($sub, $reason)
    {
        Log::info('[BILLING][SKIP]', [
            'subscription_id' => $sub->id,
            'resident_id' => $sub->resident_id,
            'reason' => $reason,
        ]);
    }
}
