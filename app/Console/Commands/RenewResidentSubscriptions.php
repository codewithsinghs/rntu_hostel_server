<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Resident;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\ResidentSubscription;

class RenewResidentSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:renew-resident-subscriptions';
    protected $signature = 'subscriptions:renew {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Create next subscription period for active residents';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $created = 0;
        $today  = now()->startOfDay();

        $this->info('🔄 Subscription renewal started' . ($dryRun ? ' (DRY RUN)' : ''));

        Resident::where('status', 'active')
            ->where(function ($q) use ($today) {
                $q->whereNull('check_out_date')
                    ->orWhere('check_out_date', '>', $today);
            })
            ->chunkById(50, function ($residents) use (&$created, $dryRun) {

                foreach ($residents as $resident) {

                    $subscriptions = Subscription::where('resident_id', $resident->id)
                        ->whereIn('status', ['active', 'expired', 'upcoming'])
                        ->whereNull('deleted_at')
                        ->orderByDesc('end_date')
                        ->get()
                        // ->groupBy('service_code'); // 🔑 important
                        ->groupBy(function ($sub) {
                            return $sub->service_type . ':' . $sub->invoice_item_id;
                        });

                    // foreach ($subscriptions as $serviceCode => $subs) {
                    foreach ($subscriptions as $key => $subs) {

                        $latest = $subs->first();

                        // ✅ Skip active subscription
                        // if ($latest->status === 'active') {
                        //     continue;
                        // }

                        $today = now()->startOfDay();
                        $expiryWindow = $today->copy()->addDays(7);

                        // ⛔ Skip if subscription is still valid beyond 7 days
                        if ($latest->end_date->gt($expiryWindow)) {
                            continue;
                        }

                        // ❌ Skip one-time
                        if ($latest->billing_type === 'one_time') {
                            continue;
                        }

                        // ❌ Skip zero-price recurring
                        // if ($latest->unit_price <= 0) {
                        //     continue;
                        // }

                        // ⛔ Prevent duplicate renewal
                        $nextStart = $latest->end_date->copy()->addDay();

                        $alreadyExists = Subscription::where('resident_id', $resident->id)
                            // ->where('service_code', $latest->service_code)
                            ->where('service_type', $latest->service_type)
                            ->where('invoice_item_id', $latest->invoice_item_id)
                            ->whereDate('start_date', $nextStart)
                            ->exists();


                        if ($alreadyExists) {
                            continue;
                        }

                        // 📅 Determine duration
                        $months = match ($latest->billing_cycle) {
                            'monthly'   => 1,
                            'quarterly' => 3,
                            'halfyear'  => 6,
                            'yearly'    => 12,
                            default     => 3,
                        };

                        $nextEnd = $nextStart->copy()
                            ->addMonthsNoOverflow($months)
                            ->subDay();

                        $status = match (true) {
                            $nextEnd->lt($today) => 'expired',
                            $nextStart->gt($today) => 'upcoming',
                            default => 'active',
                        };

                        // $status = $nextEnd->lt($today)
                        //     ? 'expired'
                        //     : 'active';


                        if (!$dryRun) {
                            Subscription::create([
                                'resident_id'   => $resident->id,
                                'service_code'  => $latest->service_code,
                                'invoice_item_id' => $latest->invoice_item_id,
                                'service_type'  => $latest->service_type,
                                'service_name'  => $latest->service_name,
                                'unit_price'    => $latest->unit_price,
                                'quantity'      => $latest->quantity,
                                'billing_type'  => $latest->billing_type,
                                'billing_cycle' => $latest->billing_cycle,
                                'start_date'    => $nextStart,
                                'end_date'      => $nextEnd,
                                'status'        => $status,

                            ]);
                        }

                        Log::info('[SUB-RENEWED]', [
                            'resident_id' => $resident->id,
                            'service'     => $latest->service_name,
                            'status'      => $status,
                            'from'        => $nextStart->toDateString(),
                            'to'          => $nextEnd->toDateString(),
                            'backfilled'  => $nextEnd->lt(now()),
                        ]);

                        $created++;
                    }
                }
            });

        $this->info("✅ Subscriptions renewed: {$created}");
    }
}
