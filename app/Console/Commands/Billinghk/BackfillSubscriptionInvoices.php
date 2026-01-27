<?php

namespace App\Console\Commands\Billing;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ResidentSubscription;
use App\Support\InvoiceNumberGenerator;
use App\Services\Billing\SubscriptionBillCalculator;

class BackfillSubscriptionInvoices extends Command
{
    protected $signature = 'billings:backfill {--dry-run}';
    protected $description = 'Backfill subscription invoices safely (dry-run supported)';

    // public function handle()
    // {
    //     $today  = Carbon::today();
    //     $dryRun = $this->option('dry-run');

    //     $subs = ResidentSubscription::query()
    //         ->whereIn('status', ['active', 'expired'])
    //         ->whereNotNull('billing_type')
    //         ->get();

    //     $this->info("Found {$subs->count()} subscriptions");

    //     foreach ($subs as $sub) {

    //         DB::transaction(function () use ($sub, $today, $dryRun) {

    //             /* ---------------------------------
    //              | STEP 1: Determine last billed date
    //              |---------------------------------*/
    //             // $lastItemTo = InvoiceItem::query()
    //             $lastItemTo = InvoiceItem::whereHas('invoice', function ($q) use ($sub) {
    //                 $q->where('resident_id', $sub->resident_id);
    //             })
    //                 ->whereIn('item_type', ['fee', 'accessory'])
    //                 ->where('item_id', $sub->invoice_item_id)
    //                 // ->where('resident_id', $sub->resident_id)
    //                 ->max('to_date');

    //             $billingFrom = collect([
    //                 $sub->start_date,
    //                 $sub->last_billed_at ? Carbon::parse($sub->last_billed_at)->addDay() : null,
    //                 $sub->next_billing_date,
    //                 $lastItemTo ? Carbon::parse($lastItemTo)->addDay() : null,
    //             ])->filter()->max();

    //             $billingTo = min(
    //                 Carbon::parse($sub->end_date ?? $today),
    //                 $today
    //             );

    //             if (!$billingFrom || $billingFrom->gt($billingTo)) {
    //                 return;
    //             }

    //             /* ---------------------------------
    //              | STEP 2: Calculate amount
    //              |---------------------------------*/
    //             // $calc = SubscriptionBillCalculator::calculate(
    //             //     $sub,
    //             //     $billingFrom,
    //             //     $billingTo
    //             // );
    //             $calc = SubscriptionBillCalculator::calculate(
    //                 $sub,
    //                 $sub->start_date,
    //                 $sub->end_date
    //             );

    //             Log::info('calculated fdaafd', $calc);

    //             // Nightly cron
    //             // $calc = SubscriptionBillCalculator::calculate(
    //             //     $sub,
    //             //     $sub->last_billed_at
    //             //         ? $sub->last_billed_at->copy()->addDay()
    //             //         : $sub->start_date,
    //             //     now()->startOfDay()
    //             // );
    //             // 🔹 Partial / admin invoice
    //             // $calc = SubscriptionBillCalculator::calculate(
    //             //     $sub,
    //             //     $billingFrom,
    //             //     $billingTo
    //             // );


    //             if (empty($calc)) {
    //                 Log::debug('[BACKFILL] No billable amount', [
    //                     'subscription_id' => $sub->id,
    //                 ]);
    //                 return;
    //             }

    //             if (($calc['total_amount'] ?? 0) <= 0) {
    //                 return;
    //             }

    //             if ($dryRun) {
    //                 $this->line("[DRY-RUN] Resident {$sub->resident_id} | {$billingFrom->toDateString()} → {$billingTo->toDateString()} | ₹{$calc['total_amount']}");
    //                 return;
    //             }

