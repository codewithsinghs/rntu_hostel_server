<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Guest;
use App\Models\Fee;
use App\Models\Accessory;
use Illuminate\Support\Facades\Log;

class InvoiceItemService
{
    public function syncInvoiceItems(Guest $guest, array $accessoryIds, int $months): void
    {
        $invoice = $guest->invoices()->latest()->first();
        if (!$invoice) {
            Log::warning("No invoice found for guest ID {$guest->id}");
            return;
        }

        $this->syncFees($invoice, $guest, $months);
        $this->syncAccessories($invoice, $accessoryIds, $months);
        $this->recalculateTotals($invoice);
    }

    protected function syncFees(Invoice $invoice, Guest $guest, int $months): void
    {
        $fees = Fee::select('fee_head_id', 'name', 'amount')
            ->where('is_active', 1)
            ->whereHas('feeHead', fn($q) =>
                $q->where('is_mandatory', 1)
                  ->where('university_id', $guest->faculty->university_id)
            )
            ->with('feeHead:id,is_one_time')
            ->get();

        foreach ($fees as $fee) {
            $price     = (float) $fee->amount;
            $isOneTime = $fee->feeHead?->is_one_time ?? false;
            $amount    = $isOneTime ? $price : $price * $months;

            $invoice->items()->updateOrCreate(
                [
                    'item_type' => 'fee',
                    'item_id'   => $fee->fee_head_id,
                ],
                [
                    'description'  => $fee->name,
                    'price'        => $price,
                    'from_date'    => now(),
                    'to_date'      => now()->addDays($months * 30),
                    'total_amount' => $amount,
                ]
            );
        }
    }

    protected function syncAccessories(Invoice $invoice, array $accessoryIds, int $months): void
    {
        $existingAccessoryIds = $invoice->items()
            ->where('item_type', 'accessory')
            ->pluck('item_id')
            ->map(fn($id) => (int) $id)
            ->toArray();

        $newAccessoryIds = array_map('intval', $accessoryIds);
        $toDelete        = array_diff($existingAccessoryIds, $newAccessoryIds);

        if (!empty($toDelete)) {
            $invoice->items()
                ->where('item_type', 'accessory')
                ->where('price', '>', 0)
                ->whereIn('item_id', $toDelete)
                ->delete();
        }

        foreach (array_unique($newAccessoryIds) as $accId) {
            $accessory = Accessory::with('accessoryHead')->find($accId);
            if (!$accessory) {
                Log::warning("Accessory ID {$accId} not found or inactive.");
                continue;
            }

            $price  = (float) $accessory->price;
            $amount = $price * $months;

            $invoice->items()->updateOrCreate(
                [
                    'item_type' => 'accessory',
                    'item_id'   => $accessory->id,
                ],
                [
                    'description'  => $accessory->accessoryHead?->name,
                    'price'        => $price,
                    'from_date'    => now(),
                    'to_date'      => now()->addDays($months * 30),
                    'total_amount' => $amount,
                ]
            );
        }
    }

    protected function recalculateTotals(Invoice $invoice): void
    {
        $grandTotal = $invoice->items()->sum('total_amount');
        $invoice->update([
            'total_amount'     => $grandTotal,
            'remaining_amount' => $grandTotal - $invoice->paid_amount,
        ]);
    }



    // using in Controller
    //  $months = $request->input('months');
    // $accessoryIds = $request->input('accessory_ids', []);

    // app(InvoiceItemService::class)->syncInvoiceItems($guest, $accessoryIds, $months);
}
