<?php

namespace App\Services\Billing;

use App\Models\InvoiceItem;
use App\Models\ResidentSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class InvoiceDuplicateGuard
{
    public static function exists(
        ResidentSubscription $sub,
        Carbon $from,
        Carbon $to
    ): bool {

        $exists = InvoiceItem::where('item_type', $sub->service_type)
            ->where('item_id', $sub->invoice_item_id)
            ->where('from_date', $from)
            ->where('to_date', $to)
            ->whereHas('invoice', function ($q) {
                $q->whereNotIn('status', ['cancelled', 'failed']);
            })
            ->exists();

        if ($exists) {
            Log::warning('[BILLING] Duplicate invoice prevented', [
                'subscription_id' => $sub->id,
                'from' => $from->toDateString(),
                'to'   => $to->toDateString(),
            ]);
        }

        return $exists;
    }
}
