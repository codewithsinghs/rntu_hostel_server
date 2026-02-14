<?php

namespace App\Services\Billing;

use Carbon\Carbon;
use Nette\Utils\Json;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\Log;
use App\Models\ResidentSubscription;
use App\Services\Billing\BillingDateResolver;

class SubscriptionBillCalculator
{
    // public static function calculate(ResidentSubscription $sub): array
    // {
    //     Log::info('sub'. json_encode($sub));
    //     // Skip one-time services
    //     if ($sub->billing_type === 'one_time') {
    //         Log::info('[BILLING] Skipped one-time subscription', [
    //             'subscription_id' => $sub->id
    //         ]);
    //         return [];
    //     }

    //     $from = $sub->last_billed_at
    //         ? $sub->last_billed_at->copy()->addDay()
    //         : $sub->start_date;

    //     $to = $sub->end_date;

    //     if ($from->gt($to)) {
    //         return [];
    //     }

    //     // $months = BillingDateResolver::countFullMonths($from, $to);
    //     // $months = BillingDateResolver::countMonthlyCycles($from, $to);
    //     $months = BillingDateResolver::countMonthlyCharges($from, $to);

    //     $amount = $months * $sub->unit_price;

    //     return [
    //         'from'   => $from,
    //         'to'     => $to,
    //         'months' => $months,
    //         'amount' => $amount,
    //     ];
    // }


    // calculate() must cap billing at today.
    // public static function calculate(ResidentSubscription $sub, ?Carbon $upto = null): array
    // {
    //     $upto ??= now()->startOfDay();

    //     if ($sub->billing_type === 'one_time') {
    //         return [];
    //     }

    //     $from = $sub->last_billed_at
    //         ? $sub->last_billed_at->copy()->addDay()
    //         : $sub->start_date;

    //     $to = $sub->end_date
    //         ? min($sub->end_date, $upto)
    //         : $upto;

    //     if ($from->gt($to)) {
    //         return [];
    //     }

    //     $months = BillingDateResolver::countMonthlyCharges($from, $to);

    //     if ($months <= 0) {
    //         return [];
    //     }

    //     return [
    //         'from'   => $from,
    //         'to'     => $to,
    //         'months' => $months,
    //         'amount' => $months * $sub->unit_price,
    //     ];
    // }


    // Final Works

    // public static function calculate(ResidentSubscription $sub): array
    // {
    //     $today ??= now()->startOfDay();
    //     // $upto ??= now()->startOfDay();

    //     // One-time fee → never bill here
    //     if ($sub->billing_type === 'one_time') {
    //         Log::debug('[BILLING] Skipped – one time fee', [
    //             'sub_id' => $sub->id,
    //             'service' => $sub->service_name,
    //         ]);
    //         return [];
    //     }


    //     // Zero price → skip
    //     // if ($sub->unit_price <= 0) {
    //     //     Log::debug('[BILLING] Skipped – zero price', [
    //     //         'sub_id' => $sub->id,
    //     //         'service' => $sub->service_name,
    //     //     ]);
    //     //     return [];
    //     // }

    //     // Already billed till today
    //     if ($sub->last_billed_at && $sub->last_billed_at->gte($today)) {
    //         Log::debug('[BILLING] Skipped – already billed till today', [
    //             'sub_id' => $sub->id,
    //             'last_billed_at' => $sub->last_billed_at,
    //         ]);
    //         return [];
    //     }

    //     $from = $sub->last_billed_at
    //         ? $sub->last_billed_at->copy()->addDay()
    //         : $sub->start_date;

    //     if (!$from) {
    //         return [];
    //     }

    //     $to = $sub->end_date
    //         // ? min($sub->end_date->copy(), $today)
    //         ? min($sub->end_date, $today)
    //         : $today;

    //     // 🚨 THE MOST IMPORTANT CHECK
    //     if ($from->gt($to)) {
    //         return [];
    //     }

    //     $months = BillingDateResolver::countMonthlyCharges($from, $to);

    //     // Monthly calculation (FULL MONTHS ONLY)
    //     // $months = $from->copy()->startOfDay()
    //     //     ->diffInMonths($to->copy()->endOfDay());

    //     if ($months <= 0) {
    //         return [];
    //     }

