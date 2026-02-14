<?php

namespace App\Models\Finance;

use App\Models\Resident;
use Illuminate\Database\Eloquent\Model;
use App\Models\Checkout\CheckoutRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',

        'security_deposit',
        'deduction_amount',
        'final_refund_amount',
        'refund_mode',
        'transaction_reference',
        'account_holder',
        'bank_name',
        'account_number',
        'ifsc_code',
        'status',
        'processed_at',
        'processed_by',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function checkout()
    {
        return $this->belongsTo(CheckoutRequest::class, 'checkout_request_id');
    }
}


// if ($checkout->refund_expected) {

//     $securityDeposit = $resident->security_deposit;

//     $deduction = $pendingAmount; // dues deducted

//     $finalRefund = $securityDeposit - $deduction;

//     if ($finalRefund < 0) {
//         $finalRefund = 0;
//     }

//     Refund::create([
//         'resident_id' => $resident->id,
//         'checkout_request_id' => $checkout->id,
//         'security_deposit' => $securityDeposit,
//         'deduction_amount' => $deduction,
//         'final_refund_amount' => $finalRefund,
//         'account_holder' => $checkout->account_holder,
//         'bank_name' => $checkout->bank_name,
//         'account_number' => $checkout->account_number,
//         'ifsc_code' => $checkout->ifsc_code,
//         'status' => 'pending'
//     ]);
// }


// Route::post('/refunds/{refund}/process', [RefundController::class, 'process']);

// public function process(Request $request, Refund $refund)
// {
//     $request->validate([
//         'refund_mode' => 'required|string',
//         'transaction_reference' => 'required|string'
//     ]);

//     if ($refund->status !== 'approved') {
//         return response()->json([
//             'success' => false,
//             'message' => 'Refund not approved yet'
//         ], 422);
//     }

//     $refund->update([
//         'refund_mode' => $request->refund_mode,
//         'transaction_reference' => $request->transaction_reference,
//         'status' => 'processed',
//         'processed_at' => now(),
//         'processed_by' => auth()->id(),
//     ]);

//     return response()->json([
//         'success' => true,
//         'message' => 'Refund processed successfully'
//     ]);
// }
