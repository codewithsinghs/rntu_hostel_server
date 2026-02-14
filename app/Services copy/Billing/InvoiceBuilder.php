<?php

namespace App\Services\Billing;

use App\Models\Invoice;
use App\Models\InvoiceItem;

class InvoiceBuilder
{
    public static function build($resident, $items, $dryRun = false)
    {
        $invoiceData = [
            'resident_id'      => $resident->id,
            'invoice_number'   => Invoice::generateInvoiceNumber('RA'),
            'invoice_date'     => now(),
            'due_date'         => now()->addDays(7),
            'total_amount'     => 0,
            'paid_amount'      => 0,
            'remaining_amount' => 0,
            'status'           => 'unpaid',
            'remarks'          => 'Auto-generated subscription invoice',
        ];

        if ($dryRun) {
            return ['invoice' => $invoiceData, 'items' => $items];
        }

        $invoice = Invoice::create($invoiceData);

        $total = 0;

        foreach ($items as $item) {
            InvoiceItem::create(array_merge($item, [
                'invoice_id' => $invoice->id
            ]));
            $total += $item['total_amount'];
        }

        $invoice->update([
            'total_amount'     => $total,
            'remaining_amount' => $total,
        ]);

        return $invoice;
    }
}
