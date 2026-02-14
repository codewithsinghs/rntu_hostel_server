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
use App\Services\Finance\LedgerService;
use Illuminate\Database\QueryException;
use App\Models\Checkout\CheckoutRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CheckoutTaskController extends Controller
{
    public function approve($taskId, Request $request)
    {
        $task = CheckoutTask::findOrFail($taskId);

        abort_unless(auth()->user()->hasRole($task->assigned_role), 403);

        DB::transaction(function () use ($task, $request) {
            $task->update([
                'status' => 'approved',
                'remarks' => $request->remarks,
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);

            $this->evaluateCheckout($task->checkout_id);
        });
    }

    private function evaluateCheckout($checkoutId)
    {
        $checkout = CheckoutRequest::with('tasks')->find($checkoutId);

        if ($checkout->tasks->where('status', 'pending')->count() === 0) {
            $checkout->update(['status' => 'ready_for_exit']);
        }
    }


    public function roomInspection(Request $request, $taskId)
    {
        $task = CheckoutTask::findOrFail($taskId);

        if ($request->damage_amount > 0) {
            LedgerService::post([
                'resident_id' => $task->checkout->resident_id,
                'source_type' => 'checkout',
                'source_id' => $task->checkout_id,
                'document_no' => 'CHK-DMG-' . $task->checkout_id,
                'description' => 'Room damage charges',
                'debit' => $request->damage_amount,
                'credit' => 0
            ]);
        }

        $this->approveTask($taskId, 'Room inspected');
    }
}
