<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\Subscription;
use Carbon\Carbon;
use Throwable;

class AlignBillingDates extends Command
{
    protected $signature = 'billing:align-checkin-dates {--dry}';
    protected $description = 'Safely align FIRST invoice, invoice items and subscription dates using verified check-in date';

    protected int $updated = 0;
    protected int $skipped = 0;
    protected array $failed = [];

    // public function handle()
    // {
    //     $dry = $this->option('dry');

    //     $this->info(
    //         $dry
    //             ? '🔎 DRY RUN – No data will be changed'
    //             : '🚀 LIVE RUN – Updating FIRST invoices (RESIDENT ONLY)'
    //     );

    //     $firstInvoices = Invoice::with([
    //         'items',
    //         'resident',
    //     ])
    //         ->whereNotNull('resident_id')
    //         ->orderBy('created_at')
    //         ->get()
    //         ->groupBy('resident_id')
    //         ->map(fn($group) => $group->first());

    //     foreach ($firstInvoices as $invoice) {
    //         try {
    //             $resident = $invoice->resident;

    //             if (!$resident) {
    //                 throw new \Exception('Resident missing');
    //             }

    //             if (empty($resident->check_in_date)) {
    //                 throw new \Exception('Resident check-in date missing');
    //             }

    //             // Base start date
    //             $baseDate = Carbon::parse($resident->check_in_date)->startOfDay();

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Determine exact duration in days from existing invoice/item
    //         |--------------------------------------------------------------------------
    //         */
    //             $durationDays = 30; // default fallback

    //             if ($invoice->from_date && $invoice->to_date) {
    //                 $durationDays = Carbon::parse($invoice->from_date)
    //                     // ->diffInDays(Carbon::parse($invoice->to_date)) + 1; // inclusive
    //                     ->diffInDays(Carbon::parse($invoice->to_date)) ; // inclusive
    //             } elseif ($invoice->items->isNotEmpty()) {
    //                 $firstItem = $invoice->items->first();
    //                 if ($firstItem->from_date && $firstItem->to_date) {
    //                     $durationDays = Carbon::parse($firstItem->from_date)
    //                         // ->diffInDays(Carbon::parse($firstItem->to_date)) + 1; // inclusive
    //                         ->diffInDays(Carbon::parse($firstItem->to_date)); // inclusive
    //                 }
    //             }

    //             // Target end date using exact day alignment
    //             $targetToDate = $baseDate->copy()->addDays($durationDays - 1);

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Audit logging
    //         |--------------------------------------------------------------------------
    //         */
    //             $audit = [
    //                 'invoice' => [
    //                     'id'            => $invoice->id,
    //                     'from_date_old' => $invoice->from_date,
    //                     'to_date_old'   => $invoice->to_date,
    //                     'from_date_new' => $baseDate,
    //                     'to_date_new'   => $targetToDate,
    //                 ],
    //                 'items' => [],
    //                 'subscription' => null,
    //             ];

    //             foreach ($invoice->items as $item) {
    //                 $audit['items'][] = [
    //                     'id'            => $item->id,
    //                     'from_date_old' => $item->from_date,
    //                     'to_date_old'   => $item->to_date,
    //                     'from_date_new' => $baseDate,
    //                     'to_date_new'   => $targetToDate,
    //                 ];
    //             }

    //             $subscription = Subscription::where('resident_id', $resident->id)
    //                 ->orderBy('created_at')
    //                 ->first();

    //             if ($subscription) {
    //                 $audit['subscription'] = [
    //                     'id'            => $subscription->id,
    //                     'from_date_old' => $subscription->from_date,
    //                     'to_date_old'   => $subscription->to_date,
    //                     'from_date_new' => $baseDate,
    //                     'to_date_new'   => $targetToDate,
    //                 ];
    //             }

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Apply updates or dry-run preview
    //         |--------------------------------------------------------------------------
    //         */
    //             if (!$dry) {
    //                 // Update invoice
    //                 // $invoice->update([
    //                 //     'from_date' => $baseDate,
    //                 //     'to_date'   => $targetToDate,
    //                 // ]);

    //                 // Update invoice items
    //                 foreach ($invoice->items as $item) {
    //                     $item->update([
    //                         'from_date' => $baseDate,
    //                         'to_date'   => $targetToDate,
    //                     ]);
    //                 }

    //                 // Update subscription
    //                 if ($subscription) {
    //                     $subscription->update([
    //                         'from_date' => $baseDate,
    //                         'to_date'   => $targetToDate,
    //                     ]);
    //                 }

    //                 // Log audit info
    //                 \Log::info('Invoice alignment applied', $audit);
    //             } else {
    //                 // Dry-run preview
    //                 $this->line(
    //                     "Invoice {$invoice->id} (Resident {$resident->id}) → " .
    //                         "{$baseDate->toDateString()} to {$targetToDate->toDateString()}"
    //                 );
    //             }

    //             $this->updated++;
    //         } catch (\Throwable $e) {
    //             $this->failed[] = [
    //                 'invoice_id'  => $invoice->id,
    //                 'resident_id' => $invoice->resident_id,
    //                 'error'       => $e->getMessage(),
    //             ];
    //         }
    //     }

    //     /*
    // |--------------------------------------------------------------------------
    // | Summary
    // |--------------------------------------------------------------------------
    // */
    //     $this->newLine();
    //     $this->info('========== ALIGNMENT SUMMARY ==========');
    //     $this->info("Updated First Invoices : {$this->updated}");
    //     $this->info("Failed                : " . count($this->failed));

