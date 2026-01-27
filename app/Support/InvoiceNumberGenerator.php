<?php

namespace App\Support;

use App\Models\InvoiceSequence;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InvoiceNumberGenerator
{
    // public static function generate(string $type = 'G'): string
    // {
    //     $prefix = 'INV';
    //     $month  = now()->format('ym'); // 2401 etc.

    //     $sequence = DB::transaction(function () use ($type, $month) {

    //         $record = InvoiceSequence::lockForUpdate()
    //             ->firstOrCreate(
    //                 ['type' => $type, 'month' => $month],
    //                 ['sequence' => 0]
    //             );

    //         $record->sequence++;
    //         $record->save();

    //         return $record->sequence;
    //     });

    //     $seqStr = str_pad($sequence, 4, '0', STR_PAD_LEFT);

    //     return "{$prefix}-{$type}-{$month}-{$seqStr}";
    // }

    /**
     * Generate invoice number in a safe manner without creating extra table.
     * Format: INV-<TYPE>-<YYMM>-<SEQ>
     */
    // public static function generate(string $type = 'G'): string
    // {
    //     $prefix = 'INV';
    //     $month  = now()->format('ym'); // e.g., 2401

    //     $sequence = DB::transaction(function () use ($type, $month) {

    //         // Lock the invoices table for reading sequence safely
    //         $max = Invoice::where('invoice_number', 'like', "{$prefix}-{$type}-{$month}-%")
    //             ->lockForUpdate()
    //             ->max(DB::raw("CAST(SUBSTRING_INDEX(invoice_number, '-', -1) AS UNSIGNED)"));

    //         $nextSeq = ($max ?? 0) + 1;

    //         return $nextSeq;
    //     });

    //     $seqStr = str_pad($sequence, 4, '0', STR_PAD_LEFT);

    //     return "{$prefix}-{$type}-{$month}-{$seqStr}";
    // }

 




    /**
     * Generate invoice number WITHOUT extra tables
     *
     * Format:
     * INV-SUB-2601-000123
     */
    public static function generate(string $type = 'GEN'): string
    {
        $prefix = 'INV';
        $date   = now()->format('ym');

        // Fetch latest invoice for same type+month
        $lastInvoice = Invoice::where('invoice_number', 'like', "{$prefix}-{$type}-{$date}-%")
            ->orderByDesc('id')
            ->first();

        $nextSeq = 1;

        if ($lastInvoice) {
            // Extract last 6 digits
            preg_match('/(\d+)$/', $lastInvoice->invoice_number, $matches);
            $nextSeq = isset($matches[1]) ? ((int) $matches[1] + 1) : 1;
        }

        $seqStr = str_pad($nextSeq, 6, '0', STR_PAD_LEFT);

        $invoiceNumber = "{$prefix}-{$type}-{$date}-{$seqStr}";

        Log::debug('[INVOICE] Generated number', [
            'invoice_number' => $invoiceNumber,
        ]);

        return $invoiceNumber;
    }
}