    //     return [
    //         'from'   => $from,
    //         'to'     => $to,
    //         'months' => $months,
    //         'amount' => $months * $sub->unit_price,
    //     ];
    // }

    // public static function calculate(ResidentSubscription $sub, Carbon $today): array
    // {
    //     Log::info('Subscription data in calc' . json_encode($sub));

    //     // 1️⃣ One-time fee → never bill here
    //     if ($sub->billing_type === 'one_time') {
    //         return [];
    //     }

    //     // 2️⃣ Determine FROM
    //     $from = $sub->last_billed_at
    //         ? $sub->last_billed_at->copy()->addDay()
    //         : $sub->start_date?->copy();

    //     if (!$from) {
    //         return [];
    //     }
    //     Log::info('From date data in calc' . json_encode($from));
    //     // 3️⃣ Determine TO (CRITICAL FIX)
    //     $to = $sub->end_date?->copy();
    //     if (!$to) {
    //         return [];
    //     }

    //     Log::info('To date data in calc' . json_encode($to));
    //     // 4️⃣ Apply MAX 3-MONTH CAP from FROM date
    //     $maxTo = $from->copy()->addMonthsNoOverflow(3)->subDay();
    //     Log::info('Max To date data in calc' . json_encode($maxTo));
    //     if ($to->gt($maxTo)) {
    //         $to = $maxTo;
    //     }

    //     Log::info('Final To date data in calc' . json_encode($to));
    //     // 4️⃣ Safety
    //     if ($from->gt($to)) {
    //         return [];
    //     }

    //     // 5️⃣ Count FULL months
    //     $months = BillingDateResolver::countMonthlyCharges($from, $to);
    //     Log::info('month' . json_encode($months));
    //     if ($months <= 0) {
    //         return [];
    //     }

    //     // Log::info('[BILLING] calculated month', [
    //     Log::info('[BILLING] calculated month (max 3 cap)', [
    //         'sub_id' => $sub->id,
    //         'from'   => $from->toDateString(),
    //         'to'     => $to->toDateString(),
    //         'months' => $months,
    //         'amount' => $months * $sub->unit_price,
    //     ]);

    //     return [
    //         'from'   => $from,
    //         'to'     => $to,
    //         'months' => $months,
    //         'amount' => $months * $sub->unit_price,
    //     ];
    // }

    public static function calculate(ResidentSubscription $sub): array
    {
        if ($sub->billing_type === 'one_time') {
            return [];
        }

        if (!$sub->start_date || !$sub->end_date) {
            return [];
        }

        /*
    |--------------------------------------------------------------------------
    | 1️⃣ Determine LAST COVERED DATE
    |--------------------------------------------------------------------------
    | Priority:
    | - Invoice items (actual payment)
    | - Else subscription end date (because it was created FROM invoice)
    |--------------------------------------------------------------------------
    */
        $lastCovered = $sub->end_date->copy(); // 👈 KEY LINE

        $latestInvoiceItem = InvoiceItem::where('item_type', $sub->service_type)
            ->where('item_id', $sub->invoice_item_id)
            ->where('invoice_id', $sub->invoice_id)
            ->whereHas(
                'invoice',
                fn($q) =>
                $q->whereNotIn('status', ['cancelled', 'failed'])
            )
            ->orderByDesc('to_date')
            ->first();

        if ($latestInvoiceItem?->to_date && $latestInvoiceItem->to_date->gt($lastCovered)) {
            $lastCovered = $latestInvoiceItem->to_date->copy();
        }

        /*
    |--------------------------------------------------------------------------
    | 2️⃣ Billing FROM = next unpaid day
    |--------------------------------------------------------------------------
    */
        $from = $lastCovered->copy()->addDay();

        /*
    |--------------------------------------------------------------------------
    | 3️⃣ Billing TO (future, rolling)
    |--------------------------------------------------------------------------
    */
        $to = now()->addMonthsNoOverflow(3)->subDay();

        /*
    |--------------------------------------------------------------------------
    | 4️⃣ Safety checks
    |--------------------------------------------------------------------------
    */
        if ($from->gt($to)) {
            return [];
        }

        $months = BillingDateResolver::countMonthlyCharges($from, $to);
        if ($months <= 0) {
            return [];
        }

        return [
            'from'   => $from,
            'to'     => $to,
            'months' => $months,
            'amount' => $months * $sub->unit_price,
        ];
    }
}
