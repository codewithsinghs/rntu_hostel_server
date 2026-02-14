<?php

use App\Http\Controllers\Controller;
use App\Models\Approvals\ApprovalTask;
use Illuminate\Support\Facades\DB;


class CheckoutRequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $tasks = ApprovalTask::with('approvable')
            ->whereJsonContains('allowed_roles', $user->role)
            ->where('status', 'pending')
            ->orderBy('sequence_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'requested_exit_date' => 'required|date|after:today',
            'description' => 'nullable|string',
            'refund_expected' => 'nullable|boolean',
            'account_holder' => 'nullable|required_if:refund_expected,1|string',
            'bank_name' => 'nullable|required_if:refund_expected,1|string',
            'account_number' => 'nullable|required_if:refund_expected,1|string',
            'ifsc_code' => 'nullable|required_if:refund_expected,1|string',
        ]);

        DB::beginTransaction();

        try {

            $checkout = CheckoutRequest::create([
                ...$validated,
                'status' => 'submitted',
                'requested_by' => auth()->id(),
            ]);

            app(ApprovalWorkflowService::class)->generate($checkout);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Checkout request submitted successfully'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    // public function approve(ApprovalTask $task)
    // {
    //     if (!in_array(auth()->user()->role, $task->allowed_roles)) {
    //         return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    //     }

    //     $task->update([
    //         'status' => 'approved',
    //         'approved_by' => auth()->id(),
    //         'approved_at' => now(),
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Approved successfully'
    //     ]);
    // }

    public function approve(ApprovalTask $task)
    {
        DB::beginTransaction();

        try {

            if (!in_array(auth()->user()->role, $task->allowed_roles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Ensure previous approvals completed
            $previousPending = ApprovalTask::where('approvable_id', $task->approvable_id)
                ->where('sequence_order', '<', $task->sequence_order)
                ->where('status', '!=', 'approved')
                ->exists();

            if ($previousPending) {
                return response()->json([
                    'success' => false,
                    'message' => 'Previous approval pending'
                ], 422);
            }

            $task->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // 🔥 Check if this was final step
            $remaining = ApprovalTask::where('approvable_id', $task->approvable_id)
                ->where('status', 'pending')
                ->exists();

            if (!$remaining) {
                app(FinalCheckoutService::class)
                    ->process($task->approvable);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Approved successfully'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Approval failed'
            ], 500);
        }
    }
}
