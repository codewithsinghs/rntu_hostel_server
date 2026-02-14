<?php

namespace App\Http\Controllers\ApiV1\Checkout;

use Throwable;
use App\Models\Resident;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Checkout\CheckoutTask;
use App\Models\Finance\ResidentLedger;
use App\Services\Finance\LedgerService;
use Illuminate\Database\QueryException;
use App\Models\Checkout\CheckoutRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CheckoutAccountController extends Controller
{
    public function finalize($checkoutId)
    {
        $checkout = CheckoutRequest::findOrFail($checkoutId);

        $balance = ResidentLedger::where('resident_id', $checkout->resident_id)
            ->where('status', 'open')
            ->sum(DB::raw("
                CASE WHEN direction='debit'
                THEN amount ELSE -amount END
            "));

        abort_if($balance > 0, 422, 'Pending dues exist');

        CheckoutTask::where('checkout_id', $checkoutId)
            ->where('task_key', 'accounts')
            ->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);
    }

    public function finalizeAccounts($checkoutId)
    {
        $checkout = CheckoutRequest::findOrFail($checkoutId);

        $balance = ResidentLedger::where('resident_id', $checkout->resident_id)
            ->latest()
            ->value('balance_after');

        if ($balance > 0) {
            abort(422, 'Resident has pending dues');
        }

        if ($balance < 0) {
            LedgerService::post([
                'resident_id' => $checkout->resident_id,
                'source_type' => 'refund',
                'source_id' => $checkoutId,
                'document_no' => 'REF-' . $checkoutId,
                'description' => 'Deposit refund',
                'debit' => 0,
                'credit' => abs($balance)
            ]);
        }

        CheckoutTask::where('checkout_id', $checkoutId)
            ->where('task_key', 'accounts')
            ->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);
    }
}
