<?php

namespace App\Models\Approvals;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Checkout\CheckoutRequest;

class ApprovalTask extends Model
{
    protected $table = 'approval_tasks';

    protected $fillable = [
        // 'checkout_id',
        'approvable_type',
        'approvable_id',
        'task_key',
        'task_name',
        'department',
        'assigned_role',
        'sequence',
        'status',
        'remarks',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // protected $fillable = [
    //     'checkout_id',
    //     'role',
    //     'order',
    //     'status',
    //     'approved_by',
    //     'approved_at',
    //     'remarks',
    // ];

    /* =====================
     | Relationships
     ===================== */

    public function approvable()
    {
        return $this->morphTo();
    }


    public function checkout()
    {
        return $this->belongsTo(CheckoutRequest::class, 'checkout_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /* =====================
     | Scopes
     ===================== */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /* =====================
     | Domain helpers
     ===================== */

    public function approve(int $userId, ?string $remarks = null): void
    {
        $this->update([
            'status'      => 'approved',
            'approved_by' => $userId,
            'approved_at' => now(),
            'remarks'     => $remarks,
        ]);
    }


    public function workflowProgress()
    {
        return $this->approvalTasks()
            ->orderBy('sequence_order')
            ->get()
            ->map(function ($task) {

                return [
                    'task' => $task->task_name,
                    'level' => $task->sequence,
                    'role' => $task->allowed_roles,
                    'status' => $task->status,
                    'approved_by' => optional($task->approver)->name,
                    'approved_at' => optional($task->approved_at)
                        ? $task->approved_at->format('d M Y H:i')
                        : null
                ];
            });
    }
}
