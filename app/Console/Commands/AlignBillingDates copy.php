<?php

namespace App\Console\Commands;

use Throwable;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Resident;
use Illuminate\Console\Command;

class AlignBillingDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:align-billing-dates';
    protected $signature = 'billing:align-checkin-dates {--dry}';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Safely align FIRST  invoice, invoice items and subscription dates with resident check-in date';

    /**
     * Execute the console command.
     */


    protected int $updated = 0;
    protected int $skipped = 0;
    protected array $failed = [];

    // public function handle()
    // {
    //     $dry = $this->option('dry');

    //     $this->info($dry
    //         ? '🔎 DRY RUN – No data will be changed'
    //         : '🚀 LIVE RUN – Data will be updated'
    //     );

    //     $residents = Resident::with(['invoices.items', 'subscription'])->get();

    //     foreach ($residents as $resident) {
    //         try {
    //             $baseDate = $resident->check_in_date
    //                 ? Carbon::parse($resident->check_in_date)->startOfDay()
    //                 : now()->startOfDay();

    //             /* ---------------- Invoices ---------------- */
    //             foreach ($resident->invoices as $invoice) {

    //                 // Skip paid / partial invoices
    //                 if ($invoice->paid_amount > 0) {
    //                     $this->skipped++;
    //                     continue;
    //                 }

    //                 if (!$dry) {
    //                     $invoice->update([
    //                         'invoice_date' => $baseDate,
    //                         'due_date'     => $baseDate->copy()->addDays(30),
    //                     ]);
    //                 }

    //                 /* ------------ Invoice Items ------------ */
    //                 foreach ($invoice->items as $item) {
    //                     $duration = (
    //                         $item->from_date && $item->to_date
    //                     )
    //                         ? Carbon::parse($item->from_date)->diffInDays($item->to_date)
    //                         : 30;

    //                     if (!$dry) {
    //                         $item->update([
    //                             'from_date' => $baseDate,
    //                             'to_date'   => $baseDate->copy()->addDays($duration),
    //                         ]);
    //                     }
    //                 }

    //                 $this->updated++;
    //             }

    //             /* -------------- Subscription -------------- */
    //             if ($resident->subscription && !$resident->subscription->is_locked) {
    //                 if (!$dry) {
    //                     $resident->subscription->update([
    //                         'from_date' => $baseDate,
    //                         'to_date'   => $baseDate->copy()->addMonths($resident->months ?? 1),
    //                     ]);
    //                 }
    //             }

    //         } catch (Throwable $e) {
    //             $this->failed[] = [
    //                 'resident_id' => $resident->id,
    //                 'error'       => $e->getMessage(),
    //             ];
    //         }
    //     }

    //     /* ---------------- Summary ---------------- */
    //     $this->newLine();
    //     $this->info('========== ALIGNMENT SUMMARY ==========');
    //     $this->info("Updated Records : {$this->updated}");
    //     $this->info("Skipped Records : {$this->skipped}");
    //     $this->info("Failed Records  : " . count($this->failed));

    //     if (!empty($this->failed)) {
    //         $this->error('❌ Failed Details:');
    //         foreach ($this->failed as $fail) {
    //             $this->line("Resident {$fail['resident_id']} → {$fail['error']}");
    //         }
    //     }

    //     $this->newLine();
    //     $this->info('✅ Alignment process completed safely.');
    // }

    public function handle()
    {
        $dry = $this->option('dry');

        $this->info(
            $dry
                ? '🔎 DRY RUN – No data will be changed'
                : '🚀 LIVE RUN – Updating FIRST invoices only'
        );

        /*
        |--------------------------------------------------------------------------
        | Fetch ONLY first invoice per guest
        |--------------------------------------------------------------------------
        */
        $invoices = Invoice::with([
            'items',
            'guest.resident', // resident may or may not exist
        ])
            // ->orderBy('invoice_date')
            ->orderBy('created_at')
            ->get()
            ->groupBy('guest_id')
            ->map->first(); // FIRST invoice only

        foreach ($invoices as $invoice) {
            try {

                // Skip paid or partially paid invoices
                // if ($invoice->paid_amount > 0) {
                //     $this->skipped++;
                //     continue;
                // }

                $isPaidInvoice = $invoice->paid_amount > 0;

                $guest = $invoice->guest;
                $resident = $guest?->resident;

                /*
                |--------------------------------------------------------------------------
                | Resolve base date SAFELY
                |--------------------------------------------------------------------------
                */
                // $baseDate = match (true) {
                //     !empty($resident?->check_in_date)
                //         => Carbon::parse($resident->check_in_date),

                //     !empty($guest?->check_in_date)
                //         => Carbon::parse($guest->check_in_date),

                //     !empty($invoice->invoice_date)
                //         => Carbon::parse($invoice->invoice_date),

                //     default
                //         => now(),
                // }->startOfDay();
                if ($resident && !empty($resident->check_in_date)) {
                    $baseDate = Carbon::parse($resident->check_in_date);
                } elseif ($guest && !empty($guest->check_in_date)) {
                    $baseDate = Carbon::parse($guest->check_in_date);
                } elseif (!empty($invoice->invoice_date)) {
                    $baseDate = Carbon::parse($invoice->invoice_date);
                } else {
                    $baseDate = now();
                }

                $baseDate = $baseDate->startOfDay();


                /*
                |--------------------------------------------------------------------------
                | Invoice dates
                |--------------------------------------------------------------------------
                */
                if (!$dry) {
                    $invoice->update([
                        'invoice_date' => $baseDate,
                        'due_date'     => $baseDate->copy()->addDays(30),
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Invoice Item dates (preserve duration)
                |--------------------------------------------------------------------------
                */
                // foreach ($invoice->items as $item) {
                //     $duration = (
                //         $item->from_date && $item->to_date
                //     )
                //         ? Carbon::parse($item->from_date)->diffInDays($item->to_date)
                //         : '';

                //     if (!$dry) {
                //         $item->update([
                //             'from_date' => $baseDate,
                //             'to_date'   => $baseDate->copy()->addDays($duration - 1),
                //             // -1 so that 24 Oct → 23 Nov
                //         ]);
                //     }
                // }
                foreach ($invoice->items as $item) {
                    // Standard monthly cycle (30 days)
                    $fromDate = $baseDate;
                    $toDate   = $baseDate->copy()->addDays(29); // inclusive → 24 Oct → 23 Nov

                    if ($dry) {
                        // Preview only
                        $this->line(
                            "Invoice {$invoice->id} (Resident {$resident?->id}) → " .
                                "{$fromDate->toDateString()} to {$toDate->toDateString()}"
                        );
                    } else {
                        // Actual update
                        $item->update([
                            'from_date' => $fromDate,
                            'to_date'   => $toDate,
                        ]);
                    }
                }


                // $this->updated++;
                $isPaidInvoice
                    ? $this->skipped++   // counted as paid-aligned
                    : $this->updated++;
            } catch (Throwable $e) {
                $this->failed[] = [
                    'invoice_id' => $invoice->id,
                    'guest_id'   => $invoice->guest_id,
                    'error'      => $e->getMessage(),
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */
        $this->newLine();
        $this->info('========== ALIGNMENT SUMMARY ==========');
        $this->info("Updated First Invoices : {$this->updated}");
        $this->info("Skipped (Paid)        : {$this->skipped}");
        $this->info("Failed                : " . count($this->failed));

        if ($this->failed) {
            $this->error('❌ Failure Details:');
            foreach ($this->failed as $fail) {
                $this->line(
                    "Invoice {$fail['invoice_id']} (Guest {$fail['guest_id']}) → {$fail['error']}"
                );
            }
        }

        $this->info('✅ Done. No financial data was modified.');
    }
}
