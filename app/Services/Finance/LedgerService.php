<?php

namespace App\Services\Finance;

use Illuminate\Support\Facades\DB;
use App\Models\Finance\ResidentLedger;

class LedgerService
{
    public static function post(array $data)
    {
        return DB::transaction(function () use ($data) {

            $lastBalance = ResidentLedger::where('resident_id', $data['resident_id'])
                ->latest()
                ->value('balance_after') ?? 0;

            $balance = $lastBalance + $data['debit'] - $data['credit'];

            return ResidentLedger::create(array_merge($data, [
                'balance_after' => $balance,
                'status' => 'open',
                'document_date' => now(),
                'created_by' => auth()->id()
            ]));
        });
    }
}
