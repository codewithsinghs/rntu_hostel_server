<?php

namespace App\Services\Billing;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Subscription;

class InvoiceBuilder
{
    public function build(Subscription $subscription): void
    {
        $period = app(BillingPeriodCalculator::class)
            ->calculate($subscription);

        if (app(DuplicateGuard::class)->exists($subscription, $period)) {
            BillingLogger::skip($subscription, 'DUPLICATE');
            return;
        }

        $invoice = Invoice::firstOrCreate([
            'resident_id' => $subscription->resident_id,
            'status' => 'unpaid',
        ], [
            'invoice_number' => $this->generateInvoiceNumber(),
            'invoice_date' => now(),
            'due_date' => now()->addDays(7),
        ]);

        $amount = app(ProrationCalculator::class)
            ->calculate($subscription, $period);

        $item = InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'item_type' => $subscription->service_type,
            'item_id' => $this->resolveItemId($subscription),
            'from_date' => $period['from'],
            'to_date' => $period['to'],
            'month' => $period['months'],
            'price' => $subscription->unit_price,
            'total_amount' => $amount,
            'is_subscribed' => true,
        ]);

        $subscription->update([
            'invoice_id' => $invoice->id,
            'invoice_item_id' => $item->id,
            'last_billed_at' => $period['to'],
            'next_billing_date' => $period['to']->copy()->addDay(),
        ]);
    }
}
