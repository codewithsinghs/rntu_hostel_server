<?php

namespace App\Services\Billing;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\ResidentSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionBillingService
{
    public function run(): void
    {
        Log::info('================ BILLING CRON STARTED ================');

        $subs = ResidentSubscription::where('billing_type', 'recurring')
            ->whereIn('status', ['active', 'expired'])
            ->get();

        foreach ($subs as $sub) {
            try {
                $this->processSubscription($sub);
            } catch (\Throwable $e) {
                Log::error('[BILLING] Subscription processing failed', [
                    'subscription_id' => $sub->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('================ BILLING CRON COMPLETED ================');
    }

    protected function processSubscription(ResidentSubscription $sub): void
    {
        Log::info('[BILLING] Processing subscription', [
            'subscription_id' => $sub->id,
            'resident_id' => $sub->resident_id,
            'status' => $sub->status,
        ]);

        $lastCovered = BillingCoverageResolver::lastCoveredDate($sub);

        // Should we bill?
        if (!$this->shouldBill($sub, $lastCovered)) {
            Log::debug('[BILLING] Nothing to bill', [
                'subscription_id' => $sub->id,
            ]);
            return;
        }

        $window = BillingWindowCalculator::calculate($sub, $lastCovered);
        if (!$window) {
            return;
        }

        if (InvoiceDuplicateGuard::exists($sub, $window['from'], $window['to'])) {
            return;
        }

        $this->createInvoice($sub, $window);
    }

    protected function shouldBill(
        ResidentSubscription $sub,
        Carbon $lastCovered
    ): bool {
        $today = now()->startOfDay();

        // Backfill
        if ($lastCovered->lt($today)) {
            return true;
        }

        // Renewal within 7 days
        return $sub->end_date &&
            $today->diffInDays($sub->end_date, false) <= 7;
    }

    protected function createInvoice(
        ResidentSubscription $sub,
        array $window
    ): void {
        DB::transaction(function () use ($sub, $window) {

            $invoice = Invoice::create([
                'resident_id' => $sub->resident_id,
                'invoice_date' => now(),
                'status' => 'pending',
                'total_amount' => $sub->unit_price * $sub->quantity,
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_type' => $sub->service_type,
                'item_id' => $sub->invoice_item_id,
                'description' => $sub->service_name,
                'price' => $sub->unit_price,
                'from_date' => $window['from'],
                'to_date' => $window['to'],
                'total_amount' => $sub->unit_price * $sub->quantity,
                'is_subscribed' => 1,
            ]);

            Log::info('[BILLING] Invoice generated', [
                'invoice_id' => $invoice->id,
                'subscription_id' => $sub->id,
                'from' => $window['from']->toDateString(),
                'to' => $window['to']->toDateString(),
            ]);
        });
    }
}
