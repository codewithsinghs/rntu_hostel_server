<?php

namespace App\Services\Finance;

use App\Models\Finance\ResidentLedger;
use App\Models\Resident;
use Illuminate\Support\Facades\DB;

class ResidentFinancialService
{
    // public function calculate(Resident $resident)
    // {
    //     $ledgerBalance = $resident->ledgers()
    //         ->selectRaw('SUM(debit - credit) as balance')
    //         ->value('balance') ?? 0;

    //     // $deposit = $resident->subscriptions()
    //     //     ->where('is_deposit', true)
    //     //     ->sum('amount');
    //     $deposit = $resident->subscriptions()
    //         ->where('service_type', 'fee')
    //         ->where('service_name', 'Caution Money')
    //         ->first();

    //     $net = $ledgerBalance - $deposit;

    //     return [
    //         'ledger_balance' => $ledgerBalance,
    //         'deposit' => $deposit,
    //         'net_balance' => $net,
    //         'status' => match (true) {
    //             $net > 0 => 'payment_pending',
    //             $net < 0 => 'refund_pending',
    //             default  => 'settled',
    //         }
    //     ];
    // }


    public function calculate(Resident $resident)
    {
        // 1️⃣ Current Ledger Balance
        $currentBalance = $resident->ledgers()
            ->where('status', 'approved')
            ->latest('id')
            ->value('balance_after') ?? 0;

        // 2️⃣ Deposit Held
        $cautionSubscriptionIds = $resident->subscriptions()
            ->where('service_type', 'fee')
            ->where('service_name', 'Caution Money')
            ->pluck('id');

        $depositHeld = $resident->ledgers()
            ->where('source_type', 'subscription')
            ->whereIn('source_id', $cautionSubscriptionIds)
            ->where('status', 'approved')
            ->selectRaw('SUM(credit - debit) as net')
            ->value('net') ?? 0;

        return [
            'ledger_balance' => $currentBalance,
            'deposit_held'   => $depositHeld,
            'financial_status' => match (true) {
                $currentBalance > 0  => 'payment_pending',
                $currentBalance < 0  => 'refund_pending',
                default              => 'settled',
            }
        ];
    }


}
