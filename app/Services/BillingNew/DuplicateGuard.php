<?php

namespace App\Services\Billing;
use App\Models\InvoiceItem;
use App\Models\Subscription;

class DuplicateGuard
{
    public function exists(Subscription $sub, array $period): bool
    {
        return InvoiceItem::query()
            ->where('item_type', $sub->service_type)
            ->where('item_id', $sub->invoice_item_id)
            ->whereDate('from_date', $period['from'])
            ->whereDate('to_date', $period['to'])
            ->whereHas('invoice', fn ($q) =>
                $q->where('resident_id', $sub->resident_id)
            )
            ->exists();
    }
}
