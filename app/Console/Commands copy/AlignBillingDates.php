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
    //             : '🚀 LIVE RUN – Updating FIRST invoices only'
    //     );

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Fetch ONLY FIRST invoice per guest
    //     |--------------------------------------------------------------------------
    //     */
    //     $firstInvoices = Invoice::with([
    //         'items',
    //         'guest.resident',
    //     ])
    //         ->orderBy('created_at')
    //         ->get()
    //         ->groupBy('guest_id')
    //         ->map(fn($group) => $group->first());

    //     foreach ($firstInvoices as $invoice) {
    //         try {
    //             $guest = $invoice->guest;
    //             $resident = $guest?->resident;

    //             /*
    //             |--------------------------------------------------------------------------
    //             | Validate months
    //             |--------------------------------------------------------------------------
    //             */
    //             if (empty($guest?->months) || $guest->months <= 0) {
    //                 throw new \Exception('Guest months missing or invalid');
    //             }

    //             $expectedDays = $guest->months * 30;

    //             /*
    //             |--------------------------------------------------------------------------
    //             | Resolve BASE DATE (NO GUESSING)
    //             |--------------------------------------------------------------------------
    //             */
    //             if (!empty($resident?->check_in_date)) {
    //                 $baseDate = Carbon::parse($resident->check_in_date);
    //             } elseif (!empty($guest?->check_in_date)) {
    //                 $baseDate = Carbon::parse($guest->check_in_date);
    //             } elseif (!empty($invoice->from_date)) {
    //                 $baseDate = Carbon::parse($invoice->from_date);
    //             } else {
    //                 throw new \Exception('No reliable base date found');
    //             }

    //             $baseDate = $baseDate->startOfDay();
    //             $targetToDate = $baseDate->copy()->addDays($expectedDays);

    //             /*
    //             |--------------------------------------------------------------------------
    //             | Verify EXISTING invoice duration
    //             |--------------------------------------------------------------------------
    //             */
    //             if ($invoice->from_date && $invoice->to_date) {
    //                 $existingDays = Carbon::parse($invoice->from_date)
    //                     ->diffInDays(Carbon::parse($invoice->to_date));

    //                 if (abs($existingDays - $expectedDays) > 2) {
    //                     throw new \Exception(
    //                         "Invoice duration mismatch ({$existingDays} vs {$expectedDays})"
    //                     );
    //                 }
    //             }

    //             /*
    //             |--------------------------------------------------------------------------
    //             | Verify EACH invoice item duration
    //             |--------------------------------------------------------------------------
    //             */
    //             foreach ($invoice->items as $item) {
    //                 if (!$item->from_date || !$item->to_date) {
    //                     continue;
    //                 }

    //                 $itemDays = Carbon::parse($item->from_date)
    //                     ->diffInDays(Carbon::parse($item->to_date));

    //                 if (abs($itemDays - $expectedDays) > 2) {
    //                     throw new \Exception(
    //                         "InvoiceItem {$item->id} duration mismatch ({$itemDays} vs {$expectedDays})"
    //                     );
    //                 }
    //             }

    //             /*
    //             |--------------------------------------------------------------------------
    //             | Verify subscription (if exists)
    //             |--------------------------------------------------------------------------
    //             */
    //             $subscription = null;

    //             if ($resident) {
    //                 $subscription = Subscription::where('resident_id', $resident->id)
    //                     ->orderBy('created_at')
    //                     ->first();

    //                 if ($subscription && $subscription->from_date && $subscription->to_date) {
    //                     $subDays = Carbon::parse($subscription->from_date)
    //                         ->diffInDays(Carbon::parse($subscription->to_date));

    //                     if (abs($subDays - $expectedDays) > 2) {
    //                         throw new \Exception(
    //                             "Subscription duration mismatch ({$subDays} vs {$expectedDays})"
    //                         );
    //                     }
    //                 }
    //             }

    //             /*
    //             |--------------------------------------------------------------------------
    //             | APPLY UPDATES (DATES ONLY)
    //             |--------------------------------------------------------------------------
    //             */
    //             if (!$dry) {
    //                 // Invoice
    //                 $invoice->update([
    //                     'from_date' => $baseDate,
    //                     'to_date'   => $targetToDate,
    //                 ]);

    //                 // Invoice items
    //                 foreach ($invoice->items as $item) {
    //                     $item->update([
    //                         'from_date' => $baseDate,
    //                         'to_date'   => $targetToDate,
    //                     ]);
    //                 }

    //                 // Subscription
    //                 if ($subscription) {
    //                     $subscription->update([
    //                         'from_date' => $baseDate,
    //                         'to_date'   => $targetToDate,
    //                     ]);
    //                 }
    //             }

    //             $this->updated++;
    //         } catch (Throwable $e) {
    //             $this->failed[] = [
    //                 'invoice_id' => $invoice->id,
    //                 'guest_id'   => $invoice->guest_id,
    //                 'error'      => $e->getMessage(),
    //             ];
    //         }
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Summary
    //     |--------------------------------------------------------------------------
    //     */
    //     $this->newLine();
    //     $this->info('========== ALIGNMENT SUMMARY ==========');
    //     $this->info("Updated First Invoices : {$this->updated}");
    //     $this->info("Failed                : " . count($this->failed));

    //     if ($this->failed) {
    //         $this->error('❌ Failure Details:');
    //         foreach ($this->failed as $fail) {
    //             $this->line(
    //                 "Invoice {$fail['invoice_id']} (Guest {$fail['guest_id']}) → {$fail['error']}"
    //             );
    //         }
    //     }

    //     $this->info(
    //         $dry
    //             ? '✅ DRY RUN complete. No data was changed.'
    //             : '✅ Alignment complete. Dates updated safely.'
    //     );
    // }

    // public function handle()
    // {
    //     $dry = $this->option('dry');

    //     $this->info(
    //         $dry
    //             ? '🔎 DRY RUN – No data will be changed'
    //             : '🚀 LIVE RUN – Updating FIRST invoices (RESIDENT ONLY)'
    //     );

    //     // Fetch FIRST invoice per resident

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

    //             //  Validate resident

    //             if (!$resident) {
    //                 throw new \Exception('Resident missing');
    //             }

    //             if (empty($resident->check_in_date)) {
    //                 throw new \Exception('Resident check-in date missing');
    //             }

    //             // if (empty($resident->months) || $resident->months <= 0) {
    //             //     throw new \Exception('Resident months invalid');
    //             // }

    //             $expectedDays = $resident->months * 30;

    //             // Base date (STRICT)

    //             $baseDate = Carbon::parse($resident->check_in_date)->startOfDay();
    //             $targetToDate = $baseDate->copy()->addDays($expectedDays);

    //             // Validate existing invoice duration

    //             if ($invoice->from_date && $invoice->to_date) {
    //                 $existingDays = Carbon::parse($invoice->from_date)
    //                     ->diffInDays(Carbon::parse($invoice->to_date));

    //                 if (abs($existingDays - $expectedDays) > 2) {
    //                     throw new \Exception(
    //                         "Invoice duration mismatch ({$existingDays} vs {$expectedDays})"
    //                     );
    //                 }
    //             }

    //             // Validate invoice items duration

    //             foreach ($invoice->items as $item) {
    //                 if (!$item->from_date || !$item->to_date) {
    //                     continue;
    //                 }

    //                 $itemDays = Carbon::parse($item->from_date)
    //                     ->diffInDays(Carbon::parse($item->to_date));

    //                 if (abs($itemDays - $expectedDays) > 2) {
    //                     throw new \Exception(
    //                         "InvoiceItem {$item->id} duration mismatch ({$itemDays} vs {$expectedDays})"
    //                     );
    //                 }
    //             }

    //             // Validate subscription (FIRST only)

    //             $subscription = Subscription::where('resident_id', $resident->id)
    //                 ->orderBy('created_at')
    //                 ->first();

    //             if ($subscription && $subscription->from_date && $subscription->to_date) {
    //                 $subDays = Carbon::parse($subscription->from_date)
    //                     ->diffInDays(Carbon::parse($subscription->to_date));

    //                 if (abs($subDays - $expectedDays) > 2) {
    //                     throw new \Exception(
    //                         "Subscription duration mismatch ({$subDays} vs {$expectedDays})"
    //                     );
    //                 }
    //             }

    //             // Apply updates (DATES ONLY)

    //             if (!$dry) {
    //                 // Invoice
    //                 $invoice->update([
    //                     'from_date' => $baseDate,
    //                     'to_date'   => $targetToDate,
    //                 ]);

    //                 // Invoice items
    //                 foreach ($invoice->items as $item) {
    //                     $item->update([
    //                         'from_date' => $baseDate,
    //                         'to_date'   => $targetToDate,
    //                     ]);
    //                 }

    //                 // Subscription
    //                 if ($subscription) {
    //                     $subscription->update([
    //                         'from_date' => $baseDate,
    //                         'to_date'   => $targetToDate,
    //                     ]);
    //                 }
    //             }

    //             $this->updated++;
    //         } catch (Throwable $e) {
    //             $this->failed[] = [
    //                 'invoice_id'  => $invoice->id,
    //                 'resident_id' => $invoice->resident_id,
    //                 'error'       => $e->getMessage(),
    //             ];
    //         }
    //     }

    //     // Summary

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

    // public function handle()
    // {
    //     $dry = $this->option('dry');

    //     $this->info(
    //         $dry
    //             ? '🔎 DRY RUN – No data will be changed'
    //             : '🚀 LIVE RUN – Updating FIRST invoices (RESIDENT ONLY)'
    //     );

    //     /*
    // |--------------------------------------------------------------------------
    // | Fetch FIRST invoice per resident
    // |--------------------------------------------------------------------------
    // */
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

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Validate resident
    //         |--------------------------------------------------------------------------
    //         */
    //             if (!$resident) {
    //                 throw new \Exception('Resident missing');
    //             }

    //             if (empty($resident->check_in_date)) {
    //                 throw new \Exception('Resident check-in date missing');
    //             }

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Determine BASE DATE
    //         |--------------------------------------------------------------------------
    //         */
    //             $baseDate = Carbon::parse($resident->check_in_date)->startOfDay();

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Determine duration from existing invoice
    //         |--------------------------------------------------------------------------
    //         */
    //             if ($invoice->from_date && $invoice->to_date) {
    //                 $durationDays = Carbon::parse($invoice->from_date)
    //                     ->diffInDays(Carbon::parse($invoice->to_date));
    //             } else {
    //                 $durationDays = 30; // fallback if dates are missing
    //             }

    //             $targetToDate = $baseDate->copy()->addDays($durationDays);

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Validate EACH invoice item duration
    //         |--------------------------------------------------------------------------
    //         */
    //             foreach ($invoice->items as $item) {
    //                 if ($item->from_date && $item->to_date) {
    //                     $itemDays = Carbon::parse($item->from_date)
    //                         ->diffInDays(Carbon::parse($item->to_date));

    //                     if ($itemDays !== $durationDays) {
    //                         throw new \Exception(
    //                             "InvoiceItem {$item->id} duration mismatch ({$itemDays} vs {$durationDays})"
    //                         );
    //                     }
    //                 }
    //             }

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Handle subscription (if exists)
    //         |--------------------------------------------------------------------------
    //         */
    //             $subscription = Subscription::where('resident_id', $resident->id)
    //                 ->orderBy('created_at')
    //                 ->first();

    //             if ($subscription && $subscription->from_date && $subscription->to_date) {
    //                 $subDays = Carbon::parse($subscription->from_date)
    //                     ->diffInDays(Carbon::parse($subscription->to_date));

    //                 if ($subDays !== $durationDays) {
    //                     throw new \Exception(
    //                         "Subscription duration mismatch ({$subDays} vs {$durationDays})"
    //                     );
    //                 }
    //             }

    //             /*
    //         |--------------------------------------------------------------------------
    //         | APPLY UPDATES (DATES ONLY)
    //         |--------------------------------------------------------------------------
    //         */
    //             if (!$dry) {
    //                 // Invoice
    //                 $invoice->update([
    //                     'from_date' => $baseDate,
    //                     'to_date'   => $targetToDate,
    //                 ]);

    //                 // Invoice items
    //                 foreach ($invoice->items as $item) {
    //                     $item->update([
    //                         'from_date' => $baseDate,
    //                         'to_date'   => $targetToDate,
    //                     ]);
    //                 }

    //                 // Subscription
    //                 if ($subscription) {
    //                     $subscription->update([
    //                         'from_date' => $baseDate,
    //                         'to_date'   => $targetToDate,
    //                     ]);
    //                 }
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

    //             /*
    //         | Base start date from resident check-in
    //         */
    //             $baseDate = Carbon::parse($resident->check_in_date)->startOfDay();

    //             /*
    //         | Infer duration from invoice (if exists), else fallback to 30
    //         */
    //             if ($invoice->from_date && $invoice->to_date) {
    //                 $durationDays = Carbon::parse($invoice->from_date)
    //                     ->diffInDays(Carbon::parse($invoice->to_date));
    //             } elseif ($invoice->items->isNotEmpty()) {
    //                 // use first item duration
    //                 $firstItem = $invoice->items->first();
    //                 if ($firstItem->from_date && $firstItem->to_date) {
    //                     $durationDays = Carbon::parse($firstItem->from_date)
    //                         ->diffInDays(Carbon::parse($firstItem->to_date));
    //                 } else {
    //                     $durationDays = 30; // fallback
    //                 }
    //             } else {
    //                 $durationDays = 30; // fallback
    //             }

    //             $targetToDate = $baseDate->copy()->addDays($durationDays);

    //             /*
    //         | Update invoice dates
    //         */
    //             if (!$dry) {
    //                 $invoice->update([
    //                     'from_date' => $baseDate,
    //                     'to_date'   => $targetToDate,
    //                 ]);

    //                 // Align all items
    //                 foreach ($invoice->items as $item) {
    //                     $item->update([
    //                         'from_date' => $baseDate,
    //                         'to_date'   => $targetToDate,
    //                     ]);
    //                 }

    //                 // Align subscription
    //                 $subscription = Subscription::where('resident_id', $resident->id)
    //                     ->orderBy('created_at')
    //                     ->first();

    //                 if ($subscription) {
    //                     $subscription->update([
    //                         'from_date' => $baseDate,
    //                         'to_date'   => $targetToDate,
    //                     ]);
    //                 }
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

    //             // Determine duration in months from existing invoice/item

    //             $durationMonths = 1; // default fallback

    //             if ($invoice->from_date && $invoice->to_date) {
    //                 $durationDays = Carbon::parse($invoice->from_date)
    //                     ->diffInDays(Carbon::parse($invoice->to_date));
    //                 $durationMonths = max(1, round($durationDays / 30));
    //             } elseif ($invoice->items->isNotEmpty()) {
    //                 $firstItem = $invoice->items->first();
    //                 if ($firstItem->from_date && $firstItem->to_date) {
    //                     $itemDays = Carbon::parse($firstItem->from_date)
    //                         ->diffInDays(Carbon::parse($firstItem->to_date));
    //                     $durationMonths = max(1, round($itemDays / 30));
    //                 }
    //             }

    //             // Target end date using month-based alignment
    //             // $targetToDate = $baseDate->copy()->addMonths($durationMonths);
    //             $targetToDate = $baseDate->copy()->addMonths($durationMonths)->subDay();

    //             // AUDIT LOGGING
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

    //             //  APPLY UPDATES IF NOT DRY


    //             if (!$dry) {
    //                 // Update invoice
    //                 $invoice->update([
    //                     'from_date' => $baseDate,
    //                     'to_date'   => $targetToDate,
    //                 ]);

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
    //                 $this->line("Invoice {$invoice->id} (Resident {$resident->id}) → {$baseDate} to {$targetToDate}");
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

    //     // SUMMARY

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
                : '🚀 LIVE RUN – Updating FIRST invoices (RESIDENT ONLY)'
        );

        $firstInvoices = Invoice::with([
            'items',
            'resident',
        ])
            ->whereNotNull('resident_id')
            ->orderBy('created_at')
            ->get()
            ->groupBy('resident_id')
            ->map(fn($group) => $group->first());

        foreach ($firstInvoices as $invoice) {
            try {
                $resident = $invoice->resident;

                if (!$resident) {
                    throw new \Exception('Resident missing');
                }

                if (empty($resident->check_in_date)) {
                    throw new \Exception('Resident check-in date missing');
                }

                // Base start date
                $baseDate = Carbon::parse($resident->check_in_date)->startOfDay();

                /*
            |--------------------------------------------------------------------------
            | Determine exact duration in days from existing invoice/item
            |--------------------------------------------------------------------------
            */
                $durationDays = 30; // default fallback

                if ($invoice->from_date && $invoice->to_date) {
                    $durationDays = Carbon::parse($invoice->from_date)
                        // ->diffInDays(Carbon::parse($invoice->to_date)) + 1; // inclusive
                        ->diffInDays(Carbon::parse($invoice->to_date)) ; // inclusive
                } elseif ($invoice->items->isNotEmpty()) {
                    $firstItem = $invoice->items->first();
                    if ($firstItem->from_date && $firstItem->to_date) {
                        $durationDays = Carbon::parse($firstItem->from_date)
                            // ->diffInDays(Carbon::parse($firstItem->to_date)) + 1; // inclusive
                            ->diffInDays(Carbon::parse($firstItem->to_date)); // inclusive
                    }
                }

                // Target end date using exact day alignment
                $targetToDate = $baseDate->copy()->addDays($durationDays - 1);

                /*
            |--------------------------------------------------------------------------
            | Audit logging
            |--------------------------------------------------------------------------
            */
                $audit = [
                    'invoice' => [
                        'id'            => $invoice->id,
                        'from_date_old' => $invoice->from_date,
                        'to_date_old'   => $invoice->to_date,
                        'from_date_new' => $baseDate,
                        'to_date_new'   => $targetToDate,
                    ],
                    'items' => [],
                    'subscription' => null,
                ];

                foreach ($invoice->items as $item) {
                    $audit['items'][] = [
                        'id'            => $item->id,
                        'from_date_old' => $item->from_date,
                        'to_date_old'   => $item->to_date,
                        'from_date_new' => $baseDate,
                        'to_date_new'   => $targetToDate,
                    ];
                }

                $subscription = Subscription::where('resident_id', $resident->id)
                    ->orderBy('created_at')
                    ->first();

                if ($subscription) {
                    $audit['subscription'] = [
                        'id'            => $subscription->id,
                        'from_date_old' => $subscription->from_date,
                        'to_date_old'   => $subscription->to_date,
                        'from_date_new' => $baseDate,
                        'to_date_new'   => $targetToDate,
                    ];
                }

                /*
            |--------------------------------------------------------------------------
            | Apply updates or dry-run preview
            |--------------------------------------------------------------------------
            */
                if (!$dry) {
                    // Update invoice
                    $invoice->update([
                        'from_date' => $baseDate,
                        'to_date'   => $targetToDate,
                    ]);

                    // Update invoice items
                    foreach ($invoice->items as $item) {
                        $item->update([
                            'from_date' => $baseDate,
                            'to_date'   => $targetToDate,
                        ]);
                    }

                    // Update subscription
                    if ($subscription) {
                        $subscription->update([
                            'from_date' => $baseDate,
                            'to_date'   => $targetToDate,
                        ]);
                    }

                    // Log audit info
                    \Log::info('Invoice alignment applied', $audit);
                } else {
                    // Dry-run preview
                    $this->line(
                        "Invoice {$invoice->id} (Resident {$resident->id}) → " .
                            "{$baseDate->toDateString()} to {$targetToDate->toDateString()}"
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
    | Summary
    |--------------------------------------------------------------------------
    */
        $this->newLine();
        $this->info('========== ALIGNMENT SUMMARY ==========');
        $this->info("Updated First Invoices : {$this->updated}");
        $this->info("Failed                : " . count($this->failed));

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
                ? '✅ DRY RUN complete. No data was changed.'
                : '✅ Alignment complete. Dates updated safely.'
        );
    }
}
