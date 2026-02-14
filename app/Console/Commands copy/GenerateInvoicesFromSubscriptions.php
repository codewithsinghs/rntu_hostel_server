<?php

namespace App\Console\Commands;

use Carbon\Carbon;

use App\Models\Invoice;
use App\Models\Resident;
use App\Models\InvoiceItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ResidentSubscription;
use App\Services\Billing\InvoiceBuilder;
use App\Services\Billing\SubscriptionBillCalculator;
use App\Services\Billings\ResidentEligibilityService;
use App\Services\Billings\SubscriptionInvoiceAssembler;
use App\Services\Billings\SubscriptionEligibilityService;

class GenerateInvoicesFromSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:generate-invoices-from-subscriptions';
    protected $signature = 'billing:generate-invoices {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Generate invoices from expiring subscriptions';

    /**
     * Execute the console command.
     */

    // public function handle()
    // {
    //     $today = now()->startOfDay();
    //     $created = 0;

    //     Resident::where('status', 'active')
    //         ->where(function ($q) use ($today) {
    //             $q->whereNull('check_out_date')
    //                 ->orWhere('check_out_date', '>', $today);
    //         })
    //         ->chunk(50, function ($residents) use ($today, &$created) {

    //             foreach ($residents as $resident) {

    //                 DB::transaction(function () use ($resident, $today, &$created) {

    //                     $subscriptions = ResidentSubscription::where('resident_id', $resident->id)
    //                         ->where('status', 'active')
    //                         ->whereNotNull('end_date')
    //                         ->where(function ($q) {
    //                             $q->whereNull('last_billed_at')
    //                                 ->orWhereColumn('last_billed_at', '<', 'end_date');
    //                         })
    //                         ->orderBy('end_date')
    //                         ->get();

    //                     if ($subscriptions->isEmpty()) {
    //                         return;
    //                     }

    //                     $earliestExpiry = $subscriptions->first()->end_date;
    //                     $billingDate = $earliestExpiry->copy()->subDays(7);

    //                     if ($today->lt($billingDate)) {
    //                         return;
    //                     }

    //                     $mergeUntil = $earliestExpiry->copy()->addDays(3);

    //                     $billable = $subscriptions->filter(
    //                         fn($s) => $s->end_date->lte($mergeUntil)
    //                     );

    //                     if ($billable->isEmpty()) {
    //                         return;
    //                     }

    //                     // Prevent duplicate invoices
    //                     $alreadyBilled = Invoice::where('resident_id', $resident->id)
    //                         ->whereDate('invoice_date', $today)
    //                         ->exists();

    //                     if ($alreadyBilled) {
    //                         return;
    //                     }

    //                     $invoiceNumber = Invoice::generateInvoiceNumber('RA');

    //                     $invoice = Invoice::create([
    //                         'resident_id' => $resident->id,
    //                         'invoice_number' => $invoiceNumber,
    //                         'invoice_date' => $today,
    //                         'due_date'    => $earliestExpiry,
    //                         'status'      => 'unpaid',
    //                         'remarks' => 'system generated',
    //                     ]);

    //                     $total = 0;

    //                     foreach ($billable as $sub) {

    //                         $amount = $sub->unit_price * $sub->quantity;

    //                         InvoiceItem::create([
    //                             'invoice_id'   => $invoice->id,
    //                             'item_type' => 'accessory',
    //                             'item_id' => rand(10,99),
    //                             'description'  => $sub->service_name,
    //                             'price'   => $sub->unit_price,
    //                             // 'm'     => $sub->quantity,
    //                             'total_amount'       => $amount,
    //                         ]);

    //                         $sub->update([
    //                             'last_billed_at' => $today,
    //                         ]);

    //                         $total += $amount;
    //                     }

    //                     $invoice->update([
    //                         'total_amount' => $total,
    //                     ]);

    //                     $created++;
    //                 });
    //             }
    //         });

    //     $this->info("Invoices generated: {$created}");
    // }

    // public function handle()
    // {
    //     $today   = now()->startOfDay();
    //     $created = 0;

    //     Resident::where('status', 'active')
    //         ->where(function ($q) use ($today) {
    //             $q->whereNull('check_out_date')
    //                 ->orWhere('check_out_date', '>', $today);
    //         })
    //         ->chunkById(50, function ($residents) use ($today, &$created) {

    //             foreach ($residents as $resident) {

    //                 DB::transaction(function () use ($resident, $today, &$created) {

    //                     $subscriptions = ResidentSubscription::where('resident_id', $resident->id)
    //                         ->where('status', 'active')
    //                         ->whereNotNull('end_date')
    //                         ->where(function ($q) {
    //                             $q->whereNull('last_billed_at')
    //                                 ->orWhereColumn('last_billed_at', '<', 'end_date');
    //                         })
    //                         ->orderBy('end_date')
    //                         ->get();

    //                     if ($subscriptions->isEmpty()) {
    //                         return;
    //                     }

    //                     $earliestExpiry = Carbon::parse($subscriptions->first()->end_date);
    //                     $billingTrigger = $earliestExpiry->copy()->subDays(7);

    //                     if ($today->lt($billingTrigger)) {
    //                         return;
    //                     }

    //                     $mergeUntil = $earliestExpiry->copy()->addDays(3);

    //                     $billableSubs = $subscriptions->filter(
    //                         fn($s) => Carbon::parse($s->end_date)->lte($mergeUntil)
    //                     );

    //                     if ($billableSubs->isEmpty()) {
    //                         return;
    //                     }

    //                     // Prevent duplicate invoice for same cycle
    //                     $alreadyBilled = Invoice::where('resident_id', $resident->id)
    //                         ->whereDate('invoice_date', $today)
    //                         ->exists();

    //                     if ($alreadyBilled) {
    //                         return;
    //                     }

    //                     $invoice = Invoice::create([
    //                         'resident_id'      => $resident->id,
    //                         'invoice_number'   => Invoice::generateInvoiceNumber('RA'),
    //                         'invoice_date'     => $today,
    //                         'due_date'         => $today->copy()->addDays(7),
    //                         'total_amount'     => 0,
    //                         'paid_amount'      => 0,
    //                         'remaining_amount' => 0,
    //                         'status'           => 'unpaid',
    //                         'remarks'          => 'Auto generated (subscription renewal)',
    //                     ]);

    //                     $grandTotal = 0;

    //                     foreach ($billableSubs as $sub) {

    //                         $from = $sub->last_billed_at
    //                             ? Carbon::parse($sub->last_billed_at)->addDay()
    //                             : Carbon::parse($sub->start_date);

    //                         $to = Carbon::parse($sub->end_date);

    //                         if ($from->gt($to)) {
    //                             continue;
    //                         }

    //                         $days   = $from->diffInDays($to) + 1;
    //                         $months = round($days / 30, 2);

    //                         $amount = round($months * $sub->unit_price, 2);

    //                         InvoiceItem::create([
    //                             'invoice_id'    => $invoice->id,
    //                             'item_type'     => 'subscription',
    //                             'item_id'       => $sub->service_code,
    //                             'description'   => $sub->service_name,
    //                             'price'         => $sub->unit_price,
    //                             'from_date'     => $from,
    //                             'to_date'       => $to,
    //                             'is_subscribed' => 1,
    //                             'month'         => $months,
    //                             'total_amount'  => $amount,
    //                         ]);

    //                         $sub->update([
    //                             'last_billed_at' => $to,
    //                         ]);

    //                         $grandTotal += $amount;
    //                     }

    //                     $invoice->update([
    //                         'total_amount'     => $grandTotal,
    //                         'remaining_amount' => $grandTotal,
    //                     ]);

    //                     $created++;
    //                 });
    //             }
    //         });

    //     $this->info("Invoices generated: {$created}");
    // }

    // Working Perfectly with Billing
    // public function handle()
    // {
    //     $today   = now()->startOfDay();
    //     $dryRun  = $this->option('dry-run');
    //     $created = 0;

    //     Log::info('[BILLING] Nightly subscription billing started');

    //     Resident::where('status', 'active')
    //         ->where(function ($q) use ($today) {
    //             $q->whereNull('check_out_date')
    //                 ->orWhere('check_out_date', '>', $today);
    //         })
    //         ->chunkById(50, function ($residents) use ($today, $dryRun, &$created) {

    //             foreach ($residents as $resident) {

    //                 $subs = ResidentSubscription::where('resident_id', $resident->id)
    //                     ->where('status', 'active')
    //                     ->whereNotNull('end_date')
    //                     ->get();

    //                 if ($subs->isEmpty()) {
    //                     continue;
    //                 }

    //                 $earliestExpiry = $subs->min('end_date')->subDays(7);

    //                 if ($today->lt($earliestExpiry)) {
    //                     continue;
    //                 }

    //                 $items = [];

    //                 foreach ($subs as $sub) {
    //                     $calc = SubscriptionBillCalculator::calculate($sub);

    //                     if (empty($calc)) {
    //                         continue;
    //                     }

    //                     $items[] = [
    //                         'item_type'     => 'subscription',
    //                         'item_id'       => $sub->service_code,
    //                         'description'   => $sub->service_name,
    //                         'price'         => $sub->unit_price,
    //                         'from_date'     => $calc['from'],
    //                         'to_date'       => $calc['to'],
    //                         'is_subscribed' => 1,
    //                         'month'         => $calc['months'],
    //                         'total_amount'  => $calc['amount'],
    //                     ];

    //                     if (!$dryRun) {
    //                         $sub->update(['last_billed_at' => $calc['to']]);
    //                     }
    //                 }

    //                 if (empty($items)) {
    //                     continue;
    //                 }

    //                 $result = InvoiceBuilder::build($resident, $items, $dryRun);

    //                 Log::info('[BILLING] Invoice generated', [
    //                     'resident_id' => $resident->id,
    //                     'dry_run'     => $dryRun,
    //                     'items'       => count($items),
    //                 ]);

    //                 $created++;
    //             }
    //         });

    //     $this->info("Invoices generated: {$created}");
    // }

    public function handle()
    {
        $today  = now()->startOfDay();
        $dryRun = $this->option('dry-run');
        $count  = 0;

        Log::info('[BILLING] Nightly billing started', [
            'date' => $today->toDateString(),
            'dry_run' => $dryRun
        ]);

        Resident::chunkById(50, function ($residents) use ($today, $dryRun, &$count) {

            foreach ($residents as $resident) {

                if (!ResidentEligibilityService::isBillable($resident, $today)) {
                    continue;
                }

                $subs = ResidentSubscription::where('resident_id', $resident->id)
                    ->where('status', 'active')
                    ->whereNotNull('end_date')
                    ->get();

                if (!SubscriptionEligibilityService::isBillingDue($subs, $today)) {
                    continue;
                }

                // $items = SubscriptionInvoiceAssembler::buildItems($subs, $dryRun);
                $items = SubscriptionInvoiceAssembler::buildItems($subs, $today, $dryRun);

                if (empty($items)) {
                    continue;
                }

                InvoiceBuilder::build($resident, $items, $dryRun);

                Log::info('[BILLING] Invoice created', [
                    'resident_id' => $resident->id,
                    'items' => count($items),
                ]);

                $count++;
            }
        });

        $this->info("Invoices generated: {$count}");
    }
}
