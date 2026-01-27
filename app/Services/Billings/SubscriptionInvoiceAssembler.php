<?php

namespace App\Services\Billings;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\Billing\SubscriptionBillCalculator;

class SubscriptionInvoiceAssembler
{
    // public static function buildItems($subs, $dryRun = false): array
    // {
    //     $items = [];

    //     foreach ($subs as $sub) {
    //         $calc = SubscriptionBillCalculator::calculate($sub);

    //         Log::debug('[BILLING] Subscription calc', [
    //             'sub_id' => $sub->id,
    //             'months' => $calc['months'] ?? 0,
    //         ]);

    //         if (empty($calc) || $calc['months'] <= 0) {
    //             continue;
    //         }

    //         $items[] = [
    //             'item_type'     => 'subscription',
    //             'item_id'       => $sub->service_code,
    //             'description'   => $sub->service_name,
    //             'price'         => $sub->unit_price,
    //             'from_date'     => $calc['from'],
    //             'to_date'       => $calc['to'],
    //             'is_subscribed' => 1,
    //             'month'         => $calc['months'],
    //             'total_amount'  => $calc['amount'],
    //         ];

    //         if (!$dryRun) {
    //             $sub->update(['last_billed_at' => $calc['to']]);
    //         }
    //     }

    //     return $items;
    // }

    public static function buildItems($subs, Carbon $today, bool $dryRun = false): array
    {
        $items = [];

        foreach ($subs as $sub) {

            $calc = SubscriptionBillCalculator::calculate($sub, $today);

            // Log::debug('[BILLING] Subscription calc', [
            //     'sub_id' => $sub->id,
            //     'from'   => $calc['from'] ?? null,
            //     'to'     => $calc['to'] ?? null,
            //     'months' => $calc['months'] ?? 0,
            // ]);

            if (empty($calc) || $calc['months'] <= 0) {
                continue;
            }

            $items[] = [
                'item_type'     => $sub->service_type,
                'item_id'       => $sub->invoice_item_id,
                'description'   => $sub->service_name,
                'price'         => $sub->unit_price,
                'from_date'     => $calc['from'],
                'to_date'       => $calc['to'],
                'is_subscribed' => 1,
                'month'         => $calc['months'],
                'total_amount'  => $calc['amount'],
            ];

            if (!$dryRun) {
                $sub->update(['last_billed_at' => $calc['to']]);
            }
        }

        return $items;
    }
}
