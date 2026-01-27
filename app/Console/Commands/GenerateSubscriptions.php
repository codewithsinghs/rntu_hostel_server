<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:generate-subscriptions';
    protected $signature = 'subscriptions:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate subscriptions for all active residents based on invoices and invoice items';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting subscription generation...');

        $residents = DB::table('residents')
            ->where('status', 'active')
            ->whereNotNull('check_in_date')
            ->get();

        foreach ($residents as $resident) {
            $data = DB::table('residents')
                ->join('invoices', 'invoices.resident_id', '=', 'residents.id')
                ->join('invoice_items', 'invoice_items.invoice_id', '=', 'invoices.id')
                // ->join('accessories', 'accessories.id', '=', 'invoice_items.item_id') // join accessories
                ->where('residents.id', $resident->id)
                // ->where('residents.status', 'active')
                // ->whereNotNull('residents.check_in_date')
                // ->where('accessories.is_active', 1) // only active accessories
                ->select([
                    'residents.guest_id',
                    'residents.id as resident_id',
                    'invoice_items.item_type',
                    'invoice_items.item_id',
                    DB::raw('residents.check_in_date AS start_date'),
                    DB::raw('DATE_SUB(DATE_ADD(residents.check_in_date, INTERVAL 3 MONTH), INTERVAL 1 DAY) AS end_date'),
                    DB::raw("'active' AS status"),
                    // 'invoice_items.unit_price',
                    // DB::raw('(invoice_items.unit_price * invoice_items.quantity) AS item_total_amount'),
                ])
                ->get()
                ->map(fn($item) => (array) $item)
                ->toArray();

            // Define items to ignore 
            $ignoreItems = ['Chair', 'Table', 'Almirah', 'Mattress'];
            if (!empty($data)) {
                foreach ($data as $subscription) {
                    // Skip if item_type or item_id matches ignore list
                    if (in_array($subscription['item_type'], $ignoreItems, true)) {
                        Log::info("Skipping subscription for item: {$subscription['item_type']} (ID: {$subscription['item_id']})");
                        continue;
                    }
                    DB::table('subscriptions')->updateOrInsert(
                        [
                            'resident_id' => $subscription['resident_id'],
                            'item_id'     => $subscription['item_id'],
                            'item_type'   => $subscription['item_type'],
                        ],
                        $subscription
                    );
                }
            }

            $this->info("Subscriptions synced for Resident ID: {$resident->id}");
        }

        $this->info('Subscription generation completed successfully.');
    }
}
