<?php

namespace App\Console\Commands\Billing;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Resident;
use App\Models\InvoiceItem;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ResidentSubscription;
use App\Support\InvoiceNumberGenerator;
use App\Services\Billing\SubscriptionBillCalculator;

class BackfillSubscriptionInvoices extends Command
{
    protected $signature = 'billingsss:backfill {--dry-run}';

    protected $description = 'Backfill missing subscription invoices till today';

    public function handle(): int
    {
        $today  = now()->startOfDay();
        $dryRun = $this->option('dry-run');

        $this->info('========================================');
        $this->info('Subscription Invoice Backfill Started');
        $this->info('Date      : ' . $today->toDateString());
        $this->info('Dry Run   : ' . ($dryRun ? 'YES' : 'NO'));
        $this->info('========================================');

        Log::info('[BACKFILL] Started', [
            'date'    => $today->toDateString(),
            'dry_run' => $dryRun,
        ]);

        $created = 0;
        $skipped = 0;

        Resident::where('status', 'active')
            ->where(function ($q) use ($today) {
                $q->whereNull('check_out_date')
                  ->orWhere('check_out_date', '>', $today);
            })
            ->chunkById(50, function ($residents) use (
                $today,
                $dryRun,
                &$created,
                &$skipped
            ) {

                foreach ($residents as $resident) {

                    Log::debug('[BACKFILL] Processing resident', [
                        'resident_id' => $resident->id,
                    ]);

                    $subscriptions = ResidentSubscription::where('resident_id', $resident->id)
                        ->whereIn('status', ['active', 'expired'])
                        // ->where('billing_type', 'monthly')
                        ->whereNotNull('start_date')
                        ->get();

                    if ($subscriptions->isEmpty()) {
                        $skipped++;
                        continue;
                    }

                    $items = [];
                    $billingUpto = null;

                    foreach ($subscriptions as $sub) {

                        $calc = SubscriptionBillCalculator::calculate(
                            $sub,
                            $today,
                            backfill: true
                        );

                        Log::debug('[BACKFILL] Subscription calc', [
                            'subscription_id' => $sub->id,
                            'calc'            => $calc,
                        ]);

                        if (empty($calc)) {
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

                        if (!$billingUpto || $calc['to']->gt($billingUpto)) {
                            $billingUpto = $calc['to'];
                        }
                    }

                    if (empty($items)) {
                        $skipped++;
                        continue;
                    }

                    if ($dryRun) {
                        Log::info('[BACKFILL][DRY RUN] Invoice preview', [
                            'resident_id' => $resident->id,
                            'items'       => $items,
                            'billing_upto'=> optional($billingUpto)->toDateString(),
                        ]);
                        $created++;
                        continue;
                    }

                    DB::transaction(function () use (
                        $resident,
                        $items,
                        $billingUpto,
                        &$created
                    ) {

                        $invoice = Invoice::create([
                            'resident_id'       => $resident->id,
                            'invoice_number'    => InvoiceNumberGenerator::generate('SUB'),
                            'invoice_date'      => now()->startOfDay(),
                            'due_date'          => $billingUpto,
                            'total_amount'      => 0,
                            'paid_amount'       => 0,
                            'remaining_amount'  => 0,
                            'billing_upto'      => $billingUpto,
                            'remarks'           => 'System backfill',
                            'status'            => 'unpaid',
                        ]);

                        $total = 0;

                        foreach ($items as $item) {
                            $item['invoice_id'] = $invoice->id;
                            InvoiceItem::create($item);
                            $total += $item['total_amount'];
                        }

                        $invoice->update([
                            'total_amount'     => $total,
                            'remaining_amount'=> $total,
                        ]);

                        Log::info('[BACKFILL] Invoice created', [
                            'invoice_id'  => $invoice->id,
                            'resident_id' => $resident->id,
                            'amount'      => $total,
                        ]);

                        $created++;
                    });
                }
            });

        $this->info("Backfill complete.");
        $this->info("Invoices created: {$created}");
        $this->info("Residents skipped: {$skipped}");

        Log::info('[BACKFILL] Completed', [
            'created' => $created,
            'skipped' => $skipped,
        ]);

        return Command::SUCCESS;
    }
}
