<?php

namespace App\Services\Billing;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Resident;
use App\Models\InvoiceItem;
use App\Enums\BillingStatus;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionSyncService
{
    // public function run(bool $dryRun = true): void
    // {
    //     $created = 0;
    //     $updated = 0;
    //     $skipped = 0;

    //     Invoice::with(['items', 'resident'])
    //         ->orderBy('invoice_date')
    //         ->chunk(50, function ($invoices) use ($dryRun, &$created, &$updated, &$skipped,  &$noResident) {

    //             foreach ($invoices as $invoice) {
    //                 $residentId = $this->resolveResidentId($invoice);

    //                 // 🚫 No resident → skip completely
    //                 if (!$residentId) {
    //                     $noResident++;
    //                     continue;
    //                 }

    //                 foreach ($invoice->items as $item) {

    //                     if (!$this->isRecurringItem($item)) {
    //                         $skipped++;
    //                         continue;
    //                     }

    //                     $subscription = Subscription::where([
    //                         'resident_id'  => $invoice->resident_id,
    //                         'service_type' => $item->item_type,
    //                         'service_code' => $item->item_id,
    //                     ])->first();

    //                     if ($subscription) {
    //                         $this->updateSubscription($subscription, $item, $dryRun);
    //                         $updated++;
    //                     } else {
    //                         $this->createSubscription($invoice, $item, $dryRun);
    //                         $created++;
    //                     }
    //                 }
    //             }
    //         });

    //     dump(compact('created', 'updated', 'skipped', 'dryRun'));
    // }

    public function run(bool $dryRun = true): void
    {
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $noResident = 0;

        Invoice::with(['items'])
            ->orderBy('invoice_date')
            ->chunk(50, function ($invoices) use (
                $dryRun,
                &$created,
                &$updated,
                &$skipped,
                &$noResident
            ) {
                foreach ($invoices as $invoice) {

                    $residentId = $this->resolveResidentId($invoice);

                    // 🚫 No resident → skip completely
                    if (!$residentId) {
                        $noResident++;
                        continue;
                    }

                    foreach ($invoice->items as $item) {

                        if (!$this->isRecurringItem($item)) {
                            $skipped++;
                            continue;
                        }

                        $subscription = Subscription::where([
                            'resident_id'  => $residentId,
                            'service_type' => $item->item_type,
                            'service_code' => $item->item_id,
                        ])->first();

                        if ($subscription) {
                            $this->updateSubscription($subscription, $item, $dryRun);
                            $updated++;
                        } else {
                            $this->createSubscription($residentId, $invoice, $item, $dryRun);
                            $created++;
                        }
                    }
                }
            });

        dump(compact(
            'created',
            'updated',
            'skipped',
            'noResident',
            'dryRun'
        ));
    }


    /* ================= CREATE ================= */

    // private function createSubscription($invoice, $item, bool $dryRun): void
    // {
    //     $status = $this->resolveStatus($invoice, $item);

    //     $payload = [
    //         'resident_id'       => $invoice->resident_id,
    //         'invoice_id'        => $invoice->id,
    //         'invoice_item_id'   => $item->id,
    //         'service_type'      => $item->item_type,
    //         'service_code'      => $item->item_id,
    //         'service_name'      => $item->description,
    //         'unit_price'        => $item->price,
    //         'quantity'          => 1,
    //         'billing_type'      => 'recurring',
    //         'billing_cycle'     => 'monthly',
    //         'start_date'        => $item->from_date,
    //         'last_billed_at'    => $item->to_date,
    //         'next_billing_date' => Carbon::parse($item->to_date)->addDay(),
    //         'status'            => $status,
    //         // 'remarks'           => 'Bootstrapped from invoices',
    //         'remarks' => json_encode("Bootstrapped from invoices"), // ✅
    //     ];

    //     Log::info('[SUBSCRIPTION][CREATE]', $payload);

    //     if (!$dryRun) {
    //         Subscription::create($payload);
    //     }
    // }
    
    private function createSubscription(
        int $residentId,
        Invoice $invoice,
        InvoiceItem $item,
        bool $dryRun
    ): void {
        if ($dryRun) {
            Log::info('[DRY] Creating subscription', [
                'resident_id' => $residentId,
                'service' => $item->item_id,
            ]);
            return;
        }

        Subscription::create([
            'resident_id'       => $residentId,
            'invoice_id'        => $invoice->id,
            'invoice_item_id'   => $item->id,
            'service_type'      => $item->item_type,
            'service_code'      => $item->item_id,
            'service_name'      => $item->item_name,
            'unit_price'        => $item->amount,
            'quantity'          => $item->quantity ?? 1,
            'billing_type'      => 'recurring',
            'billing_cycle'     => $item->billing_cycle,
            'start_date'        => $invoice->invoice_date,
            'next_billing_date' => Carbon::parse($invoice->invoice_date)->addMonth(),
            'status'            => 'active',
            'last_billed_at'    => $invoice->invoice_date,
        ]);
    }


    /* ================= UPDATE ================= */

    private function updateSubscription(Subscription $sub, $item, bool $dryRun): void
    {
        if (Carbon::parse($item->to_date)->lte($sub->last_billed_at)) {
            return;
        }

        $update = [
            'invoice_id'        => $item->invoice_id,
            'invoice_item_id'   => $item->id,
            'last_billed_at'    => $item->to_date,
            'next_billing_date' => Carbon::parse($item->to_date)->addDay(),
        ];

        Log::info('[SUBSCRIPTION][UPDATE]', [
            'subscription_id' => $sub->id,
            'changes' => $update,
        ]);

        if (!$dryRun) {
            $sub->update($update);
        }
    }

    /* ================= STATUS LOGIC ================= */

    private function resolveStatus($invoice, $item): string
    {
        // Resident already checked out
        if ($invoice->resident && $invoice->resident->checked_out_at) {
            return BillingStatus::STOPPED->value;
        }

        // One-time fee → completed
        if ($item->item_type === 'fee') {
            $fee = DB::table('fees')->where('id', $item->item_id)->first();
            if ($fee) {
                $feeHead = DB::table('fee_heads')
                    ->where('id', $fee->fee_head_id)
                    ->first();

                if ($feeHead && $feeHead->is_one_time) {
                    return BillingStatus::COMPLETED->value;
                }
            }
        }

        return BillingStatus::ACTIVE->value;
    }

    /* ================= RECURRING DETECTION ================= */

    private function isRecurringItem($item): bool
    {
        if ($item->item_type === 'fee') {
            $fee = DB::table('fees')->where('id', $item->item_id)->first();
            if (!$fee) return false;

            $feeHead = DB::table('fee_heads')
                ->where('id', $fee->fee_head_id)
                ->first();

            return $feeHead && !$feeHead->is_one_time;
        }

        if ($item->item_type === 'accessory') {
            return true;
        }

        return false;
    }

    private function resolveResidentId(Invoice $invoice): ?int
    {
        // Case 1: Direct resident invoice
        if ($invoice->resident_id) {
            return $invoice->resident_id;
        }

        // Case 2: Guest invoice → guest converted to resident
        if ($invoice->guest_id) {
            return Resident::where('guest_id', $invoice->guest_id)->value('id');
        }

        return null;
    }
}