    //             /* ---------------------------------
    //              | STEP 3: Create invoice
    //              |---------------------------------*/
    //             $invoice = Invoice::create([
    //                 'resident_id'       => $sub->resident_id,
    //                 'invoice_number'    => InvoiceNumberGenerator::generate('SUB'),
    //                 'invoice_date'      => now(),
    //                 'due_date'          => now()->addDays(7),
    //                 'total_amount'      => $calc['total_amount'],
    //                 'paid_amount'       => 0,
    //                 'remaining_amount' => $calc['total_amount'],
    //                 'status'            => 'unpaid',
    //                 'remarks'           => 'Subscription backfill',
    //             ]);

    //             /* ---------------------------------
    //              | STEP 4: Create invoice item
    //              |---------------------------------*/
    //             $item = InvoiceItem::create([
    //                 'invoice_id'   => $invoice->id,
    //                 'resident_id'  => $sub->resident_id,
    //                 'item_type'    => $sub->service_type,
    //                 'item_id'      => $sub->invoice_item_id,
    //                 'description'  => $sub->service_name,
    //                 'price'        => $sub->unit_price,
    //                 'from_date'    => $billingFrom,
    //                 'to_date'      => $billingTo,
    //                 'month'        => $calc['months'],
    //                 'total_amount' => $calc['total_amount'],
    //                 'is_subscribed' => 1,
    //             ]);

    //             /* ---------------------------------
    //              | STEP 5: Update subscription safely
    //              |---------------------------------*/
    //             $sub->update([
    //                 'invoice_id'      => $invoice->id,
    //                 'invoice_item_id' => $item->id,
    //                 'last_billed_at' => $billingTo,
    //                 'next_billing_date' => $billingTo->copy()->addDay(),
    //             ]);
    //         });
    //     }

    //     $this->info(
    //         $dryRun
    //             ? 'Dry-run completed (no DB writes)'
    //             : 'Subscription backfill completed successfully'
    //     );
    // }

    // public function handle()
    // {
    //     $today  = Carbon::today();
    //     $dryRun = $this->option('dry-run');

    //     $subs = ResidentSubscription::query()
    //         ->whereIn('status', ['active', 'expired'])
    //         ->whereNotNull('billing_type')
    //         ->get()
    //         ->groupBy('resident_id');

    //     $this->info("Processing {$subs->count()} residents");

    //     foreach ($subs as $residentId => $residentSubs) {

    //         DB::transaction(function () use ($residentId, $residentSubs, $today, $dryRun) {

    //             $invoiceTotal = 0;
    //             $items = [];

    //             foreach ($residentSubs as $sub) {

    //                 /* -----------------------------
    //              | Determine billing range
    //              |-----------------------------*/
    //                 $lastItemTo = InvoiceItem::whereHas('invoice', function ($q) use ($residentId) {
    //                     $q->where('resident_id', $residentId);
    //                 })
    //                     ->where('item_id', $sub->invoice_item_id)
    //                     ->max('to_date');

    //                 $billingFrom = collect([
    //                     $sub->start_date,
    //                     $sub->last_billed_at ? Carbon::parse($sub->last_billed_at)->addDay() : null,
    //                     $sub->next_billing_date,
    //                     $lastItemTo ? Carbon::parse($lastItemTo)->addDay() : null,
    //                 ])->filter()->max();

    //                 $billingTo = min(
    //                     Carbon::parse($sub->end_date ?? $today),
    //                     $today
    //                 );

    //                 if (!$billingFrom || $billingFrom->gt($billingTo)) {
    //                     $calc = SubscriptionBillCalculator::calculate($sub);
    //                 } else {
    //                     $calc = SubscriptionBillCalculator::calculate(
    //                         $sub,
    //                         $billingFrom,
    //                         $billingTo,
    //                         true
    //                     );
    //                 }

    //                 $invoiceTotal += $calc['total_amount'];

    //                 $items[] = [
    //                     'sub'  => $sub,
    //                     'calc' => $calc,
    //                 ];
    //             }

