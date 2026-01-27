<?php

namespace App\Services\Billing;

use App\Models\InvoiceItem;
use App\Models\ResidentSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BillingCoverageResolver
{
    public static function lastCoveredDate(ResidentSubscription $sub): Carbon
    {
        Log::debug('[BILLING] Resolving last covered date', [
            'subscription_id' => $sub->id,
        ]);

        // 1️⃣ Latest PAID invoice item
        $latestItem = InvoiceItem::where('item_type', $sub->service_type)
            ->where('item_id', $sub->invoice_item_id)
            ->whereHas('invoice', function ($q) {
                $q->whereIn('status', ['paid', 'success']);
            })
            ->orderByDesc('to_date')
            ->first();

        if ($latestItem && $latestItem->to_date) {
            Log::debug('[BILLING] Last covered from invoice item', [
                'to_date' => $latestItem->to_date->toDateString(),
            ]);

            return $latestItem->to_date->copy();
        }

        // 2️⃣ Fallback: subscription metadata
        if ($sub->last_billed_at) {
            Log::debug('[BILLING] Last covered from subscription.last_billed_at', [
                'to_date' => $sub->last_billed_at->toDateString(),
            ]);

            return $sub->last_billed_at->copy();
        }

        // 3️⃣ Absolute fallback: subscription end_date
        Log::debug('[BILLING] Last covered from subscription.end_date', [
            'to_date' => optional($sub->end_date)->toDateString(),
        ]);

        return $sub->end_date->copy();
    }
}
