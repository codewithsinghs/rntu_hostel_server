<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\ResidentSubscription;

class GenerateSubscriptionsFromInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:generate-subscriptions-from-invoices';
    protected $signature = 'subscriptions:seed-from-invoices {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'One-time creation of subscriptions from paid invoices';

    /**
     * Execute the console command.
     */

    // public function handle()
    // {
    //     $invoices = Invoice::with('items')
    //         ->where('status', 'paid')
    //         ->get();

    //     Log::info('Paid invoices count: '.$invoices->count());


    //     foreach ($invoices as $invoice) {

    //         if (!$invoice->resident_id) continue;

    //         foreach ($invoice->items as $item) {

    //             if (!in_array($item->item_type, ['facility', 'addon', 'service'])) {
    //                 continue;
    //             }

    //             if (!$item->service_code || !$item->price) continue;

    //             if (ResidentSubscription::where('invoice_item_id', $item->id)->exists()) {
    //                 continue;
    //             }

    //             $data = [
    //                 'resident_id'     => $invoice->resident_id,
    //                 'invoice_id'      => $invoice->id,
    //                 'invoice_item_id' => $item->id,
    //                 'service_code'    => $item->service_code,
    //                 'service_name'    => $item->description,
    //                 'unit_price'      => $item->price,
    //                 'quantity'        => $item->quantity ?? 1,
    //                 'billing_type'    => $item->period_to ? 'monthly' : 'one_time',
    //                 'start_date'      => $item->period_from ?? $invoice->invoice_date,
    //                 'end_date'        => $item->period_to,
    //                 'status'          => 'active',
    //             ];

    //             if ($this->option('dry-run')) {
    //                 $this->info(json_encode($data));
    //             } else {
    //                 ResidentSubscription::create($data);
    //             }
    //         }
    //     }

    //     $this->info('Subscriptions seeded successfully.');
    // }

    // public function handle()
    // {
    //     // dd('COMMAND HIT');

    //     $created = 0;
    //     $skipped = 0;

    //     $invoices = Invoice::with('items')
    //         ->where('status', 'paid')
    //         ->get();

    //     Log::info('paid Invoices' . $invoices->count());

    //     $this->info('Paid invoices found: ' . $invoices->count());

    //     foreach ($invoices as $invoice) {

    //         Log::info('Invoices' . json_encode($invoice));

    //         if (!$invoice->resident_id) {
    //             $skipped++;
    //             continue;
    //         }

    //         foreach ($invoice->items as $item) {

    //             Log::info('Item Debug', [
    //                 'type'  => $item->item_type,
    //                 'id'    => $item->item_id,
    //                 'price' => $item->price,
    //             ]);

    //             // if (!in_array($item->item_type, ['facility', 'addon', 'service'])) {
    //             //     $skipped++;
    //             //     continue;
    //             // }

    //             if (empty($item->item_id) || $item->price === null) {
    //                 $skipped++;
    //                 continue;
    //             }

    //             if (
    //                 ResidentSubscription::where('resident_id', $invoice->resident_id)
    //                 ->where('service_code', $item->item_id)
    //                 ->exists()
    //             ) {
    //                 $skipped++;
    //                 continue;
    //             }

    //             Log::info('data Item' . json_encode($item));

    //             // $data = [
    //             //     'resident_id'  => $invoice->resident_id,
    //             //     'service_code' => $item->item_id,
    //             //     'service_name' => $item->description,
    //             //     'unit_price'   => $item->price,
    //             //     'quantity'     => $item->quantity ?? 1,
    //             //     'billing_type' => $item->period_to ? 'monthly' : 'one_time',
    //             //     'start_date'   => $item->from_date ?? $invoice->from_date,
    //             //     'end_date'     => $item->to_date,
    //             //     'status'       => 'active',
    //             // ];
    //             $data = [

    //                 'resident_id'  => $invoice->resident_id,
    //                 'invoice_id'  => $invoice->id,
    //                 'invoice_item_id'  => $item->id,
    //                 'service_code' => $item->item_id,
    //                 'service_name' => $item->description,
    //                 'unit_price'   => $item->price,
    //                 'quantity'     => $item->quantity ?? 1,
    //                 'billing_type' => $item->period_to ? 'monthly' : 'one_time',
    //                 'start_date'   => $item->from_date ?? $invoice->from_date,
    //                 'end_date'     => $item->to_date,
    //                 'status'       => 'active',
    //             ];


    //             Log::info('ResidentSubscription data' . json_encode($data));

    //             if ($this->option('dry-run')) {
    //                 $this->line('[DRY RUN] ' . json_encode($data));
    //             } else {
    //                 ResidentSubscription::unguarded(
    //                     fn() =>
    //                     ResidentSubscription::create($data)
    //                 );
    //                 $created++;
    //             }
    //         }
    //     }

    //     $this->info("Done. Created: {$created}, Skipped: {$skipped}");
    // }

    public function handle()
    {
        $created = 0;
        $updated = 0;

        Invoice::with('items')
            ->whereNotNull('resident_id')
            ->chunk(50, function ($invoices) use (&$created, &$updated) {

                foreach ($invoices as $invoice) {

                    foreach ($invoice->items as $item) {

                        if (empty($item->item_id) || $item->price === null) {
                            continue;
                        }

                        $subscription = ResidentSubscription::where('resident_id', $invoice->resident_id)
                            ->where('service_code', $item->item_id)
                            ->first();

                        $start = $item->from_date ?? $invoice->from_date ?? $invoice->invoice_date;
                        // $end   = $item->to_date;
                        $end = $item->to_date
                            ? Carbon::parse($item->to_date)
                            : null;

                        $oneTimeServices = ['Caution Money', 'Admission Fee', 'Security Deposit'];
                        $billingType = in_array($item->description, $oneTimeServices, true) ? 'one_time' : 'recurring';

                        // Default billing cycle 
                        $billingCycle = 'monthly';
                        if ($end) {
                            $diffInDays = $start->diffInDays($end);
                            if ($diffInDays <= 7) {
                                $billingCycle = 'weekly';
                            } elseif ($diffInDays <= 31) {
                                $billingCycle = 'monthly';
                            } elseif ($diffInDays <= 92) {
                                $billingCycle = 'quarterly';
                            } elseif ($diffInDays <= 183) {
                                $billingCycle = 'half_yearly';
                            } elseif ($diffInDays >= 365) {
                                $billingCycle = 'annual';
                            }
                        } else {
                            // If no end date, assume recurring monthly 
                            $billingCycle = 'monthly';
                        }

                        if (!$subscription) {

                            ResidentSubscription::create([
                                'resident_id'  => $invoice->resident_id,
                                'invoice_id'  => $invoice->id,
                                'invoice_item_id'  => $item->item_id,
                                'service_code' => '',
                                'service_type' => $item->item_type,
                                'service_name' => $item->description,
                                'unit_price'   => $item->price,
                                'quantity'     => $item->quantity ?? 1,
                                'billing_type' => $billingType,
                                'billing_cycle' => $billingCycle,
                                'start_date'   => $start,
                                'end_date'     => $end,
                                'next_billing_date' => $end,
                                'status'       => $end && $end->isPast() ? 'expired' : 'active',
                            ]);

                            $created++;
                        } else {

                            // extend period if newer invoice exists
                            if ($end && (!$subscription->end_date || $end->gt($subscription->end_date))) {
                                $subscription->update([
                                    'end_date' => $end,
                                    'status'   => 'active',
                                ]);
                                $updated++;
                            }
                        }
                    }
                }
            });

        $this->info("Done. Created: {$created}, Updated: {$updated}");
    }
}
