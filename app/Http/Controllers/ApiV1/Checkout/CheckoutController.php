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
use App\Models\Checkout\Approvals\ApprovalTask;
use App\Models\Checkout\CheckoutTask;
use Illuminate\Database\QueryException;
use App\Models\Checkout\CheckoutRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class CheckoutController extends Controller
{
    // public function initiate(Request $request)
    // {
    //     DB::transaction(function () use ($request) {

    //         $checkout = CheckoutRequest::create([
    //             'resident_id' => $request->resident_id,
    //             'requested_by' => auth()->id(),
    //             'requested_exit_date' => $request->exit_date,
    //             'status' => 'submitted'
    //         ]);

    //         foreach (config('checkout.workflow') as $task) {
    //             CheckoutTask::create(array_merge($task, [
    //                 'checkout_id' => $checkout->id
    //             ]));
    //         }

    //         $checkout->update(['status' => 'in_clearance']);
    //     });
    // }



    public function initiate(Request $request)
    {
        try {

            /* -----------------------------
         | 1. Validate request
         |----------------------------- */
            $validated = $request->validate([
                'resident_id' => 'required|exists:residents,id',
                'exit_date'   => 'required|date|after_or_equal:today',
            ]);

            /* -----------------------------
         | 2. Validate workflow config
         |----------------------------- */
            $workflow = config('checkout.workflow');

            if (empty($workflow) || !is_array($workflow)) {
                return $this->error(
                    'Checkout workflow is not configured properly',
                    [],
                    500
                );
            }

            DB::beginTransaction();

            /* -----------------------------
         | 3. Create checkout request
         |----------------------------- */
            $checkout = CheckoutRequest::create([
                'resident_id'         => $validated['resident_id'],
                'requested_by'        => auth()->id(),
                'requested_exit_date' => $validated['exit_date'],
                'status'              => 'submitted',
            ]);

            /* -----------------------------
         | 4. Create workflow tasks
         |----------------------------- */
            foreach ($workflow as $task) {

                if (!isset($task['role']) || !isset($task['order'])) {
                    throw new \Exception('Invalid checkout workflow step');
                }

                ApprovalTask::create([
                    'checkout_id' => $checkout->id,
                    'role'        => $task['role'],
                    'order'       => $task['order'],
                    'status'      => 'pending',
                    'remarks'     => null,
                    'action_at'   => null,
                ]);
            }

            /* -----------------------------
         | 5. Move checkout to clearance
         |----------------------------- */
            $checkout->update([
                'status' => 'in_clearance',
            ]);

            DB::commit();

            /* -----------------------------
         | 6. Success response
         |----------------------------- */
            return $this->success(
                'Checkout request initiated successfully',
                [
                    'checkout_id' => $checkout->id,
                    'status'      => $checkout->status,
                ]
            );
        } catch (ValidationException $e) {

            DB::rollBack();

            return $this->error(
                'Validation failed',
                $e->errors(),
                422
            );
        } catch (\Throwable $e) {

            DB::rollBack();

            /* -----------------------------
         | 7. Log for debugging
         |----------------------------- */
            Log::error('Checkout initiation failed', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'user'  => auth()->id(),
            ]);

            return $this->error(
                'Failed to initiate checkout request',
                [],
                500
            );
        }
    }



    public function complete($id)
    {
        $checkout = CheckoutRequest::with('tasks')->findOrFail($id);

        if ($checkout->status !== 'ready_for_exit') {
            abort(422, 'Checkout not ready');
        }

        DB::transaction(function () use ($checkout) {
            $checkout->update([
                'status' => 'completed',
                'actual_exit_date' => now()
            ]);

            Resident::where('id', $checkout->resident_id)
                ->update(['status' => 'inactive']);
        });
    }
}
