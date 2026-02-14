<?php

namespace App\Services\Billing;

use App\Models\Invoice;
use App\Models\Subscription;
use App\Enums\BillingStatus;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SubscriptionSyncService
{
    // public function run(bool $dryRun = true): void
    // {
    //     Invoice::with('items')
    //         ->orderBy('invoice_date')
    //         ->chunk(50, function ($invoices) use ($dryRun) {

    //             foreach ($invoices as $invoice) {
    //                 foreach ($invoice->items as $item) {
    //                     $this->syncItem($invoice, $item, $dryRun);
    //                 }
    //             }

    //         });
    // }

    public function run(bool $dryRun = true): void
    {
        $invoiceCount = 0;
        $itemCount = 0;
        $processed = 0;

        Invoice::with('items')
            ->orderBy('invoice_date')
            ->chunk(50, function ($invoices) use ($dryRun, &$invoiceCount, &$itemCount, &$processed) {

                foreach ($invoices as $invoice) {
                    $invoiceCount++;

                    foreach ($invoice->items as $item) {
                        $itemCount++;

                        if (!$item->is_subscribed) {
                            continue;
                        }

                        $processed++;

                        $this->syncItem($invoice, $item, $dryRun);
                    }
                }
            });

        dump([
            'invoices_seen' => $invoiceCount,
            'items_seen' => $itemCount,
            'subscribed_items' => $processed,
            'dry_run' => $dryRun,
        ]);
    }


    private function syncItem($invoice, $item, bool $dryRun): void
    {
        // Only recurring items
        if (!$item->is_subscribed) {
            return;
        }

        $existing = Subscription::where([
            'resident_id'  => $invoice->resident_id,
            'service_type' => $item->item_type,
            'service_code' => $item->item_id,
        ])->first();

        if ($existing) {
            $this->updateExisting($existing, $item, $dryRun);
        } else {
            $this->createNew($invoice, $item, $dryRun);
        }
    }

    private function createNew($invoice, $item, bool $dryRun): void
    {
        $payload = [
            'resident_id'       => $invoice->resident_id,
            'invoice_id'        => $invoice->id,
            'invoice_item_id'   => $item->id,
            'service_type'      => $item->item_type,
            'service_code'      => $item->item_id,
            'service_name'      => $item->description,
            'unit_price'        => $item->price,
            'quantity'          => 1,
            'billing_type'      => 'recurring',
            'billing_cycle'     => 'monthly',
            'start_date'        => $item->from_date,
            'last_billed_at'    => $item->to_date,
            'next_billing_date' => Carbon::parse($item->to_date)->addDay(),
            'status'            => BillingStatus::ACTIVE->value,
            'remarks'           => 'Synced from invoice item',
        ];

        Log::info('[SYNC][CREATE]', $payload);

        if (!$dryRun) {
            Subscription::create($payload);
        }
    }

    private function updateExisting(Subscription $sub, $item, bool $dryRun): void
    {
        // Heal mismatch: advance billing dates if invoice is newer
        if (
            !$sub->last_billed_at ||
            Carbon::parse($item->to_date)->gt($sub->last_billed_at)
        ) {
            $update = [
                'invoice_id'        => $item->invoice_id,
                'invoice_item_id'   => $item->id,
                'last_billed_at'    => $item->to_date,
                'next_billing_date' => Carbon::parse($item->to_date)->addDay(),
            ];

            Log::info('[SYNC][UPDATE]', [
                'subscription_id' => $sub->id,
                'changes' => $update,
            ]);

            if (!$dryRun) {
                $sub->update($update);
            }
        }
    }
}
