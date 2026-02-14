<?php

namespace App\Models\Checkout;

use App\Models\Approvals\ApprovalTask;
use App\Models\Checkout\ClearanceFinding;
use App\Models\Finance\Refund;
use App\Models\Finance\ResidentLedger;
use App\Models\Resident;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CheckoutRequest extends Model
{
    protected $fillable = [
        'resident_id',
        'scholar_number',
        'requested_exit_date',
        'description',
        'refund_expected',
        'account_holder',
        'bank_name',
        'account_number',
        'ifsc_code',
        'status',
        'requested_by',
        'actual_exit_date',
        'remarks',
    ];

    protected $casts = [
        'requested_exit_date' => 'datetime',
        'actual_exit_date' => 'datetime',
        'refund_expected'  => 'bool',
    ];

    public function canTransitionTo($newStatus)
    {
        $map = [
            'submitted' => ['in_clearance'],
            'in_clearance' => ['financial_review'],
            'financial_review' => ['payment_pending', 'refund_pending', 'ready_for_exit'],
            'payment_pending' => ['ready_for_exit'],
            'refund_pending' => ['ready_for_exit'],
            'ready_for_exit' => ['completed'],
        ];

        return in_array($newStatus, $map[$this->status] ?? []);
    }

    // if (!$checkout->canTransitionTo('completed')) {
    //     throw new \Exception('Invalid state transition');
    // }

    // public function tasks()
    // {
    //     return $this->hasMany(CheckoutTask::class);
    // }
    public function approvalTasks()
    {
        return $this->morphMany(ApprovalTask::class, 'approvable');
    }

    public function findings()
    {
        return $this->hasMany(ClearanceFinding::class);
    }

    public function ledger()
    {
        return $this->hasMany(ResidentLedger::class, 'checkout_id');
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function refunds()
    {
        return $this->morphMany(Refund::class, 'refundable');
    }



    public function scopeVisibleFor($query, $user)
    {
        if ($user->hasRole('resident')) {
            return $query->where('resident_id', $user->resident->id);
        }

        // if ($user->hasRole('warden')) {
        //     return $query->whereIn('status', [
        //         'submitted',
        //         'in_clearance'
        //     ]);
        // }

        // if ($user->hasRole('accountant')) {
        //     return $query->whereIn('status', [
        //         'financial_review',
        //         'payment_pending',
        //         'refund_pending'
        //     ]);
        // }


        // if ($user->hasRole('admin')) {
        //     return $query->where('status', '!=', 'draft');
        // }

        if ($user->hasAnyRole(['warden', 'accountant', 'admin', 'hod'])) {
            return $query; // See all records
        }

        return $query->whereRaw('1=0');
    }


    // public function resolvePermissions($user)
    // {
    //     $status = $this->status;

    //     return [

    //         'can_view' => true,

    //         'can_edit' =>
    //         $user->hasRole('resident') &&
    //             $status === 'draft',

    //         'can_cancel' =>
    //         $user->hasRole('resident') &&
    //             $status === 'submitted',

    //         'can_approve' =>
    //         $user->hasRole('warden') &&
    //             in_array($status, ['submitted', 'in_clearance']),

    //         'can_process_payment' =>
    //         $user->hasRole('accountant') &&
    //             in_array($status, ['payment_pending', 'refund_pending']),

    //         'can_complete' =>
    //         $user->hasRole('admin') &&
    //             $status === 'approved',
    //     ];
    // }

    public function resolvePermissions($user)
    {
        $pendingTask = $this->approvalTasks()
            ->where('status', 'pending')
            ->orderBy('sequence')
            ->first();

        $canApprove = false;

        if ($pendingTask) {
            $canApprove = in_array(
                $user->getRoleNames()->first(),
                $pendingTask->allowed_roles
            );
        }

        return [

            // Everyone visible can view
            'can_view' => true,

            // Resident actions
            'can_edit' =>
            $user->hasRole('resident') &&
                $this->status === 'draft',

            'can_cancel' =>
            $user->hasRole('resident') &&
                in_array($this->status, ['submitted']),

            // Workflow-based approval
            'can_approve' => $canApprove,

            // Accountant financial action
            'can_process_payment' =>
            $user->hasRole('accountant') &&
                $this->status === 'payment_pending',

            // Admin override power
            'can_force_complete' =>
            $user->hasRole('admin'),
        ];
    }


    // public function getFinancialSummary()
    // {
    //     $resident = $this->resident;

    //     $invoices = $resident->invoices()->financial();

    //     $totalInvoiced = $invoices->sum('total_amount');
    //     $totalPaid     = $invoices->sum('paid_amount');

    //     $balance = $totalInvoiced - $totalPaid;

    //     return [
    //         'total_invoiced' => $totalInvoiced,
    //         'total_paid'     => $totalPaid,
    //         'balance'        => $balance,
    //         'financial_status' => match (true) {
    //             $balance > 0  => 'payment_pending',
    //             $balance < 0  => 'refund_pending',
    //             default       => 'settled',
    //         }
    //     ];
    // }

    // public function getFinancialSummary(): array
    // {
    //     $resident = $this->resident;

    //     if (!$resident) {
    //         return [
    //             'ledger_balance'   => 0,
    //             'deposit_expected' => 0,
    //             'deposit_held'     => 0,
    //             'financial_status' => 'settled',
    //         ];
    //     }

    //     /*
    // |--------------------------------------------------------------------------
    // | 1️⃣ Ledger Balance (Approved Entries Only)
    // |--------------------------------------------------------------------------
    // */

    //     $ledgerBalance = $resident->ledgers()
    //         ->where('status', 'approved')
    //         ->latest('id')
    //         ->value('balance_after') ?? 0;

    //     /*
    // |--------------------------------------------------------------------------
    // | 2️⃣ Deposit From Subscriptions (Expected)
    // |--------------------------------------------------------------------------
    // */

    //     $depositSubscriptions = $resident->subscriptions()
    //         ->where('service_type', 'fee')
    //         ->where('service_name', 'Caution Money')
    //         ->get();

    //     $depositExpected = $depositSubscriptions->sum(function ($sub) {
    //         return ($sub->unit_price ?? 0) * ($sub->quantity ?? 1);
    //     });

    //     /*
    // |--------------------------------------------------------------------------
    // | 3️⃣ Deposit Actually Held (Ledger Verified)
    // |--------------------------------------------------------------------------
    // */

    //     $depositHeld = 0;

    //     if ($depositSubscriptions->isNotEmpty()) {
    //         $depositHeld = $resident->ledgers()
    //             ->where('status', 'approved')
    //             ->where('source_type', 'subscription')
    //             ->whereIn('source_id', $depositSubscriptions->pluck('id'))
    //             ->selectRaw('COALESCE(SUM(credit - debit),0) as net')
    //             ->value('net');
    //     }

    //     /*
    // |--------------------------------------------------------------------------
    // | 4️⃣ Financial Status
    // |--------------------------------------------------------------------------
    // */

    //     $financialStatus = match (true) {
    //         $ledgerBalance > 0  => 'payment_pending',
    //         $ledgerBalance < 0  => 'refund_pending',
    //         default             => 'settled',
    //     };

    //     return [
    //         'ledger_balance'   => (float) $ledgerBalance,
    //         'deposit_expected' => (float) $depositExpected,
    //         'deposit_held'     => (float) $depositHeld,
    //         'financial_status' => $financialStatus,
    //     ];
    // }


    public function workflowProgress()
    {
        return $this->approvalTasks()
            ->orderBy('sequence')
            ->get()
            ->map(function ($task) {

                return [
                    'task' => $task->task_name,
                    'level' => $task->sequence,
                    'role' => $task->allowed_roles,
                    'status' => $task->status,
                    'approved_by' => optional($task->approver)->name,

                    // 'approved_at' => optional($task->approved_at)
                    //     ? optional($task->approved_at)->format('d M Y H:i')
                    //     : null

                    'approved_at' => $task->approved_at?->format('d M Y') ?? null

                    // optional($this->requested_exit_date)->format('d M Y')
                ];
            });
    }



    public function getFinancialSummary(): array
    {
        $resident = $this->resident;

        if (!$resident) {
            return $this->emptyFinancialSummary();
        }

        // 1️⃣ Pending Invoice Dues (Primary Truth)


        $pendingDues = (float) $resident->invoices()
            ->whereIn('status', ['unpaid', 'partial_paid', 'pending'])
            ->sum('total_amount');

        // 2️⃣ Deposit Expected (From Subscription Configuration)


        $depositSubscriptions = $resident->subscriptions()
            ->where('service_type', 'fee')
            ->where('service_name', 'Caution Money')
            ->get();



        $depositExpected = (float) $depositSubscriptions->sum(function ($sub) {
            return ($sub->unit_price ?? 0) * ($sub->quantity ?? 1);
        });

        // 3️⃣ Deposit Paid (Verified via Paid Invoices)


        $depositPaid = 0.0;

        if ($depositSubscriptions->isNotEmpty()) {

            // $depositPaid = (float) $resident->invoices()
            //     ->where('status', 'paid')
            //     ->whereHas('items', function ($query) {
            //         $query->where('item_type', 'fee')
            //         ->where('description', 'Caution Money');
            //     })
            //     ->sum('total_amount');

            $depositPaid = $resident->invoiceItems()
                ->whereHas('invoice', function ($q) {
                    $q->where('status', 'paid');
                })
                ->where('item_type', 'fee')
                ->where('description', 'Caution Money')
                ->get()
                // ->sum(fn($item) => $item->price * $item->quantity);
                ->sum(fn($item) => $item->price);



            // $depositPaid = (float) DB::table('invoice_items')
            //     ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            //     ->where('invoices.resident_id', $resident->id)
            //     ->where('invoices.status', 'paid')
            //     ->where('invoice_items.item_type', 'fee')
            //     ->where('invoice_items.description', 'Caution Money')
            //     // ->sum(DB::raw('invoice_items.price * invoice_items.quantity'));
            //     ->sum(DB::raw('invoice_items.price'));
        }

        //4️⃣ Ledger Snapshot (Accounting View Only)


        $ledgerBalance = (float) ($resident->ledgers()
            ->where('status', 'approved')
            ->latest('id')
            ->value('balance_after') ?? 0);

        // 5️⃣ Net Position Calculation


        $netPosition = $depositPaid - $pendingDues;

        // 6️⃣ Financial Status Mapping (Deterministic)


        $financialStatus = $this->resolveFinancialStatus(
            pendingDues: $pendingDues,
            depositExpected: $depositExpected,
            depositPaid: $depositPaid,
            netPosition: $netPosition
        );

        return [
            'pending_dues'      => $pendingDues,
            'deposit_expected'  => $depositExpected,
            'deposit_paid'      => $depositPaid,
            'ledger_balance'    => $ledgerBalance,
            'net_position'      => $netPosition,
            'financial_status'  => $financialStatus,
        ];
    }

    // public function getFinancialSummary(): array
    // {
    //     $resident = $this->resident;

    //     // 1. Invoice Totals
    //     $totalInvoices = $resident->invoices()->sum('total_amount');
    //     $totalPaid     = $resident->invoices()->sum('paid_amount');

    //     $pendingDues = $totalInvoices - $totalPaid;

    //     // 2. Deposit (from subscriptions or other logic)
    //     $depositExpected = 5000; // Replace with config if needed
    //     $depositPaid = $resident->subscriptions()
    //         ->where('type', 'deposit')
    //         ->sum('amount');

    //     $depositBalance = $depositExpected - $depositPaid;

    //     // 3. Net Position
    //     $netPosition = $depositPaid - $pendingDues;

    //     // 4. Financial Status Resolver
    //     if ($pendingDues > 0) {
    //         $financialStatus = 'payment_pending';
    //     } elseif ($depositBalance > 0) {
    //         $financialStatus = 'deposit_pending';
    //     } elseif ($netPosition > 0) {
    //         $financialStatus = 'refund_pending';
    //     } else {
    //         $financialStatus = 'settled';
    //     }

    //     return [
    //         'total_invoices'   => $totalInvoices,
    //         'total_paid'       => $totalPaid,
    //         'pending_dues'     => $pendingDues,
    //         'deposit_expected' => $depositExpected,
    //         'deposit_paid'     => $depositPaid,
    //         'deposit_balance'  => $depositBalance,
    //         'net_position'     => $netPosition,
    //         'financial_status' => $financialStatus,
    //     ];
    // }



    protected function resolveFinancialStatus(
        float $pendingDues,
        float $depositExpected,
        float $depositPaid,
        float $netPosition
    ): string {

        // 1️⃣ Resident has unpaid invoices
        if ($pendingDues > 0) {
            return 'payment_pending';
        }

        // 2️⃣ Deposit required but not paid
        if ($depositExpected > 0 && $depositPaid <= 0) {
            return 'deposit_pending';
        }

        // 3️⃣ No dues + deposit held → refundable
        if ($depositPaid > 0 && $pendingDues == 0) {
            return 'refund_pending';
        }

        // 4️⃣ Everything cleared
        return 'settled';
    }

    public function getStructuredStatusView(): array
    {
        $financial = $this->getFinancialSummary();
        $workflow  = $this->workflowProgress();

        $stage = $this->resolveCheckoutStage($financial);

        return [
            'stage'       => $stage['key'],
            'label'       => $stage['label'],
            'description' => $stage['description'],
            'severity'    => $stage['severity'],
            'financial_summary' => $financial,
            'workflow'    => $workflow,
            'timeline'    => $this->buildTimeline($financial),
        ];
    }

    protected function resolveCheckoutStage(array $financial): array
    {
        if ($this->status === 'rejected') {
            return [
                'key' => 'rejected',
                'label' => 'Checkout Rejected',
                'description' => 'Your checkout request was rejected.',
                'severity' => 'danger',
            ];
        }

        if ($financial['financial_status'] === 'payment_pending') {
            return [
                'key' => 'financial_clearance',
                'label' => 'Financial Clearance Pending',
                'description' => 'You have pending dues. Please clear them before checkout.',
                'severity' => 'warning',
            ];
        }

        if ($financial['financial_status'] === 'deposit_pending') {
            return [
                'key' => 'deposit_pending',
                'label' => 'Deposit Not Paid',
                'description' => 'Security deposit is pending.',
                'severity' => 'warning',
            ];
        }

        if ($financial['financial_status'] === 'refund_pending') {
            return [
                'key' => 'refund_processing',
                'label' => 'Refund Processing',
                'description' => 'Your refundable amount is being processed.',
                'severity' => 'info',
            ];
        }

        if ($this->status === 'approved') {
            return [
                'key' => 'approved',
                'label' => 'Checkout Approved',
                'description' => 'Your checkout has been approved.',
                'severity' => 'success',
            ];
        }

        return [
            'key' => 'under_review',
            'label' => 'Under Review',
            'description' => 'Your checkout request is under review.',
            'severity' => 'info',
        ];
    }


    protected function buildTimeline(array $financial): array
    {
        return [

            [
                'step' => 'Request Submitted',
                'completed' => true,
                'date' => optional($this->created_at)->format('d M Y H:i'),
            ],

            [
                'step' => 'Department Approval',
                'completed' => $this->status !== 'pending',
            ],

            [
                'step' => 'Financial Clearance',
                'completed' => $financial['financial_status'] === 'settled'
                    || $financial['financial_status'] === 'refund_pending',
            ],

            [
                'step' => 'Final Approval',
                'completed' => $this->status === 'approved',
            ],
        ];
    }
}
