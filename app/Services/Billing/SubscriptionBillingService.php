<?php

namespace App\Services\Billing;

use App\Models\Invoice;
use App\Models\Resident;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
use App\Models\ResidentSubscription;

class SubscriptionBillingService
{
    public function run()
    {
        $residents = Resident::where('status', 'active')->get();

        foreach ($residents as $resident) {
            $this->billResident($resident);
        }
    }

    private function billResident(Resident $resident)
    {
        $subs = ResidentSubscription::where('resident_id', $resident->id)
            ->where('status', 'active')
            ->whereNotNull('end_date')
            ->where(function ($q) {
                $q->whereNull('last_billed_at')
                    ->orWhereColumn('last_billed_at', '<', 'end_date');
            })
            ->get();

        if ($subs->isEmpty()) return;

        $earliest = $subs->min('end_date');
        $latest   = $subs->max('end_date');

        // ❌ Checkout applied before expiry
        if ($resident->checkout_date && $resident->checkout_date <= $earliest) {
            return;
        }

        $proposed = $latest->copy()->subDays(config('billing.bill_before_days'));
        $minAllowed = $earliest->copy()->subDays(config('billing.min_lead_days'));

        $invoiceDate = $proposed->greaterThan($minAllowed) ? $proposed : $minAllowed;

        if (now()->lt($invoiceDate)) return;

        DB::transaction(function () use ($resident, $subs, $latest) {

            $exists = Invoice::where('resident_id', $resident->id)
                ->where('type', 'subscription_renewal')
                ->whereDate('billing_anchor_date', $latest)
                ->exists();

            if ($exists) return;

            $invoice = Invoice::create([
                'resident_id' => $resident->id,
                'type' => 'subscription_renewal',
                'billing_anchor_date' => $latest,
                'invoice_date' => now(),
                'due_date' => now()->addDays(3),
                'status' => 'unpaid',
            ]);

            foreach ($subs as $sub) {

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_type' => 'subscription_renewal',
                    'service_code' => $sub->service_code,
                    'description' => $sub->service_name,
                    'price' => $sub->unit_price,
                    'quantity' => $sub->quantity,
                    'period_from' => $sub->end_date->addDay(),
                    'period_to' => $sub->end_date->addMonth(),
                ]);

                $sub->update([
                    'last_billed_at' => now(),
                ]);

                if ($sub->billing_type === 'one_time') {
                    $sub->update(['status' => 'cancelled']);
                }
            }
        });
    }
}
