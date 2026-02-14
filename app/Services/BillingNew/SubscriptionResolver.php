<?php

namespace App\Services\Billing;

use App\Models\Subscription;
use App\Enums\BillingStatus;
use Carbon\Carbon;

class SubscriptionResolver
{
    public function isBillable(Subscription $subscription): bool
    {
        // 1️⃣ Must be active
        if ($subscription->status !== BillingStatus::ACTIVE->value) {
            return false;
        }

        // 2️⃣ Next billing date must be within window
        if (!$subscription->next_billing_date) {
            return false;
        }

        if (Carbon::parse($subscription->next_billing_date)->gt(now()->addDays(7))) {
            return false;
        }

        // 3️⃣ Subscription end date (if exists)
        if ($subscription->end_date &&
            Carbon::parse($subscription->end_date)->lt(now())
        ) {
            return false;
        }

        // 4️⃣ Resident eligibility
        $resident = $subscription->resident;

        if (!$resident ||
            $resident->status !== 'active' ||
            $resident->checked_out_at
        ) {
            return false;
        }

        return true;
    }

    /**
     * Sync subscription with historical invoices (data mismatch healer)
     */
    public function syncFromInvoices(Subscription $subscription): void
    {
        $lastItem = $subscription->invoiceItems()
            ->orderByDesc('to_date')
            ->first();

        if (!$lastItem) {
            return;
        }

        $subscription->update([
            'last_billed_at'    => $lastItem->to_date,
            'next_billing_date'=> Carbon::parse($lastItem->to_date)->addDay(),
        ]);
    }
}
