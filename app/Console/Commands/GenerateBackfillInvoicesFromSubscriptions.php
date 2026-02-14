<?php

namespace App\Console\Commands;

use Carbon\Carbon;

use App\Models\Invoice;
use App\Models\Resident;
use App\Models\InvoiceItem;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ResidentSubscription;

class GenerateBackfillInvoicesFromSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:generate-backfill-invoices-from-subscriptions';
    protected $signature = 'billing:generate-backfill-invoices 
                            {--dry-run : Do not write anything to DB}';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Generate invoices from subscriptions (backfill + ongoing)';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $today  = now()->startOfDay();

        $this->info('🧾 Invoice generation started' . ($dryRun ? ' (DRY RUN)' : ''));

        Log::info('[INVOICE-GEN] Started', [
            'dry_run' => $dryRun,
            'date'    => $today->toDateString(),
        ]);

        $invoiceCount = 0;

        Resident::where('status', 'active')
            // ->where('id', 188)
            ->where(function ($q) use ($today) {
                $q->whereNull('check_out_date')
                    ->orWhere('check_out_date', '>', $today);
            })
            ->chunkById(50, function ($residents) use ($dryRun, &$invoiceCount) {

                foreach ($residents as $resident) {

                    Log::debug('[INVOICE-GEN] Processing resident', [
                        'resident_id' => $resident->id,
                    ]);

                    // 🔑 Load all subscriptions (backfilled already)
                    $subscriptions = Subscription::where('resident_id', $resident->id)
                        ->whereNull('deleted_at')
                        ->orderBy('start_date')
                        ->get()
                        ->groupBy(
                            fn($s) =>
                            $s->start_date->toDateString() . '|' . $s->end_date->toDateString()
                        );

                    if ($subscriptions->isEmpty()) {
                        Log::debug('[INVOICE-GEN] No subscriptions', [
                            'resident_id' => $resident->id,
                        ]);
                        continue;
                    }

                    foreach ($subscriptions as $periodKey => $subs) {

                        [$fromDate, $toDate] = explode('|', $periodKey);

                        Log::debug('[INVOICE-GEN] Period detected', [
                            'resident_id' => $resident->id,
                            'from'        => $fromDate,
                            'to'          => $toDate,
                            'items'       => count($subs),
                        ]);

                        $billableSubs = [];

                        // 🔍 ITEM-LEVEL DUPLICATE CHECK (SOURCE OF TRUTH)
                        foreach ($subs as $sub) {

                            $alreadyBilled = InvoiceItem::where('item_type', $sub->service_type)
                                ->where('item_id', $sub->invoice_item_id)
                                ->whereDate('from_date', $sub->start_date)
                                ->whereDate('to_date', $sub->end_date)
                                ->whereHas(
                                    'invoice',
                                    fn($q) =>
                                    $q->where('resident_id', $resident->id)
                                        ->whereNotIn('status', ['cancelled', 'failed'])
                                )
                                ->exists();

                            if ($alreadyBilled) {
                                Log::debug('[INVOICE-GEN] Item already billed', [
                                    'resident_id' => $resident->id,
                                    'service'     => $sub->service_name,
                                    'from'        => $sub->start_date->toDateString(),
                                    'to'          => $sub->end_date->toDateString(),
                                ]);
                                continue;
                            }

                            $billableSubs[] = $sub;
                        }

                        if (empty($billableSubs)) {
                            Log::debug('[INVOICE-GEN] Nothing to bill for period', [
                                'resident_id' => $resident->id,
                                'from'        => $fromDate,
                                'to'          => $toDate,
                            ]);
                            continue;
                        }

                        if ($dryRun) {
                            $this->line(
                                "DRY → Resident {$resident->id} | {$fromDate} → {$toDate} | Items: "
                                    . count($billableSubs)
                            );
                            continue;
                        }

                        DB::transaction(function () use (
                            $resident,
                            $billableSubs,
                            $fromDate,
                            $toDate,
                            &$invoiceCount
                        ) {

                            // 🧾 Create invoice header
                            $invoice = Invoice::create([
                                'resident_id'       => $resident->id,
                                'invoice_number'   => Invoice::generateInvoiceNumber('SUB'),
                                'invoice_date'      => $fromDate,
                                'due_date'          => $toDate,
                                'total_amount'      => 0,
                                'paid_amount'       => 0,
                                'remaining_amount' => 0,
                                'remarks' => 'Auto generated as per subscription',
                                'status'            => 'pending',
                            ]);

                            $total = 0;

                            foreach ($billableSubs as $sub) {

                                $amount = $sub->unit_price * $sub->quantity;

                                // $months = $sub->start_date
                                //     ->copy()
                                //     ->startOfMonth()
                                //     ->diffInMonths(
                                //         $sub->end_date->copy()->startOfMonth()
                                //     ) + 1;
                                $months = match ($sub->billing_cycle) {
                                    'monthly'   => 1,
                                    'quarterly' => 3,
                                    'halfyear'  => 6,
                                    'yearly'    => 12,
                                    default     => 1,
                                };

                                $totalAmount = $months * $sub->unit_price * $sub->quantity;

                                InvoiceItem::create([
                                    'invoice_id'      => $invoice->id,
                                    'item_type'    => $sub->service_type,
                                    'item_id' => $sub->invoice_item_id,
                                    'description'     => $sub->service_name,
                                    'price'      => $sub->unit_price,
                                    'quantity'        => $sub->quantity,
                                    'from_date'       => $sub->start_date,
                                    'to_date'         => $sub->end_date,
                                    'subscription_id' => $sub->id,
                                    // 'amount'          => $amount,
                                    'month'           => $months,
                                    'total_amount'    => $totalAmount,
                                ]);

                                $total += $totalAmount;
                            }

                            $invoice->update([
                                'total_amount'      => $total,
                                'remaining_amount' => $total,
                            ]);

                            // 🔄 Update only billed subscriptions
                            foreach ($billableSubs as $sub) {

                                $months = match ($sub->billing_cycle) {
                                    'monthly'   => 1,
                                    'quarterly' => 3,
                                    'halfyear'  => 6,
                                    'yearly'    => 12,
                                    default     => 3,
                                };

                                $sub->update([
                                    'invoice_id'        => $invoice->id,
                                    'last_billed_at'    => now(),
                                    'next_billing_date' => $sub->end_date,
                                    //     ->copy()
                                    //     ->addMonthsNoOverflow($months),
                                ]);
                            }

                            Log::info('[INVOICE-GEN] Invoice created', [
                                'invoice_id'  => $invoice->id,
                                'resident_id' => $resident->id,
                                'period'      => "{$fromDate} → {$toDate}",
                                'items'       => count($billableSubs),
                                'total'       => $total,
                            ]);

                            $invoiceCount++;
                        });
                    }
                }
            });

        $this->info("✅ Invoices generated: {$invoiceCount}");

        Log::info('[INVOICE-GEN] Completed', [
            'count' => $invoiceCount,
        ]);
    }


    // public function handle()
    // {
    //     $dryRun = $this->option('dry-run');
    //     $today  = now()->startOfDay();

    //     $this->info('🧾 Invoice generation started' . ($dryRun ? ' (DRY RUN)' : ''));

    //     Log::info('[INVOICE-GEN] Started', [
    //         'dry_run' => $dryRun,
    //         'date'    => $today->toDateString(),
    //     ]);

    //     $invoiceCount = 0;

    //     Resident::where('status', 'active')
    //         ->chunkById(50, function ($residents) use (&$invoiceCount, $dryRun) {

    //             foreach ($residents as $resident) {

    //                 Log::debug('[INVOICE-GEN] Resident', [
    //                     'resident_id' => $resident->id,
    //                 ]);

    //                 // 🔑 Get subscriptions NOT yet invoiced
    //                 $subscriptions = Subscription::where('resident_id', $resident->id)
    //                     ->whereNull('invoice_id')
    //                     ->whereNull('deleted_at')
    //                     ->orderBy('start_date')
    //                     ->get()
    //                     ->groupBy(fn ($s) =>
    //                         $s->start_date->toDateString() . '|' . $s->end_date->toDateString()
    //                     );

    //                 if ($subscriptions->isEmpty()) {
    //                     Log::debug('[INVOICE-GEN] No pending subscriptions', [
    //                         'resident_id' => $resident->id,
    //                     ]);
    //                     continue;
    //                 }

    //                 foreach ($subscriptions as $periodKey => $subs) {

    //                     [$startDate, $endDate] = explode('|', $periodKey);

    //                     Log::debug('[INVOICE-GEN] Processing period', [
    //                         'resident_id' => $resident->id,
    //                         'from'        => $startDate,
    //                         'to'          => $endDate,
    //                         'items'       => count($subs),
    //                     ]);

    //                     // 🛑 Prevent duplicate invoice
    //                     $invoiceExists = Invoice::where('resident_id', $resident->id)
    //                         ->whereDate('invoice_date', $startDate)
    //                         ->whereDate('due_date', $endDate)
    //                         ->whereNotIn('status', ['cancelled', 'failed'])
    //                         ->exists();

    //                     if ($invoiceExists) {
    //                         Log::warning('[INVOICE-GEN] Invoice already exists', [
    //                             'resident_id' => $resident->id,
    //                             'from'        => $startDate,
    //                             'to'          => $endDate,
    //                         ]);
    //                         continue;
    //                     }

    //                     if ($dryRun) {
    //                         $this->line("DRY → Invoice for Resident {$resident->id} ({$startDate} → {$endDate})");
    //                         continue;
    //                     }

    //                     DB::transaction(function () use (
    //                         $resident,
    //                         $subs,
    //                         $startDate,
    //                         $endDate,
    //                         &$invoiceCount
    //                     ) {

    //                         // 🧾 Create invoice
    //                         $invoice = Invoice::create([
    //                             'resident_id'       => $resident->id,
    //                             'invoice_date'      => $startDate,
    //                             'due_date'          => $endDate,
    //                             'total_amount'      => 0,
    //                             'paid_amount'       => 0,
    //                             'remaining_amount' => 0,
    //                             'status'            => 'pending',
    //                         ]);

    //                         $total = 0;

    //                         foreach ($subs as $sub) {

    //                             $amount = $sub->unit_price * $sub->quantity;

    //                             InvoiceItem::create([
    //                                 'invoice_id'      => $invoice->id,
    //                                 'item_type'       => $sub->service_type,
    //                                 'item_id'         => $sub->invoice_item_id,
    //                                 'description'     => $sub->service_name,
    //                                 'price'           => $sub->unit_price,
    //                                 'quantity'        => $sub->quantity,
    //                                 'amount'          => $amount,
    //                             ]);

    //                             $total += $amount;
    //                         }

    //                         $invoice->update([
    //                             'total_amount'      => $total,
    //                             'remaining_amount' => $total,
    //                         ]);

    //                         // 🔄 Update subscriptions
    //                         foreach ($subs as $sub) {

    //                             $months = match ($sub->billing_cycle) {
    //                                 'monthly'   => 1,
    //                                 'quarterly' => 3,
    //                                 'halfyear'  => 6,
    //                                 'yearly'    => 12,
    //                                 default     => 3,
    //                             };

    //                             $sub->update([
    //                                 'invoice_id'        => $invoice->id,
    //                                 'last_billed_at'    => now(),
    //                                 'next_billing_date' => $sub->end_date
    //                                     ->copy()
    //                                     ->addMonthsNoOverflow($months),
    //                             ]);
    //                         }

    //                         Log::info('[INVOICE-GEN] Invoice created', [
    //                             'invoice_id'  => $invoice->id,
    //                             'resident_id' => $resident->id,
    //                             'period'      => "{$startDate} → {$endDate}",
    //                             'total'       => $total,
    //                         ]);

    //                         $invoiceCount++;
    //                     });
    //                 }
    //             }
    //         });

    //     $this->info("✅ Invoices generated: {$invoiceCount}");

    //     Log::info('[INVOICE-GEN] Completed', [
    //         'count' => $invoiceCount,
    //     ]);
    // }
}
