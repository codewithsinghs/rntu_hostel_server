<?php

namespace App\Services\Billing;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Resident;
use App\Models\InvoiceItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ResidentSubscription;

class InvoiceSettlementService
{
    public static function settle(Invoice $invoice): void
    {
        if ($invoice->status !== 'paid') {
            return;
        }

        DB::transaction(function () use ($invoice) {

            foreach ($invoice->items as $item) {

                if (!$item->is_subscribed) continue;

                $sub = ResidentSubscription::where(
                    'service_code',
                    $item->item_id
                )->lockForUpdate()->first();

                if (!$sub) continue;

                if (
                    !$sub->last_billed_at ||
                    $sub->last_billed_at->lt($invoice->billing_upto)
                ) {
                    $sub->update([
                        'last_billed_at' => $invoice->billing_upto,
                        'status'         => 'active',
                    ]);
                }
            }
        });
    }
}
