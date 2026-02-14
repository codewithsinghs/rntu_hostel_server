<?php

namespace App\Services\Billing;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Resident;
use App\Models\InvoiceItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Support\InvoiceNumberGenerator;

class InvoiceBuilder
{
    // public static function build(Resident $resident, Collection $subs, Carbon $upto, bool $dryRun = false): ?Invoice {

    //     $items = [];

    //     foreach ($subs as $sub) {
    //         $calc = SubscriptionBillCalculator::calculate($sub, $upto);
    //         if (empty($calc)) continue;

    //         $items[] = [
    //             'item_type'     => 'subscription',
    //             'item_id'       => $sub->service_code,
    //             'description'   => $sub->service_name,
    //             'price'         => $sub->unit_price,
    //             'from_date'     => $calc['from'],
    //             'to_date'       => $calc['to'],
    //             'month'         => $calc['months'],
    //             'total_amount'  => $calc['amount'],
    //             'is_subscribed' => 1,
    //         ];
    //     }

    //     if (empty($items)) return null;

    //     if ($dryRun) {
    //         Log::info('[DRY RUN] Invoice preview', $items);
    //         return null;
    //     }

    //     return DB::transaction(function () use ($resident, $items) {

    //         $total = collect($items)->sum('total_amount');

    //         $invoice = Invoice::create([
    //             'resident_id'      => $resident->id,
    //             'invoice_number'   => InvoiceNumberGenerator::next(),
    //             // 'invoice_number' => Invoice::generateInvoiceNumber('RA'),
    //             'invoice_date'     => today(),
    //             'due_date'         => today()->addDays(7),
    //             'total_amount'     => $total,
    //             'paid_amount'      => 0,
    //             'remaining_amount' => $total,
    //             'billing_upto'     => collect($items)->max('to_date'),
    //             'status'           => 'unpaid',
    //         ]);

    //         foreach ($items as $item) {
    //             $invoice->items()->create($item);
    //         }

    //         return $invoice;
    //     });
    // }


    public static function build(Resident $resident, array $items, bool $dryRun = false): ?Invoice {

        if (empty($items)) {
            return null;
        }

        if ($dryRun) {
            Log::info('[DRY RUN] Invoice preview', $items);
            return null;
        }

        return DB::transaction(function () use ($resident, $items) {

            $total = collect($items)->sum('total_amount');

            $invoice = Invoice::create([
                'resident_id'      => $resident->id,
                'invoice_number'   => InvoiceNumberGenerator::next(),
                'invoice_date'     => today(),
                'due_date'         => today()->addDays(7),
                'total_amount'     => $total,
                'paid_amount'      => 0,
                'remaining_amount' => $total,
                'billing_upto'     => collect($items)->max('to_date'),
                'status'           => 'unpaid',
            ]);

            foreach ($items as $item) {
                $invoice->items()->create($item);
            }

            return $invoice;
        });
    }
}
