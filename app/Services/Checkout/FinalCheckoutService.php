<?php

use App\Models\Checkout\CheckoutRequest;
use App\Models\Finance\AuditLog;
use App\Models\Finance\Refund;
use App\Models\Invoice;
use App\Models\Resident;
use App\Models\Room;
use App\Models\Subscription;

class FinalCheckoutService
{
    public function process(CheckoutRequest $checkout)
    {
        $resident = Resident::findOrFail($checkout->resident_id);

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ Calculate Pending Dues
        |--------------------------------------------------------------------------
        */

        $pendingAmount = $this->calculatePendingDues($resident);

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ Generate Final Invoice (if dues exist)
        |--------------------------------------------------------------------------
        */

        if ($pendingAmount > 0) {

            Invoice::create([
                'resident_id' => $resident->id,
                'invoice_date' => now(),
                'total_amount' => $pendingAmount,
                'status' => 'unpaid',
                'type' => 'final_settlement'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ Refund Calculation
        |--------------------------------------------------------------------------
        */

        $refundAmount = 0;

        if ($checkout->refund_expected) {

            $refundAmount = $resident->security_deposit - $pendingAmount;

            if ($refundAmount < 0) {
                $refundAmount = 0;
            }

            Refund::create([
                'resident_id' => $resident->id,
                'amount' => $refundAmount,
                'bank_name' => $checkout->bank_name,
                'account_holder' => $checkout->account_holder,
                'account_number' => $checkout->account_number,
                'ifsc_code' => $checkout->ifsc_code,
                'status' => 'pending'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 4️⃣ Update Resident
        |--------------------------------------------------------------------------
        */

        $resident->update([
            'status' => 'checked_out',
            'check_out_date' => $checkout->requested_exit_date
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5️⃣ Release Room
        |--------------------------------------------------------------------------
        */

        if ($resident->room_id) {
            Room::where('id', $resident->room_id)
                ->increment('available_beds');
        }

        /*
        |--------------------------------------------------------------------------
        | 6️⃣ Stop Subscription
        |--------------------------------------------------------------------------
        */

        Subscription::where('resident_id', $resident->id)
            ->update(['status' => 'closed']);

        /*
        |--------------------------------------------------------------------------
        | 7️⃣ Update Checkout Status
        |--------------------------------------------------------------------------
        */

        $checkout->update([
            'status' => 'completed',
            'final_dues' => $pendingAmount,
            'refund_amount' => $refundAmount
        ]);

        /*
        |--------------------------------------------------------------------------
        | 8️⃣ Audit Log
        |--------------------------------------------------------------------------
        */

        AuditLog::create([
            'performed_by' => auth()->id(),
            'action' => 'checkout.completed',
            'auditable_type' => CheckoutRequest::class,
            'auditable_id' => $checkout->id,
            'meta' => [
                'pending_amount' => $pendingAmount,
                'refund_amount' => $refundAmount
            ]
        ]);
    }

    private function calculatePendingDues($resident)
    {
        return Invoice::where('resident_id', $resident->id)
            ->where('status', 'unpaid')
            ->sum('total_amount');
    }
}
