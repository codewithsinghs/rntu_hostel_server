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

class BillingNightly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:generate-invoices-from-subscriptions';
    protected $signature = 'billing:backfill-subscriptions {--dry-run}';

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

    public function handle()
    {
        $today   = now()->startOfDay();
        $dryRun  = $this->option('dry-run');
        $created = 0;

        // Log::info('[BILLING-BACKFILL] Started', [
        //     'date' => $today->toDateString(),
        //     'dry_run' => $dryRun,
        // ]);

        Resident::where('status', 'active')
            ->where('id', 187)
            ->where(function ($q) use ($today) {
                $q->whereNull('check_out_date')
                    ->orWhere('check_out_date', '>', $today);
            })
            ->chunkById(50, function ($residents) use ($today, $dryRun, &$created) {

                foreach ($residents as $resident) {

                    Log::debug('[BACKFILL] Processing resident', [
                        'resident_id' => $resident->id
                    ]);

                    $subs = ResidentSubscription::where('resident_id', $resident->id)
                        // ->where('status', 'active')
                        ->whereIn('status', ['active', 'expired'])
                        ->whereNotNull('start_date')
                        ->get();

                    if ($subs->isEmpty()) {
                        Log::debug('[BACKFILL] No subscriptions', [
                            'resident_id' => $resident->id
                        ]);
                        continue;
                    }

                    $items = [];

                    foreach ($subs as $sub) {
                        $calc = SubscriptionBillCalculator::calculate($sub, $today);

                        // Log::info('calculated month', $calc);
                        // dd($calc);

                        if (empty($calc)) {
                            Log::debug('[BACKFILL] Nothing to bill', [
                                'subscription_id' => $sub->id
                            ]);
                            continue;
                        }

                        // 🔒 SIMPLE & SAFE DUPLICATE CHECK
                        // $alreadyExists = InvoiceItem::where('item_type', $sub->service_type)
                        //     ->where('item_id', $sub->invoice_item_id)
                        //     ->whereDate('from_date', $calc['from'])
                        //     ->whereDate('to_date', $calc['to'])
                        //     ->whereHas('invoice', function ($q) {
                        //         $q->whereIn('status', ['paid', 'partial', 'unpaid', 'pending']);
                        //     })
                        //     ->exists();

                        // $alreadyExists = InvoiceItem::where('item_type', $sub->service_type)
                        //     ->where('item_id', $sub->invoice_item_id)
                        //     ->whereDate('from_date', '<=', $calc['from'])
                        //     ->whereDate('to_date', '>=', $calc['from'])
                        //     ->whereHas('invoice', function ($q) {
                        //         $q->whereNotIn('status', ['cancelled', 'failed']);
                        //     })
                        //     ->exists();


                        // if ($alreadyExists) {
                        //     Log::info('[BACKFILL] Invoice item already exists, skipped', [
                        //         'subscription_id' => $sub->id,
                        //         'from' => $calc['from']->toDateString(),
                        //         'to'   => $calc['to']->toDateString(),
                        //     ]);
                        //     continue;
                        // }


                        // $coveredTill = InvoiceItem::where('item_type', $sub->service_type)
                        //     ->where('item_id', $sub->invoice_item_id)
                        //     ->whereHas('invoice', function ($q) {
                        //         $q->whereNotIn('status', ['cancelled', 'failed']);
                        //     })
                        //     ->max('to_date');

                        // if ($coveredTill) {
                        //     $coveredTill = Carbon::parse($coveredTill);

                        //     if ($coveredTill->gte($calc['to'])) {
                        //         Log::info('[BACKFILL] Subscription already fully billed', [
                        //             'subscription_id' => $sub->id,
                        //         ]);
                        //         continue;
                        //     }
                        // }



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

                    if (empty($items)) {
                        continue;
                    }

                    InvoiceBuilder::build($resident, $items, $dryRun);

                    Log::info('[BILLING-BACKFILL] Invoice created', [
                        'resident_id' => $resident->id,
                        'items' => count($items),
                    ]);

                    $created++;
                }
            });

        $this->info("Backfill invoices generated: {$created}");
    }
}
