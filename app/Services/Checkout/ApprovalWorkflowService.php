<?php

namespace App\Services\Checkout;

use App\Models\Approvals\ApprovalTask;

class ApprovalWorkflowService
{
    public function generate($checkout)
    {
        // $workflow = [
        //     ['role' => 'warden', 'order' => 1],
        //     ['role' => 'accountant', 'order' => 2],
        //     ['role' => 'warden', 'order' => 3],
        // ];
        $workflow = [
            ['roles' => ['warden'], 'order' => 1],
            ['roles' => ['accountant'], 'order' => 2],
            ['roles' => ['warden', 'admin'], 'order' => 3],
        ];

        foreach ($workflow as $step) {
            ApprovalTask::create([
                'approvable_type' => get_class($checkout),
                'approvable_id'   => $checkout->id,
                // 'assigned_role'   => $step['role'],
                'allowed_roles'   => $step['roles'],
                'sequence_order'  => $step['order'],
                'status'          => 'pending',
            ]);
        }

        $checkout->update(['status' => 'in_clearance']);
    }

    public function approveTask(ApprovalTask $task)
    {
        if (!in_array(auth()->user()->role, $task->allowed_roles)) {
            throw new \Exception('Unauthorized approver.');
        }

        // Ensure previous tasks completed
        $previousPending = ApprovalTask::where('approvable_id', $task->approvable_id)
            ->where('sequence_order', '<', $task->sequence_order)
            ->where('status', '!=', 'approved')
            ->exists();

        if ($previousPending) {
            throw new \Exception('Previous approval not completed.');
        }

        $task->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
    }
}
