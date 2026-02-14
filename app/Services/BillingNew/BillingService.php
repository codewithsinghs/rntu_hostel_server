<?php

namespace App\Services\Billing;
use App\Enums\BillingStatus;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use App\Services\Billing\InvoiceBuilder;

class BillingService
{
    public function run(): void
    {
        $subscriptions = Subscription::query()
            ->where('status', BillingStatus::ACTIVE->value)
            ->whereDate('next_billing_date', '<=', now()->addDays(7))
            ->whereHas('resident', fn ($q) =>
                $q->where('status', 'active')
                  ->whereNull('checked_out_at')
            )
            ->lockForUpdate()
            ->get();

        foreach ($subscriptions as $subscription) {
            $this->processSubscription($subscription);
        }
    }

    private function processSubscription(Subscription $subscription): void
    {
        DB::transaction(function () use ($subscription) {

            $subscription->update([
                'status' => BillingStatus::BILLING_LOCKED->value,
            ]);

            app(InvoiceBuilder::class)->build($subscription);

            $subscription->update([
                'status' => BillingStatus::ACTIVE->value,
            ]);
        });
    }
}
