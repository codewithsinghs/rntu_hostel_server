<?php

namespace App\Services\Billing;

use Carbon\Carbon;
use App\Models\Resident;
use Illuminate\Console\Command;
use App\Models\ResidentSubscription;
use App\Services\Billing\InvoiceBuilder;


class BackfillSubscriptionInvoices extends Command
{
    protected $signature = 'billing:backfill {--dry-run}';

    public function handle()
    {
        $today = now()->startOfDay();

        Resident::where('status', 'active')
            ->where(
                fn($q) =>
                $q->whereNull('check_out_date')
                    ->orWhere('check_out_date', '>', $today)
            )
            ->chunkById(50, function ($residents) use ($today) {

                foreach ($residents as $resident) {

                    $subs = ResidentSubscription::where('resident_id', $resident->id)
                        ->whereIn('status', ['active', 'expired'])
                        ->get();

                    InvoiceBuilder::build(
                        $resident,
                        $subs,
                        $today,
                        $this->option('dry-run')
                    );
                }
            });
    }
}
