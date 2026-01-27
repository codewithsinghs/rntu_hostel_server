<?php

namespace App\Console\Commands;

use Throwable;
use Carbon\Carbon;
use App\Models\InvoiceItem;
use Illuminate\Console\Command;

class VerifyAndRestoreInvoiceDurations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:verify-and-restore-invoice-durations';
    protected $signature = 'billing:verify-restore-durations {--dry}';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Safely verify and restore invoice item durations with validation';

    /**
     * Execute the console command.
     */
    protected int $updated = 0;
    protected int $skipped = 0;
    protected array $reasons = [];

    public function handle()
    {
        $dry = $this->option('dry');

        $this->info(
            $dry
                ? '🔎 DRY RUN – Verification only'
                : '🚀 LIVE RUN – Restoring verified items'
        );

        $items = InvoiceItem::with('invoice.guest')
            ->whereNotNull('from_date')
            ->whereNotNull('to_date')
            ->where('price', '>', 0)
            ->get();

        foreach ($items as $item) {
            try {
                $from = Carbon::parse($item->from_date);
                $to   = Carbon::parse($item->to_date);

                $currentDays = $from->diffInDays($to);

                // Skip one-time fees
                if ($item->item_type === 'fee' && $item->total_amount == $item->price) {
                    $this->skip($item, 'One-time fee');
                    continue;
                }

                $guest = $item->invoice?->guest;

                // Expected months
                $expectedMonths = null;

                if ($guest && $guest->months > 0) {
                    $expectedMonths = (int) $guest->months;
                } else {
                    $expectedMonths = (int) round($item->total_amount / $item->price);
                }

                if ($expectedMonths <= 0) {
                    $this->skip($item, 'Invalid expected months');
                    continue;
                }

                $expectedDays = $expectedMonths * 30;

                // Skip if already correct (±2 days tolerance)
                if (abs($currentDays - $expectedDays) <= 2) {
                    $this->skip($item, 'Already aligned');
                    continue;
                }

                // Safety check: huge mismatch
                if (abs($currentDays - $expectedDays) > 120) {
                    $this->skip($item, 'Mismatch too large, non-authentic');
                    continue;
                }

                $newTo = $from->copy()->addDays($expectedDays);

                if (!$dry) {
                    $item->update([
                        'to_date' => $newTo,
                    ]);
                }

                $this->updated++;
            } catch (Throwable $e) {
                $this->skip($item, $e->getMessage());
            }
        }

        $this->summary();
    }

    protected function skip($item, $reason)
    {
        $this->skipped++;
        $this->reasons[$reason] = ($this->reasons[$reason] ?? 0) + 1;
    }

    protected function summary()
    {
        $this->newLine();
        $this->info('========== VERIFICATION SUMMARY ==========');
        $this->info("Updated Items : {$this->updated}");
        $this->info("Skipped Items : {$this->skipped}");

        if ($this->reasons) {
            $this->info('Skip Reasons:');
            foreach ($this->reasons as $reason => $count) {
                $this->line(" - {$reason} : {$count}");
            }
        }

        $this->info('✅ Verification completed safely.');
    }
}
