<?php

namespace App\Services\Billing;

use Carbon\Carbon;
use Nette\Utils\Json;
use Illuminate\Support\Facades\Log;
use App\Models\ResidentSubscription;
use App\Services\Billing\BillingDateResolver;

class SubscriptionBillCalculator
{
    // public static function calculate(ResidentSubscription $sub, Carbon $upto): array
    // {

    //     // 1️⃣ Skip one-time fees
    //     if ($sub->billing_type === 'one_time') {
    //         return [];
    //     }

    //     // 2️⃣ Determine FROM
    //     $from = $sub->last_billed_at
    //         ? $sub->last_billed_at->copy()->addDay()
    //         : $sub->start_date;

    //     if (!$from) return [];

    //     // 3️⃣ Determine TO (STRICT)
    //     $to = min(
    //         $sub->end_date ?? $upto,
    //         $upto
    //     );

    //     if ($from->gt($to)) return [];

    //     // 4️⃣ Count full months
    //     $months = BillingDateResolver::countMonthlyCharges($from, $to);

    //     if ($months <= 0) return [];

    //     // 5️⃣ Resolve exact billing range
    //     $finalTo = $from
    //         ->copy()
    //         ->addMonths($months)
    //         ->subDay();

    //     return [
    //         'from'   => $from,
    //         'to'     => $finalTo,
    //         'months' => $months,
    //         'amount' => $months * $sub->unit_price,
    //     ];
    // }

    // public static function calculate(
    //     ResidentSubscription $sub,
    //     ?\Carbon\Carbon $today = null,
    //     bool $backfill = false
    //     ): array {
    //     $today = $today?->copy()->startOfDay() ?? now()->startOfDay();

    //     // 1️⃣ Skip one-time fees always
    //     if ($sub->billing_type === 'one_time') {
    //         return [];
    //     }

    //     // 2️⃣ Determine billing start
    //     $from = $sub->last_billed_at
    //         ? $sub->last_billed_at->copy()->addDay()
    //         : $sub->start_date;

    //     if (!$from) {
    //         return [];
    //     }

    //     // 3️⃣ Determine billing end
    //     if ($backfill) {
    //         // Backfill → bill till subscription end
    //         $to = $sub->end_date;
    //     } else {
    //         // Nightly billing → bill only till today
    //         $to = min($sub->end_date, $today);
    //     }

    //     if (!$to || $from->gt($to)) {
    //         return [];
    //     }

    //     // 4️⃣ Calculate full months only
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

    // public static function calculate(
    //     ResidentSubscription $sub,
    //     \Carbon\Carbon $from,
    //     \Carbon\Carbon $to
    // ): array {
    //     $from = $from->copy()->startOfDay();
    //     $to   = $to->copy()->startOfDay();

    //     /* ---------------------------------------------
    //  | 1. HARD SKIPS
    //  |---------------------------------------------*/
    //     if ($sub->billing_type === 'one_time') {
    //         return [];
    //     }

    //     if ($from->gt($to)) {
    //         return [];
    //     }

    //     if ($to->gt($sub->end_date)) {
    //         $to = $sub->end_date->copy()->startOfDay();
    //     }

    //     /* ---------------------------------------------
    //  | 2. DUPLICATE BILLING PROTECTION
    //  |---------------------------------------------*/
    //     if ($sub->last_billed_at && $from->lte($sub->last_billed_at)) {
    //         $from = $sub->last_billed_at->copy()->addDay();
    //     }

    //     if ($from->gt($to)) {
    //         return [];
    //     }

    //     /* ---------------------------------------------
    //  | 3. FULL MONTH CALCULATION ONLY
    //  |---------------------------------------------*/
    //     $months = BillingDateResolver::countMonthlyCharges($from, $to);

    //     if ($months <= 0) {
    //         return [];
    //     }

    //     return [
    //         'from'   => $from,
    //         'to'     => $to,
    //         'months' => $months,
    //         'total_amount' => round($months * $sub->unit_price, 2),
    //     ];
    // }


    // public static function calculate(
    //     ResidentSubscription $sub,
    //     ?Carbon $from = null,
    //     ?Carbon $to = null,
    //     bool $backfill = false
    // ): array {
    //     $today = now()->startOfDay();

    //     if ($sub->billing_type === 'one_time') {
    //         // return self::empty();
    //         return [];
    //     }

    //     $from = $from?->copy()->startOfDay()
    //         ?? ($sub->last_billed_at
    //             ? $sub->last_billed_at->copy()->addDay()
    //             : $sub->start_date);

    //     if (!$from) {
    //         return self::empty();
    //     }

    //     $to = $to?->copy()->startOfDay()
    //         ?? ($backfill ? $sub->end_date : min($sub->end_date, $today));

    //     if (!$to || $from->gt($to)) {
    //         return self::empty();
    //     }

    //     $months = BillingDateResolver::countMonthlyCharges($from, $to);

    //     if ($months <= 0) {
    //         return self::empty();
    //     }

    //     $total = $months * $sub->unit_price;

    //     return [
    //         'from'          => $from,
    //         'to'            => $to,
    //         'months'        => $months,
    //         'unit_price'    => $sub->unit_price,
    //         'total_amount'  => $total,
    //     ];
    // }

    // private static function empty(): array
    // {
    //     return [
    //         'from' => null,
    //         'to' => null,
    //         'months' => 0,
    //         'unit_price' => 0,
    //         'total_amount' => 0,
    //     ];
    // }


    public static function calculate(
        ResidentSubscription $sub,
        ?Carbon $from = null,
        ?Carbon $to = Null,
        bool $backfill = false
    ): array {

        // skip one-time
        if ($sub->billing_type === 'one_time') {
            return [];
        }

        // fix dates
        $from = $from->copy()->startOfDay();
        $to   = $to->copy()->startOfDay();

        if ($from->gt($to)) {
            return [];
        }

        $months = BillingDateResolver::countMonthlyCharges($from, $to);

        if ($months <= 0) {
            return [];
        }

        // 5️⃣ Resolve exact billing range
        $finalTo = $from
            ->copy()
            ->addMonths($months)
            ->subDay();

        return [
            'from' => $from,
            'to' => $finalTo,
            'months' => $months,
            'total_amount' => $months * $sub->unit_price,
        ];
    }
}
