<?php

namespace App\Services\Billing;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Resident;
use App\Models\InvoiceItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public static function apply(
        Invoice $invoice,
        float $amount,
        array $meta = []
    ): void {

        DB::transaction(function () use ($invoice, $amount, $meta) {

            // Payment::create([
            //     'invoice_id' => $invoice->id,
            //     'amount'     => $amount,
            //     'meta'       => json_encode($meta),
            // ]);

            $invoice->paid_amount += $amount;
            $invoice->recomputeStatus();
            $invoice->save();

            InvoiceSettlementService::settle($invoice);
        });
    }
}