    //     if ($this->failed) {
    //         $this->error('❌ Failure Details:');
    //         foreach ($this->failed as $fail) {
    //             $this->line(
    //                 "Invoice {$fail['invoice_id']} (Resident {$fail['resident_id']}) → {$fail['error']}"
    //             );
    //         }
    //     }

    //     $this->info(
    //         $dry
    //             ? '✅ DRY RUN complete. No data was changed.'
    //             : '✅ Alignment complete. Dates updated safely.'
    //     );
    // }


    public function handle()
    {
        $dry = $this->option('dry');

        $this->info(
            $dry
                ? '🔎 DRY RUN – No data will be changed'
                : '🚀 LIVE RUN – Aligning FIRST invoices (FULL MONTH BILLING)'
        );

        $firstInvoices = Invoice::with(['items', 'resident'])
            ->whereNotNull('resident_id')
            ->orderBy('created_at')
            ->get()
            ->groupBy('resident_id')
            ->map(fn($group) => $group->first());

        foreach ($firstInvoices as $invoice) {
            try {
                $resident = $invoice->resident;

                if (!$resident || !$resident->check_in_date) {
                    throw new \Exception('Resident or check-in date missing');
                }

                /*
            |--------------------------------------------------------------------------
            | STEP 1: Determine billing months (NO FALLBACK)
            |--------------------------------------------------------------------------
            */
                $months = null;

                // 1️⃣ Invoice-level dates
                if ($invoice->from_date && $invoice->to_date) {
                    $months = $this->calculateBillingMonths(
                        Carbon::parse($invoice->from_date)->startOfDay(),
                        Carbon::parse($invoice->to_date)->startOfDay()
                    );
                }

                // 2️⃣ Item-level dates (MAX wins)
                if (!$months && $invoice->items->isNotEmpty()) {
                    $itemMonths = $invoice->items
                        ->map(function ($item) {
                            if (!$item->from_date || !$item->to_date) {
                                return null;
                            }

                            return $this->calculateBillingMonths(
                                Carbon::parse($item->from_date)->startOfDay(),
                                Carbon::parse($item->to_date)->startOfDay()
                            );
                        })
                        ->filter()
                        ->values();

                    if ($itemMonths->isNotEmpty()) {
                        $months = $itemMonths->max();
                    }
                }

                if (!$months || $months <= 0) {
                    throw new \Exception('Unable to determine billing months');
                }

                /*
            |--------------------------------------------------------------------------
            | STEP 2: Align dates
            |--------------------------------------------------------------------------
            */
                $baseDate = Carbon::parse($resident->check_in_date)->startOfDay();

                $targetToDate = $baseDate
                    ->copy()
                    ->addMonthsNoOverflow($months)
                    ->subDay();

                /*
            |--------------------------------------------------------------------------
            | STEP 3: Apply or Dry-run
            |--------------------------------------------------------------------------
            */
                if (!$dry) {

                    // Invoice
                    $invoice->update([
                        'from_date' => $baseDate,
                        'to_date'   => $targetToDate,
                    ]);

                    // Items
                    foreach ($invoice->items as $item) {
                        $item->update([
                            'from_date' => $baseDate,
                            'to_date'   => $targetToDate,
                            'month'     => $months,
                        ]);
                    }

                    // Subscription
                    $subscription = Subscription::where('resident_id', $resident->id)
                        ->orderBy('created_at')
                        ->first();

                    if ($subscription) {
                        $subscription->update([
                            'start_date' => $baseDate,
                            'end_date'   => $targetToDate,
                        ]);
                    }

                    \Log::info('MONTH ALIGNMENT APPLIED', [
                        'invoice_id' => $invoice->id,
                        'resident_id' => $resident->id,
                        'months' => $months,
                        'from' => $baseDate->toDateString(),
                        'to' => $targetToDate->toDateString(),
                    ]);
                } else {
                    $this->line(
                        "Invoice {$invoice->id} → {$baseDate->toDateString()} to {$targetToDate->toDateString()} ({$months} months)"
                    );
                }

                $this->updated++;
            } catch (\Throwable $e) {
                $this->failed[] = [
                    'invoice_id'  => $invoice->id,
                    'resident_id' => $invoice->resident_id,
                    'error'       => $e->getMessage(),
                ];
            }
        }

        /*
    |--------------------------------------------------------------------------
    | SUMMARY
    |--------------------------------------------------------------------------
    */
        $this->newLine();
        $this->info('========== ALIGNMENT SUMMARY ==========');
        $this->info("Updated : {$this->updated}");
        $this->info("Failed  : " . count($this->failed));

        if ($this->failed) {
            $this->error('❌ Failure Details:');
            foreach ($this->failed as $fail) {
                $this->line(
                    "Invoice {$fail['invoice_id']} (Resident {$fail['resident_id']}) → {$fail['error']}"
                );
            }
        }

        $this->info(
            $dry
                ? '✅ DRY RUN complete.'
                : '✅ FULL MONTH ALIGNMENT complete.'
        );
    }


    private function calculateBillingMonths(Carbon $from, Carbon $to): int
    {
        $months = 0;
        $cursor = $from->copy();

        while (
            $cursor
            ->copy()
            ->addMonthsNoOverflow(1)
            ->subDay()
            ->lte($to)
        ) {
            $months++;
            $cursor->addMonthsNoOverflow(1);
        }

        return $months;
    }
}