    //             // 🔹 Create invoice EVEN if total = 0
    //             if ($dryRun) {
    //                 $this->line("[DRY-RUN] Resident {$residentId} | Items: " . count($items) . " | Total ₹{$invoiceTotal}");
    //                 return;
    //             }

    //             /* -----------------------------
    //          | CREATE INVOICE
    //          |-----------------------------*/
    //             $invoice = Invoice::create([
    //                 'resident_id'       => $residentId,
    //                 'invoice_number'    => InvoiceNumberGenerator::generate('SUB'),
    //                 'invoice_date'      => now(),
    //                 'due_date'          => now()->addDays(7),
    //                 'total_amount'      => $invoiceTotal,
    //                 'paid_amount'       => 0,
    //                 'remaining_amount' => $invoiceTotal,
    //                 'status'            => $invoiceTotal > 0 ? 'unpaid' : 'paid',
    //                 'remarks'           => 'Subscription billing',
    //             ]);

    //             /* -----------------------------
    //          | CREATE ITEMS (INCLUDING ₹0)
    //          |-----------------------------*/
    //             foreach ($items as $row) {

    //                 $sub  = $row['sub'];
    //                 $calc = $row['calc'];

    //                 $item = InvoiceItem::create([
    //                     'invoice_id'    => $invoice->id,
    //                     'resident_id'   => $residentId,
    //                     'item_type'     => $sub->service_type,
    //                     'item_id'       => $sub->invoice_item_id,
    //                     'description'   => $sub->service_name,
    //                     'price'         => $calc['unit_price'],
    //                     'from_date'     => $calc['from'],
    //                     'to_date'       => $calc['to'],
    //                     'month'         => $calc['months'],
    //                     'total_amount' => $calc['total_amount'],
    //                     'is_subscribed' => 1,
    //                 ]);

    //                 $sub->update([
    //                     'invoice_id'        => $invoice->id,
    //                     'invoice_item_id'   => $item->id,
    //                     'last_billed_at'    => $calc['to'] ?? $sub->last_billed_at,
    //                     'next_billing_date' => $calc['to']
    //                         ? $calc['to']->copy()->addDay()
    //                         : $sub->next_billing_date,
    //                 ]);
    //             }
    //         });
    //     }

    //     $this->info(
    //         $dryRun
    //             ? 'Dry-run completed successfully'
    //             : 'Subscription billing completed successfully'
    //     );
    // }

