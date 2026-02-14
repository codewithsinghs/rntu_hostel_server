<?php

namespace App\Http\Controllers\ApiV1;

use App\Http\Controllers\Controller;
use App\Models\Approvals\ApprovalTask;
use App\Models\Checkout\CheckoutRequest;
use App\Models\Checkout\ClearanceFinding;
use App\Models\Finance\AuditLog;
use App\Models\Resident;
use App\Models\ResidentLedger;
use FinalCheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutsController extends Controller
{
    // public function index(Request $request)
    // {
    //     try {

    //         $user = auth()->user();

    //         $query = CheckoutRequest::with([
    //             'resident',
    //             'approvalTasks',
    //         ]);

    //         switch ($user->role) {

    //             case 'resident':
    //                 $query->where('resident_id', $user->resident_id);
    //                 break;

    //             case 'warden':
    //                 $query->whereIn('status', [
    //                     'submitted',
    //                     'in_clearance'
    //                 ]);
    //                 break;

    //             case 'accountant':
    //                 $query->whereIn('status', [
    //                     'financial_review',
    //                     'payment_pending',
    //                     'refund_pending'
    //                 ]);
    //                 break;

    //             case 'admin':
    //                 // Admin sees everything except draft
    //                 $query->where('status', '!=', 'draft');
    //                 break;

    //             default:
    //                 return $this->response(null, 'Unauthorized', 403);
    //         }

    //         // Optional filters
    //         if ($request->status) {
    //             $query->where('status', $request->status);
    //         }

    //         $checkouts = $query->latest()->paginate(10);

    //         return $this->response($checkouts, 'Checkout list');
    //     } catch (\Exception $e) {
    //         return $this->errorResponse($e);
    //     }
    // }
    public function index(Request $request)
    {
        try {

            // $user = auth()->user();
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'draw' => intval($request->draw),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'message' => 'Unauthorized access.'
                ], 401);
            }

            $query = CheckoutRequest::with(['resident', 'approvalTasks']);

            /*
        |--------------------------------------------------------------------------
        | Role Based Visibility (Spatie Roles)
        |--------------------------------------------------------------------------
        */

            if ($user->hasRole('resident')) {

                $query->where('resident_id', $user->resident->id);
            } elseif ($user->hasRole('warden')) {

                $query->whereIn('status', [
                    'submitted',
                    'in_clearance'
                ]);
            } elseif ($user->hasRole('accountant')) {

                $query->whereIn('status', [
                    'financial_review',
                    'payment_pending',
                    'refund_pending'
                ]);
            } elseif ($user->hasRole('admin')) {

                $query->where('status', '!=', 'draft');
            } else {

                return response()->json([
                    'draw' => intval($request->draw),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'message' => 'You are not allowed to access checkout data.'
                ], 403);
            }

            /*
        |--------------------------------------------------------------------------
        | Optional Filters
        |--------------------------------------------------------------------------
        */

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            /*
        |--------------------------------------------------------------------------
        | Datatable Searching
        |--------------------------------------------------------------------------
        */

            $search = $request->input('search.value');

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('resident_id', 'like', "%{$search}%")
                        ->where('scholar_number', 'like', "%{$search}%")
                        ->where('requested_exit_date', 'like', "%{$search}%")
                        ->orWhereHas('resident', function ($qr) use ($search) {
                            $qr->where('name', 'like', "%{$search}%");
                        });
                });
            }

            $recordsTotal = CheckoutRequest::count();
            $recordsFiltered = $query->count();

            /*
        |--------------------------------------------------------------------------
        | Ordering
        |--------------------------------------------------------------------------
        */

            $columns = [
                0 => 'id',
                1 => 'scholar_number',
                2 => 'requested_exit_date',
                3 => 'status',
                4 => 'created_at'
            ];

            $orderColumnIndex = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir', 'desc');

            if (isset($columns[$orderColumnIndex])) {
                $query->orderBy($columns[$orderColumnIndex], $orderDirection);
            } else {
                $query->latest();
            }

            /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

            $start = intval($request->start);
            $length = intval($request->length);

            $checkouts = $query->skip($start)
                ->take($length)
                ->get();

            // Log::info('checkouts' . json_encode($checkouts));

            /*
        |--------------------------------------------------------------------------
        | Format Data + Manual Permission Flags
        |--------------------------------------------------------------------------
        */

            $data = $checkouts->map(function ($checkout) use ($user) {

                $status = $checkout->status;

                return [
                    'id' => $checkout->id,
                    'scholar_number' => $checkout->scholar_number ?? optional($checkout->resident)->scholar_no,

                    'requested_exit_date' => $checkout->requested_exit_date->format('d M Y'),
                    'resident_name' => optional($checkout->resident)->name,
                    'course' => optional($checkout->resident)->profile->course,
                    'hostel' => optional($checkout->resident)->hostel->name,
                    'room_number' => optional($checkout->resident)->room->room_number,
                    'bed_number' => optional($checkout->resident)->bed->bed_number,
                    'status' => $status,
                    'created_at' => $checkout->created_at->format('d M Y'),

                    /*
                |--------------------------------------------------------------------------
                | Backend Permission Flags (TRUE/FALSE)
                |--------------------------------------------------------------------------
                */

                    'can_view' => true,

                    'can_edit' =>
                    $user->hasRole('resident') && $status === 'draft' || $status === 'submitted',

                    'can_approve' =>
                    $user->hasRole('warden') &&
                        in_array($status, ['submitted', 'in_clearance']),

                    'can_reject' =>
                    $user->hasRole('warden') &&
                        in_array($status, ['submitted', 'in_clearance']),

                    'can_process_payment' =>
                    $user->hasRole('accountant') &&
                        in_array($status, ['payment_pending', 'refund_pending']),

                    'can_complete' =>
                    $user->hasRole('admin') &&
                        $status === 'approved',
                ];
            });

            /*
        |--------------------------------------------------------------------------
        | Final Datatable Response
        |--------------------------------------------------------------------------
        */

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
                'message' => $data->isEmpty()
                    ? 'No checkout requests found.'
                    : 'Checkout list loaded successfully.'
            ]);
        } catch (\Exception $e) {

            Log::error('Checkout Index Error: ' . $e->getMessage());

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'message' => 'Something went wrong while loading checkout requests. Please try again.'
            ], 500);
        }
    }


    // 1️⃣ Submit Checkout Request
    public function initiate(Request $request)
    {
        Log::info('checkout request', $request->all());

        $user = $request->user(); // Sanctum provides the authenticated user

        DB::beginTransaction();

        try {

            // ✅ Validation
            $validated = $request->validate([
                'resident_id'         => 'required|exists:residents,id',
                'requested_exit_date' => 'required|date|after_or_equal:today',
                'description'         => 'nullable|string|max:500',
                'refund_expected'     => 'nullable|boolean',

                'account_holder'  => 'required_if:refund_expected,1|nullable|string|max:255',
                'bank_name'       => 'required_if:refund_expected,1|nullable|string|max:255',
                'account_number'  => 'required_if:refund_expected,1|nullable|string|max:50',
                'ifsc_code'       => 'required_if:refund_expected,1|nullable|string|max:20',
            ]);

            // ✅ Prevent duplicate active checkout
            $existing = CheckoutRequest::where('resident_id', $validated['resident_id'])
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->exists();

            if ($existing) {
                return $this->response(null, 'Active checkout already exists', 422);
            }

            // ✅ If role = resident, ensure they create only for themselves
            if (auth()->user()->role === 'resident') {
                if (auth()->user()->resident_id != $validated['resident_id']) {
                    return $this->response(null, 'Unauthorized action', 403);
                }
            }

            $checkout = CheckoutRequest::create([
                'resident_id'         => $validated['resident_id'],
                'requested_exit_date' => $validated['requested_exit_date'],
                'description'         => $validated['description'] ?? null,
                'refund_expected'     => $validated['refund_expected'] ?? false,
                'account_holder'      => $validated['account_holder'] ?? null,
                'bank_name'           => $validated['bank_name'] ?? null,
                'account_number'      => $validated['account_number'] ?? null,
                'ifsc_code'           => $validated['ifsc_code'] ?? null,
                'status'              => 'submitted',
                'requested_by'        => auth()->id(),
            ]);

            // ✅ Update resident lifecycle
            // $checkout->resident()->update([
            //     'status' => 'checkout_requested'
            // ]);

            // ✅ Generate approval workflow
            $this->generateApprovalTasks($checkout);

            // ✅ Audit
            AuditLog::create([
                // 'performed_by'  => $request->user->id ?? 'system',
                // 'performed_by' => auth()->id(), // or the relevant
                'performed_by' => $request->user()->id, // Sanctum provides the authenticated user
                'action'        => 'checkout.requested',
                'auditable_type' => CheckoutRequest::class,
                'auditable_id'  => $checkout->id,
                'meta'          => ['resident_id' => $checkout->resident_id],
            ]);

            DB::commit();

            return $this->response($checkout->load('approvalTasks'), 'Checkout request submitted');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e);
        }
    }

    // using Service
    // public function store(Request $request, CheckoutService $service)
    // {
    //     $validated = $request->validate([
    //         'resident_id'         => 'required|exists:residents,id',
    //         'requested_exit_date' => 'required|date|after_or_equal:today',
    //         'description'         => 'nullable|string|max:500',
    //         'refund_expected'     => 'nullable|boolean',

    //         'account_holder'  => 'required_if:refund_expected,1|nullable|string',
    //         'bank_name'       => 'required_if:refund_expected,1|nullable|string',
    //         'account_number'  => 'required_if:refund_expected,1|nullable|string',
    //         'ifsc_code'       => 'required_if:refund_expected,1|nullable|string',
    //     ]);

    //     $this->authorize('create', CheckoutRequest::class);

    //     $checkout = $service->create($validated, auth()->user());

    //     return $this->response($checkout, 'Checkout submitted');
    // }

    // public function show($id)
    // {
    //     try {

    //         $user = auth()->user();

    //         if (!$user) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Unauthorized access.'
    //             ], 401);
    //         }

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Fetch With Visibility Scope
    //     |--------------------------------------------------------------------------
    //     */

    //         $checkout = CheckoutRequest::with([
    //             'resident',
    //             'approvalTasks.approver',
    //             'refunds'
    //         ])
    //             ->visibleFor($user)
    //             ->find($id);

    //         if (!$checkout) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Checkout request not found or access denied.'
    //             ], 404);
    //         }

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Financial Summary
    //     |--------------------------------------------------------------------------
    //     */

    //         $pendingInvoices = $checkout->resident
    //             ->invoices()
    //             // ->where('status', 'unpaid')
    //             ->where('status', ['unpaid', 'pending', 'partial_paid'])
    //             ->sum('total_amount');

    //         $refund = $checkout->refunds()->latest()->first();

    //         $financialSummary = [
    //             'security_deposit' => $checkout->resident->security_deposit ?? 0,
    //             'pending_dues' => $pendingInvoices,
    //             'refund_amount' => $refund->final_amount ?? 0,
    //             'refund_status' => $refund->status ?? null,
    //         ];

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Structured Response
    //     |--------------------------------------------------------------------------
    //     */

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Checkout details loaded successfully.',
    //             'data' => [

    //                 'checkout' => [
    //                     'id' => $checkout->id,
    //                     'request_number' => $checkout->request_number,
    //                     'requested_exit_date' =>
    //                     optional($checkout->requested_exit_date)->format('d M Y'),
    //                     'status' => $checkout->status,
    //                     'description' => $checkout->description,
    //                     'created_at' =>
    //                     $checkout->created_at->format('d M Y H:i'),
    //                 ],

    //                 'resident' => [
    //                     'id' => $checkout->resident->id,
    //                     'name' => $checkout->resident->name,
    //                     'scholar_number' =>
    //                     $checkout->resident->scholar_number,
    //                     'room_number' =>
    //                     optional($checkout->resident->room)->room_number,
    //                 ],

    //                 'workflow' => $checkout->workflowProgress(),

    //                 'financial_summary' => $financialSummary,

    //                 'permissions' => $checkout->resolvePermissions($user),
    //             ]
    //         ]);
    //     } catch (\Exception $e) {

    //         Log::error('Checkout Show Error: ' . $e->getMessage());

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unable to load checkout details. Please try again.'
    //         ], 500);
    //     }
    // }

    //     public function show(Request $request, CheckoutRequest $checkout)
    // {
    //     try {

    //         $user = auth()->user();

    //         // Visibility check
    //         if (!$checkout->visibleFor($user)) {
    //             return $this->response(null, 'You are not authorized to view this checkout.', 403);
    //         }

    //         // Load required relations
    //         $checkout->load([
    //             'resident',
    //             'approvalTasks',
    //             'invoice.items',
    //             'subscriptions',
    //             'refund',
    //         ]);

    //         // Attach dynamic permissions
    //         $checkout->permissions = $checkout->resolvePermissions($user);

    //         return $this->response($checkout, 'Checkout details fetched successfully.');

    //     } catch (\Exception $e) {

    //         return $this->errorResponse($e);
    //     }
    // }

    public function show($id)
    {
        try {

            $user = auth()->user();
            Log::info('users' . json_encode($user));

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 401);
            }

            /*
        |--------------------------------------------------------------------------
        | Fetch With Visibility Scope
        |--------------------------------------------------------------------------
        */

            $checkout = CheckoutRequest::with([
                'resident',
                'approvalTasks.approver',
                // 'refunds'
            ])
                ->visibleFor($user)
                ->find($id);

            if (!$checkout) {
                return response()->json([
                    'success' => false,
                    'message' => 'Checkout request not found or access denied.'
                ], 404);
            }

            $resident = $checkout->resident;

            Log::info('resident' . json_encode($resident));
            /*
        |--------------------------------------------------------------------------
        | Financial Summary (Ledger Driven)
        |--------------------------------------------------------------------------
        */

            // // 1️⃣ Current Ledger Balance (Approved Only)
            // $ledgerBalance = $resident->ledgers()
            //     ->where('status', 'approved')
            //     ->latest('id')
            //     ->value('balance_after') ?? 0;

            // Log::info('ledgerBalance' . json_encode($ledgerBalance));
            // // 2️⃣ Get Caution Money Subscription IDs
            // $depositSubscriptionIds = $resident->subscriptions()
            //     ->where('service_type', 'fee')
            //     ->where('service_name', 'Caution Money')
            //     ->pluck('id');
            // $deposit = $checkout->resident
            //     ->subscriptions()
            //     ->where('service_type', 'fee')
            //     ->where('service_name', 'Caution Money')
            //     ->where('status', 'active')
            //     ->get()
            //     ->sum(function ($sub) {
            //         return $sub->unit_price * $sub->quantity;
            //     });


            // Log::info('depositSubscriptionIds' . json_encode($depositSubscriptionIds));
            // // 3️⃣ Deposit Actually Held (Ledger Verified)
            // $depositHeld = 0;

            // if ($depositSubscriptionIds->isNotEmpty()) {
            //     $depositHeld = $resident->ledgers()
            //         ->where('status', 'approved')
            //         ->where('source_type', 'subscription')
            //         ->whereIn('source_id', $depositSubscriptionIds)
            //         ->selectRaw('SUM(credit - debit) as net')
            //         ->value('net') ?? 0;
            // }


            $financialSummary = $checkout->getFinancialSummary();

            $statusView = $checkout->getStructuredStatusView();


            // 4️⃣ Latest Refund (if any)
            // $refund = $checkout->refunds()->latest()->first();

            /*
        |--------------------------------------------------------------------------
        | Financial Status Mapping
        |--------------------------------------------------------------------------
        */

            // $financialStatus = match (true) {
            //     $ledgerBalance > 0  => 'payment_pending',
            //     $ledgerBalance < 0  => 'refund_pending',
            //     default             => 'settled',
            // };

            // Log::info('financialStatus' . json_encode($financialStatus));
            // $financialSummary = [
            //     'ledger_balance' => $ledgerBalance,
            //     'deposit_held'   => $depositHeld,
            //     'financial_status' => $financialStatus,
            //     // 'latest_refund' => $refund ? [
            //     //     'amount' => $refund->final_amount,
            //     //     'status' => $refund->status,
            //     //     'processed_at' => optional($refund->created_at)->format('d M Y H:i')
            //     // ] : null,
            // ];

            Log::info('financialSummary' . json_encode($financialSummary));
            /*
        |--------------------------------------------------------------------------
        | Structured Response
        |--------------------------------------------------------------------------
        */

            return response()->json([
                'success' => true,
                'message' => 'Checkout details loaded successfully.',
                'data' => [

                    'checkout' => [
                        'id' => $checkout->id,
                        'request_number' => $checkout->request_number,
                        'requested_exit_date' =>
                        optional($checkout->requested_exit_date)->format('d M Y'),
                        'status' => $checkout->status,
                        'description' => $checkout->description,
                        'created_at' =>
                        $checkout->created_at->format('d M Y H:i'),
                    ],

                    'resident' => [
                        'id' => $resident->id,
                        'name' => $resident->name,
                        'scholar_number' => $resident->scholar_no ?? $resident->profile?->scholar_number,
                        'room_number' => optional($resident->room)->room_number,
                    ],

                    'workflow' => $checkout->workflowProgress(),

                    'financial_summary' => $financialSummary,

                    'status_view' => $statusView,
                    // 'permissions' => $checkout->resolvePermissions($user),
                ]
            ]);
        } catch (\Exception $e) {

            Log::error('Checkout Show Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Unable to load checkout details. Please try again.'
            ], 500);
        }
    }



    public function update(Request $request)
    {
        Log::info('checkout update request', $request->all());

        $user = $request->user(); // Sanctum authenticated user

        DB::beginTransaction();

        try {
            // ✅ Validation
            $validated = $request->validate([
                'checkout_id'        => 'required|exists:checkout_requests,id',
                'requested_exit_date' => 'nullable|date|after_or_equal:today',
                'description'        => 'nullable|string|max:500',
                'refund_expected'    => 'nullable|boolean',

                'account_holder'     => 'required_if:refund_expected,1|nullable|string|max:255',
                'bank_name'          => 'required_if:refund_expected,1|nullable|string|max:255',
                'account_number'     => 'required_if:refund_expected,1|nullable|string|max:50',
                'ifsc_code'          => 'required_if:refund_expected,1|nullable|string|max:20',
                'status'             => 'nullable|in:draft,submitted,in_clearance,financial_review,payment_pending,refund_pending,ready_for_exit,completed,cancelled',
            ]);

            // ✅ Fetch checkout
            $checkout = CheckoutRequest::findOrFail($validated['checkout_id']);

            // ✅ Authorization: residents can only update their own request
            if ($user->role === 'resident' && $user->resident_id != $checkout->resident_id) {
                return $this->response(null, 'Unauthorized action', 403);
            }

            // ✅ Apply updates
            $checkout->update([
                'requested_exit_date' => $validated['requested_exit_date'] ?? $checkout->requested_exit_date,
                'description'         => $validated['description'] ?? $checkout->description,
                'refund_expected'     => $validated['refund_expected'] ?? $checkout->refund_expected,
                'account_holder'      => $validated['account_holder'] ?? $checkout->account_holder,
                'bank_name'           => $validated['bank_name'] ?? $checkout->bank_name,
                'account_number'      => $validated['account_number'] ?? $checkout->account_number,
                'ifsc_code'           => $validated['ifsc_code'] ?? $checkout->ifsc_code,
                'status'              => $validated['status'] ?? $checkout->status,
            ]);

            // ✅ Audit log
            AuditLog::create([
                'performed_by'   => $user->id,
                'action'         => 'checkout.updated',
                'auditable_type' => CheckoutRequest::class,
                'auditable_id'   => $checkout->id,
                'meta'           => ['resident_id' => $checkout->resident_id],
            ]);

            DB::commit();

            return $this->response($checkout->fresh('approvalTasks'), 'Checkout request updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e);
        }
    }


    // 2️⃣ Generate Approval Tasks
    protected function generateApprovalTasks($checkout)
    {
        $workflow = [
            // ['key' => 'warden_clearance', 'name' => 'Warden Clearance', 'role' => 'warden', 'sequence' => 1],
            ['key' => 'warden_clearance', 'name' => 'Warden Clearance', 'roles' => ['warden'], 'sequence' => 1],
            ['key' => 'accounts_settlement', 'name' => 'Accounts Settlement', 'roles' => ['accountant'], 'sequence' => 2],
            ['key' => 'admin_approval', 'name' => 'Admin Final Approval', 'roles' => ['warden', 'admin'], 'sequence' => 3],
        ];

        foreach ($workflow as $step) {
            ApprovalTask::create([
                'approvable_type' => CheckoutRequest::class,
                'approvable_id'   => $checkout->id,
                'task_key'        => $step['key'],
                'task_name'       => $step['name'],
                'department'      => implode(',', $step['roles']), // store actual role names
                'allowed_roles'   => $step['roles'],               // keep array for logic
                'sequence'        => $step['sequence'],
            ]);
        }
    }

    // 3️⃣ Approve Task
    // public function approveTask(Request $request, $taskId)
    public function approve(Request $request, $taskId)
    {
        DB::beginTransaction();

        try {

            $task = ApprovalTask::findOrFail($taskId);

            // Ensure previous sequence approved
            $previousPending = ApprovalTask::where('approvable_type', $task->approvable_type)
                ->where('approvable_id', $task->approvable_id)
                ->where('sequence', '<', $task->sequence)
                ->where('status', '!=', 'approved')
                ->exists();

            if ($previousPending) {
                return $this->response(null, 'Previous approvals pending', 422);
            }

            $task->update([
                'status'      => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'remarks'     => $request->remarks
            ]);

            $checkout = CheckoutRequest::find($task->approvable_id);

            // If last approval → ready_for_exit
            $remaining = ApprovalTask::where('approvable_type', CheckoutRequest::class)
                ->where('approvable_id', $checkout->id)
                ->where('status', '!=', 'approved')
                ->count();

            if ($remaining == 0) {
                $checkout->update(['status' => 'ready_for_exit']);
            }

            AuditLog::create([
                'performed_by' => auth()->id(),
                'action'       => 'checkout.task_approved',
                'auditable_type' => ApprovalTask::class,
                'auditable_id' => $task->id,
            ]);

            DB::commit();

            return $this->response($task, 'Task approved successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e);
        }
    }

    // 4️⃣ Convert Approved Findings → Ledger
    public function settleFinance($checkoutId)
    {
        DB::beginTransaction();

        try {

            $checkout = CheckoutRequest::findOrFail($checkoutId);

            $approvedFindings = ClearanceFinding::where('source_type', CheckoutRequest::class)
                ->where('source_id', $checkout->id)
                ->where('status', 'approved')
                ->get();

            $resident = Resident::findOrFail($checkout->resident_id);

            $balance = $resident->ledger()->latest()->value('balance_after') ?? 0;

            foreach ($approvedFindings as $finding) {

                $balance += $finding->amount;

                ResidentLedger::create([
                    'resident_id'   => $resident->id,
                    'source_type'   => ClearanceFinding::class,
                    'source_id'     => $finding->id,
                    'document_no'   => 'CHK-' . $checkout->id,
                    'document_date' => now(),
                    'description'   => $finding->category,
                    'debit'         => $finding->amount,
                    'credit'        => 0,
                    'balance_after' => $balance,
                    'type'          => 'damage',
                    'created_by'    => auth()->id(),
                    'approved_by'   => auth()->id(),
                    'approved_at'   => now(),
                ]);
            }

            $checkout->update(['status' => 'financial_settlement']);

            DB::commit();

            return $this->response(null, 'Financial settlement completed');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e);
        }
    }

    // 5️⃣ Final Exit (Bed revoke + complete)
    public function finalExit($checkoutId)
    {
        DB::beginTransaction();

        try {

            $checkout = CheckoutRequest::findOrFail($checkoutId);

            if ($checkout->status !== 'ready_for_exit') {
                return $this->response(null, 'Not ready for final exit', 422);
            }

            $resident = Resident::findOrFail($checkout->resident_id);

            // Revoke bed
            $resident->bedAllocation()->update([
                'status' => 'vacated',
                'vacated_at' => now()
            ]);

            $checkout->update([
                'status' => 'completed',
                'actual_exit_date' => now()
            ]);

            AuditLog::create([
                'performed_by' => auth()->id(),
                'action'       => 'checkout.final_exit',
                'auditable_type' => CheckoutRequest::class,
                'auditable_id' => $checkout->id,
            ]);

            DB::commit();

            return $this->response(null, 'Resident exited successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e);
        }
    }

    // 6️⃣ Smart API + Web Response Handler
    protected function response($data, $message, $code = 200)
    {
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data'    => $data
            ], $code);
        }

        return redirect()->back()->with('success', $message);
    }

    protected function errorResponse($e)
    {
        if (request()->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }

        return redirect()->back()->with('error', $e->getMessage());
    }

    // Extra as per final Checkout Service
    public function approveal(ApprovalTask $task)
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

    public function clearanceData($id)
    {
        $checkout = CheckoutRequest::with('resident.subscriptions')->findOrFail($id);

        // $subscriptions = $checkout->resident->subscriptions;
        $subscriptions = $checkout->resident->subscriptions()->whereIn('status', ['active'])->whereNotIn('service_type', ['fee'])->get();

        return response()->json([
            'checkout_id' => $checkout->id,
            'resident' => [
                'name' => $checkout->resident->name,
                'scholar_no' => $checkout->resident->scholar_no,
                'room' => $checkout->resident->room->room_number
            ],
            'subscriptions' => $subscriptions->map(function ($sub) {
                return [
                    'id' => $sub->id,
                    'service_id' => $sub->invoice_item_id,
                    // 'service_type' => $sub->service_type,
                    'service_name' => $sub->service_name,
                    'service_upto' => optional($sub->end_date)->format('d M Y H i A'),
                    // 'status' => $sub->status,
                    'refundable' => $sub->refundable_amount ?? 0
                ];
            })
        ]);
    }

    // public function submitClearance(Request $request)
    // {
    //     Log::info('clearance submission', $request->all());
    //     DB::transaction(function () use ($request) {

    //         foreach ($request->items as $subscriptionId => $item) {

    //             ClearanceFinding::updateOrCreate(
    //                 [
    //                     'source_type' => CheckoutRequest::class,
    //                     'source_id' => $request->checkout_id,
    //                     'item' => $subscriptionId
    //                 ],
    //                 [
    //                     'category' => 'subscription',
    //                     'amount' => $item['amount'] ?? 0,
    //                     'remarks' => $item['remarks'] ?? null,
    //                     'status' => $item['status'],
    //                     'created_by' => auth()->id()
    //                 ]
    //             );
    //         }

    //         // DO NOT approve task yet if you want reversible
    //         if ($request->action === 'approved' || $request->action === 'rejected') {

    //             ApprovalTask::where('approvable_id', $request->checkout_id)
    //                 ->where('task_key', 'warden_clearance')
    //                 ->update([
    //                     'status' => $request->action,
    //                     'approved_by' => auth()->id(),
    //                     'approved_at' => now()
    //                 ]);
    //         }
    //     });

    //     return response()->json(['success' => true]);
    // }

    // public function submitClearance(Request $request)
    // {
    //     DB::beginTransaction();

    //     try {

    //         $checkout = CheckoutRequest::findOrFail($request->checkout_id);

    //         // 1️⃣ Save Findings
    //         foreach ($request->items as $subscriptionId => $item) {

    //             ClearanceFinding::updateOrCreate(
    //                 [
    //                     'source_type' => CheckoutRequest::class,
    //                     'source_id'   => $checkout->id,
    //                     'item'        => $subscriptionId
    //                 ],
    //                 [
    //                     'category'   => 'subscription',
    //                     'amount'     => $item['amount'] ?? 0,
    //                     'remarks'    => $item['remarks'] ?? null,
    //                     'status'     => $item['status'],
    //                     'created_by' => auth()->id()
    //                 ]
    //             );
    //         }

    //         // 2️⃣ Handle Approval Action
    //         if (in_array($request->action, ['approved', 'rejected'])) {

    //             $task = ApprovalTask::where('approvable_type', CheckoutRequest::class)
    //                 ->where('approvable_id', $checkout->id)
    //                 ->where('task_key', 'warden_clearance')
    //                 ->firstOrFail();

    //             // Ensure previous sequence approved
    //             $previousPending = ApprovalTask::where('approvable_type', CheckoutRequest::class)
    //                 ->where('approvable_id', $checkout->id)
    //                 ->where('sequence', '<', $task->sequence)
    //                 ->where('status', '!=', 'approved')
    //                 ->exists();

    //             if ($previousPending) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Previous approvals pending'
    //                 ], 422);
    //             }

    //             $task->update([
    //                 'status'      => $request->action,
    //                 'approved_by' => auth()->id(),
    //                 'approved_at' => now()
    //             ]);

    //             // 3️⃣ If rejected → mark checkout rejected
    //             if ($request->action === 'rejected') {
    //                 $checkout->update(['status' => 'rejected']);
    //             }

    //             // 4️⃣ If all approved → ready_for_exit
    //             // if ($request->action === 'approved') {

    //             //     $remaining = ApprovalTask::where('approvable_type', CheckoutRequest::class)
    //             //         ->where('approvable_id', $checkout->id)
    //             //         ->where('status', '!=', 'approved')
    //             //         ->count();

    //             //     if ($remaining === 0) {
    //             //         $checkout->update(['status' => 'ready_for_exit']);
    //             //     }
    //             // }
    //             if ($request->action === 'approved') {

    //                 $task = ApprovalTask::where('approvable_type', CheckoutRequest::class)
    //                     ->where('approvable_id', $checkout->id)
    //                     ->where('task_key', 'warden_clearance')
    //                     ->firstOrFail();

    //                 // Ensure previous sequence approved
    //                 $previousPending = ApprovalTask::where('approvable_type', CheckoutRequest::class)
    //                     ->where('approvable_id', $checkout->id)
    //                     ->where('sequence', '<', $task->sequence)
    //                     ->where('status', '!=', 'approved')
    //                     ->exists();

    //                 if ($previousPending) {
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'Previous approvals pending'
    //                     ], 422);
    //                 }

    //                 $task->update([
    //                     'status'      => 'approved',
    //                     'approved_by' => auth()->id(),
    //                     'approved_at' => now()
    //                 ]);

    //                 // ✅ IMPORTANT PART
    //                 $checkout->update([
    //                     'status' => 'in_clearance'
    //                 ]);
    //             }


    //             // 5️⃣ Audit Log
    //             AuditLog::create([
    //                 'performed_by'   => auth()->id(),
    //                 'action'         => 'checkout.warden_clearance_' . $request->action,
    //                 'auditable_type' => ApprovalTask::class,
    //                 'auditable_id'   => $task->id,
    //             ]);
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Clearance processed successfully'
    //         ]);
    //     } catch (\Exception $e) {

    //         DB::rollBack();
    //         return $this->errorResponse($e);
    //     }
    // }

    public function submitClearance(Request $request)
    {
        DB::beginTransaction();

        try {

            // Basic validation (safe guard)
            $request->validate([
                'checkout_id' => 'required|exists:checkout_requests,id',
                'items'       => 'required|array',
                'action'      => 'nullable|in:approved,rejected'
            ]);

            $checkout = CheckoutRequest::findOrFail($request->checkout_id);

            /*
        |--------------------------------------------------------------------------
        | 1️⃣ Save Clearance Findings (Always Allowed)
        |--------------------------------------------------------------------------
        */

            foreach ($request->items as $subscriptionId => $item) {

                ClearanceFinding::updateOrCreate(
                    [
                        'source_type' => CheckoutRequest::class,
                        'source_id'   => $checkout->id,
                        'item'        => $subscriptionId
                    ],
                    [
                        'category'   => 'subscription',
                        'amount'     => $item['amount'] ?? 0,
                        'remarks'    => $item['remarks'] ?? null,
                        'status'     => $item['status'] ?? 'pending',
                        'created_by' => auth()->id()
                    ]
                );
            }

            /*
        |--------------------------------------------------------------------------
        | 2️⃣ If Only Saving (No Approve / Reject)
        |--------------------------------------------------------------------------
        */

            if (!$request->action) {
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Clearance findings saved successfully.'
                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | 3️⃣ Get Current Pending Task (No Hardcode)
        |--------------------------------------------------------------------------
        */

            $task = ApprovalTask::where('approvable_type', CheckoutRequest::class)
                ->where('approvable_id', $checkout->id)
                ->where('status', 'pending')
                ->orderBy('sequence')
                ->first();

            if (!$task) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No pending approval task found or process already completed.'
                ], 422);
            }

            /*
        |--------------------------------------------------------------------------
        | 4️⃣ Ensure Previous Sequence Is Approved
        |--------------------------------------------------------------------------
        */

            $previousPending = ApprovalTask::where('approvable_type', CheckoutRequest::class)
                ->where('approvable_id', $checkout->id)
                ->where('sequence', '<', $task->sequence)
                ->where('status', '!=', 'approved')
                ->exists();

            if ($previousPending) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Previous approval steps are still pending.'
                ], 422);
            }

            /*
        |--------------------------------------------------------------------------
        | 5️⃣ Update Task Status
        |--------------------------------------------------------------------------
        */

            $task->update([
                'status'      => $request->action,
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);

            /*
        |--------------------------------------------------------------------------
        | 6️⃣ Sync Checkout Status (NO HARDCODE)
        |--------------------------------------------------------------------------
        */

            if ($request->action === 'rejected') {

                $checkout->update([
                    'status' => 'rejected'
                ]);
            } else {

                // If first approval completed → move to in_clearance
                $firstSequence = ApprovalTask::where('approvable_type', CheckoutRequest::class)
                    ->where('approvable_id', $checkout->id)
                    ->min('sequence');

                if ($task->sequence == $firstSequence) {
                    $checkout->update([
                        'status' => 'in_clearance'
                    ]);
                }

                // If all tasks approved → ready_for_exit
                $remaining = ApprovalTask::where('approvable_type', CheckoutRequest::class)
                    ->where('approvable_id', $checkout->id)
                    ->where('status', '!=', 'approved')
                    ->count();

                if ($remaining === 0) {
                    $checkout->update([
                        'status' => 'ready_for_exit'
                    ]);
                }
            }

            /*
        |--------------------------------------------------------------------------
        | 7️⃣ Audit Log
        |--------------------------------------------------------------------------
        */

            AuditLog::create([
                'performed_by'   => auth()->id(),
                'action'         => 'checkout.task_' . $request->action,
                'auditable_type' => ApprovalTask::class,
                'auditable_id'   => $task->id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $request->action === 'approved'
                    ? 'Clearance approved successfully.'
                    : 'Clearance rejected successfully.'
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error('Clearance Submission Error', [
                'error' => $e->getMessage(),
                'checkout_id' => $request->checkout_id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again or contact administrator.'
            ], 500);
        }
    }

    
}