    public function handle()
    {
        $today  = Carbon::today();
        $dryRun = $this->option('dry-run');

        $subs = ResidentSubscription::query()
            ->whereIn('status', ['active', 'expired'])
            ->whereNotNull('billing_type')
            ->get()
            ->groupBy('resident_id');

        $this->info("Processing {$subs->count()} residents");

        foreach ($subs as $residentId => $residentSubs) {

            DB::transaction(function () use ($residentId, $residentSubs, $today, $dryRun) {

                $invoiceTotal = 0;
                $items = [];

                foreach ($residentSubs as $sub) {

                    $billingFrom = collect([
                        $sub->start_date,
                        $sub->last_billed_at ? Carbon::parse($sub->last_billed_at)->addDay() : null,
                        $sub->next_billing_date,
                    ])->filter()->max();

                    // $billingTo = $billingTo = Carbon::parse($sub->end_date);
                    // $billingTo = Carbon::parse($sub->end_date)
                    //     ->endOfDay();   // 🔥 NEVER today
                    $billingTo = min(
                            Carbon::parse($sub->end_date ?? $today),
                            $today
                        );


                    // if ($backfill) {
                    //     $billingTo = Carbon::parse($sub->end_date);
                    // } else {
                    //     $billingTo = min(
                    //         Carbon::parse($sub->end_date ?? $today),
                    //         $today
                    //     );
                    // }


                    if (!$billingFrom || $billingFrom->gt($billingTo)) {
                        continue;
                    }

                    $calc = SubscriptionBillCalculator::calculate($sub, $billingFrom, $billingTo, true);

                    if (empty($calc)) {
                        Log::info('[BILLING] Nothing to bill', [
                            'sub_id' => $sub->id,
                            'resident_id' => $sub->resident_id,
                            'billingFrom' => $billingFrom,
                            'billingTo' => $billingTo,
                        ]);
                        continue;
                    }

                    // Validate calc structure
                    if (!isset($calc['from'], $calc['to'], $calc['months'], $calc['total_amount'])) {
                        Log::error('[BILLING] Invalid calculate result', [
                            'sub_id' => $sub->id,
                            'calc' => $calc
                        ]);
                        continue;
                    }

                    // prevent duplicate invoice items
                    $exists = InvoiceItem::whereHas('invoice', function ($q) use ($residentId) {
                        $q->where('resident_id', $residentId);
                    })
                        // InvoiceItem::where('resident_id', $residentId)
                        ->where('item_id', $sub->invoice_item_id)
                        ->where('from_date', $calc['from'])
                        ->where('to_date', $calc['to'])
                        ->exists();

                    if ($exists) {
                        continue; // already billed
                    }

                    $invoiceTotal += $calc['total_amount'];

                    $items[] = [
                        'sub'  => $sub,
                        'calc' => $calc,
                    ];
                }

                // if no item is billable
                if (count($items) === 0) {
                    return;
                }

                if ($dryRun) {
                    $this->line("[DRY-RUN] Resident {$residentId} | Items: " . count($items) . " | Total ₹{$invoiceTotal}");
                    return;
                }

                // create invoice
                $invoice = Invoice::create([
                    'resident_id'       => $residentId,
                    'invoice_number'    => InvoiceNumberGenerator::generate('SUB'),
                    'invoice_date'      => now(),
                    'due_date'          => now()->addDays(7),
                    'total_amount'      => $invoiceTotal,
                    'paid_amount'       => 0,
                    'remaining_amount' => $invoiceTotal,
                    'status'            => $invoiceTotal > 0 ? 'unpaid' : 'paid',
                    'remarks'           => 'Subscription billing',
                ]);

                // create items
                foreach ($items as $row) {

                    $sub  = $row['sub'];
                    $calc = $row['calc'];

                    // -------------------------
                    // VALIDATION: Ensure calc is valid
                    // -------------------------
                    if (empty($calc) || !isset($calc['from'], $calc['to'], $calc['months'], $calc['total_amount'])) {
                        Log::warning("[BILLING] Invalid calc result", [
                            'sub_id' => $sub->id,
                            'calc'   => $calc
                        ]);
                        continue;
                    }

                    // -------------------------
                    // FIX: Ensure from/to are Carbon
                    // -------------------------
                    $from = $calc['from'] instanceof Carbon ? $calc['from'] : Carbon::parse($calc['from']);
                    $to   = $calc['to'] instanceof Carbon ? $calc['to'] : Carbon::parse($calc['to']);


                    // create item
                    $item = InvoiceItem::create([
                        'invoice_id'    => $invoice->id,
                        'item_type'     => $sub->service_type,
                        'item_id'       => $sub->invoice_item_id,
                        'description'   => $sub->service_name,
                        'price'         => $sub->unit_price,
                        'from_date'     => $from,
                        'to_date'       => $to,
                        'month'         => $calc['months'],
                        'total_amount'  => $calc['total_amount'],
                        'is_subscribed' => 1,
                    ]);



                    // update subscription safely
                    $sub->update([
                        // 'invoice_id'        => $invoice->id,
                        // 'invoice_item_id'   => $item->id,
                        'last_billed_at'    => $to,
                        'next_billing_date' => $to->copy()->addDay(),
                    ]);
                }
            });
        }

        $this->info(
            $dryRun
                ? 'Dry-run completed successfully'
                : 'Subscription billing completed successfully'
        );
    }
}
