<?php

namespace App\Http\Controllers\ApiV1;

use Throwable;
use App\Models\Leave;
use App\Models\Faculty;
use App\Models\Building;
use App\Models\University;
use Illuminate\Support\Str;
use App\Models\LeaveRequest;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Transformers\LeaveTransformer;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class LeaveController extends Controller
{

    use ApiResponses;

    /**
     * Apply Sanctum + Web middleware
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except(['index']);
    }

    /* =====================================================
     * COMMON ERROR HANDLER (WEB + API)
     * ===================================================== */
    private function handleWebOrApiError(
        Request $request,
        string $message,
        int $status = 500
    ) {
        if ($request->expectsJson()) {
            return $this->error($message, [], $status);
        }

        return redirect()->back()->with('swal_error', $message);
    }




    public function index(Request $request)
    {
        if (!$request->ajax()) {
            $user = $request->user();
            $buildings = Building::select('id', 'name')
                ->when(
                    $user->building_id,
                    fn($q) => $q->whereIn('id', (array) $user->building_id)
                )
                ->orderBy('name')
                ->get();

            return view('backend.admin.leaves.index', compact('buildings'));
        }

        try {
            $user = $request->user();
            $roles = $user->getRoleNames();

            // ========== BASE QUERY ==========
            $query = Leave::query()
                ->visibleFor($user)
                ->leftJoin('residents', 'residents.id', '=', 'leaves.resident_id')
                ->leftJoin('users', 'users.id', '=', 'residents.user_id')
                ->leftJoin('buildings', 'buildings.id', '=', 'users.building_id')
                ->select([
                    'leaves.id',
                    'leaves.type',
                    'leaves.reason',
                    'leaves.attachment',
                    'leaves.start_date',
                    'leaves.end_date',
                    'leaves.status',
                    'leaves.hod_status',
                    'leaves.hod_remarks',
                    'leaves.hod_action_at',
                    'leaves.admin_status',
                    'leaves.admin_remarks',
                    'leaves.admin_action_at',
                    'leaves.created_at',

                    // Resident fields
                    'residents.name as resident_name',
                    'residents.scholar_no as resident_scholar_no',

                    // User fields
                    'users.email',
                    // 'users.room_number as resident_room_number',
                    // 'users.bed_number as resident_bed_number',

                    // Building field
                    'buildings.name as building_name',
                ]);

            // ========== FILTERS ==========
            // Status filter
            if ($request->filled('status')) {
                $status = $request->status;
                $validStatuses = ['pending', 'approved', 'rejected', 'cancelled'];
                if (in_array($status, $validStatuses)) {
                    $query->where('leaves.status', $status);
                }
            }

            // Building filter
            if ($request->filled('building_id') && is_numeric($request->building_id)) {
                $query->where('users.building_id', (int) $request->building_id);
            }

            // Type filter
            if ($request->filled('type')) {
                $type = strtolower($request->type);
                $validTypes = ['casual', 'medical', 'emergency', 'annual'];
                if (in_array($type, $validTypes)) {
                    $query->where('leaves.type', $type);
                }
            }

            // Date range filters
            if ($request->filled('start_date_from')) {
                $startDateFrom = \Carbon\Carbon::parse($request->start_date_from)->startOfDay();
                $query->where('leaves.start_date', '>=', $startDateFrom);
            }

            if ($request->filled('start_date_to')) {
                $startDateTo = \Carbon\Carbon::parse($request->start_date_to)->endOfDay();
                $query->where('leaves.start_date', '<=', $startDateTo);
            }

            // ========== GLOBAL SEARCH ==========
            if ($search = trim($request->input('search.value'))) {
                $query->where(function ($q) use ($search) {
                    $q->where('leaves.type', 'like', "%{$search}%")
                        ->orWhere('leaves.status', 'like', "%{$search}%")
                        ->orWhere('leaves.reason', 'like', "%{$search}%")
                        ->orWhere('leaves.attachment', 'like', "%{$search}%")
                        ->orWhere('residents.name', 'like', "%{$search}%")
                        ->orWhere('residents.scholar_no', 'like', "%{$search}%")
                        ->orWhere('users.email', 'like', "%{$search}%")
                        ->orWhere('users.room_number', 'like', "%{$search}%")
                        ->orWhere('users.bed_number', 'like', "%{$search}%")
                        ->orWhere('buildings.name', 'like', "%{$search}%");
                });
            }

            // ========== ORDERING ==========
            $orderColumnIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir', 'desc');
            $columnKey = $request->input("columns.$orderColumnIndex.data", 'created_at');

            // Map frontend column keys to database columns
            $orderable = [
                // Leave columns
                'created_at' => 'leaves.created_at',
                'start_date' => 'leaves.start_date',
                'end_date' => 'leaves.end_date',
                'status' => 'leaves.status',
                'type' => 'leaves.type',
                'reason' => 'leaves.reason',
                'attachment' => 'leaves.attachment',
                'hod_status' => 'leaves.hod_status',
                'admin_status' => 'leaves.admin_status',
                'hod_action_at' => 'leaves.hod_action_at',
                'admin_action_at' => 'leaves.admin_action_at',

                // Resident columns
                'resident_name' => 'residents.name',
                'resident_scholar_no' => 'residents.scholar_no',

                // User columns
                'email' => 'users.email',
                'resident_room_number' => 'users.room_number',
                'resident_bed_number' => 'users.bed_number',

                // Building columns
                'building' => 'buildings.name',
            ];

            // ========== PRIORITY ORDERING LOGIC ==========
            $hasExplicitOrder = $request->has('order.0.column') && isset($orderable[$columnKey]);

            if ($hasExplicitOrder) {
                // User clicked to sort by a specific column
                $orderColumn = $orderable[$columnKey];

                // Apply user's sort choice
                $query->orderBy($orderColumn, $orderDir);

                // Add priority ordering as secondary (pending status first)
                $query->orderByRaw("FIELD(leaves.status, 'pending', 'approved', 'rejected', 'cancelled')");

                // Add tie-breaker
                $query->orderBy('leaves.id', 'desc');
            } else {
                // Default priority ordering
                // 1. Latest first (created_at desc)
                $query->orderBy('leaves.created_at', 'desc');

                // 2. Status priority: pending first
                $query->orderByRaw("FIELD(leaves.status, 'pending', 'approved', 'rejected', 'cancelled')");

                // 3. Tie-breaker
                $query->orderBy('leaves.id', 'desc');
            }
            // ========== END ORDERING LOGIC ==========

            // ========== COUNTS ==========
            $recordsTotal = Leave::visibleFor($user)->count();
            $recordsFiltered = (clone $query)->count();

            // ========== PAGINATION ==========
            $records = $query
                ->skip((int) $request->start)
                ->take((int) $request->length)
                ->get();

            // ========== FORMAT RESPONSE ==========
            $formattedLeaves = $records->map(function ($leave) {
                $startDate = $leave->start_date ? \Carbon\Carbon::parse($leave->start_date) : null;
                $endDate = $leave->end_date ? \Carbon\Carbon::parse($leave->end_date) : null;
                $createdAt = $leave->created_at ? $leave->created_at->timezone('Asia/Kolkata') : null;
                $hodActionAt = $leave->hod_action_at ? \Carbon\Carbon::parse($leave->hod_action_at)->timezone('Asia/Kolkata') : null;
                $adminActionAt = $leave->admin_action_at ? \Carbon\Carbon::parse($leave->admin_action_at)->timezone('Asia/Kolkata') : null;

                return [
                    // 'DT_RowId' => 'row_' . $leave->id,
                    'id' => $leave->id,
                    'created_at' => $createdAt ? $createdAt->format('d M Y, h:i A') : null,
                    'created_at_raw' => $leave->created_at ? $leave->created_at->timestamp : null,

                    'resident_name' => $leave->resident_name,
                    'resident_scholar_no' => $leave->resident_scholar_no,
                    'resident_room_number' => $leave->resident_room_number,
                    'resident_bed_number' => $leave->resident_bed_number,

                    'type' => ucfirst($leave->type),
                    'type_raw' => $leave->type,

                    'start_date' => $startDate ? $startDate->timezone('Asia/Kolkata')->format('d M Y') : null,
                    'start_date_raw' => $leave->start_date,

                    'end_date' => $endDate ? $endDate->timezone('Asia/Kolkata')->format('d M Y') : null,
                    'end_date_raw' => $leave->end_date,

                    'status' => $leave->status,
                    'status_raw' => $leave->status,
                    'status_html' => $this->getStatusHtml($leave->status),

                    'reason' => $leave->reason ? ucfirst($leave->reason) : null,
                    'attachment' => $leave->attachment,
                    'duration' => $startDate && $endDate ? $startDate->diffInDays($endDate) + 1 : null,

                    // 'hod_status' => $leave->hod_status,
                    // 'hod_remarks' => $leave->hod_remarks,
                    // 'hod_action_at' => $hodActionAt ? $hodActionAt->format('d M Y, h:i A') : null,

                    'hod_status' => 'approved',
                    'hod_remarks' => 'Auto approved by system',
                    'hod_action_at' =>  null,

                    'hod_action_at_raw' => $leave->hod_action_at,

                    'admin_status' => $leave->admin_status,
                    'admin_remarks' => $leave->admin_remarks,
                    'admin_action_at' => $adminActionAt ? $adminActionAt->format('d M Y, h:i A') : null,
                    'admin_action_at_raw' => $leave->admin_action_at,

                    'email' => $leave->email,
                    'building' => $leave->building_name,
                    // 'building_id' => optional($user->building_id),
                ];
            });

            // Get buildings for filters
            $buildings = Building::select('id', 'name')
                ->when(
                    $user->building_id,
                    fn($q) => $q->whereIn('id', (array) $user->building_id)
                )
                ->orderBy('name')
                ->get();

            // ========== RESPONSE ==========
            return response()->json([
                'draw' => (int) $request->input('draw', 0),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $formattedLeaves,
                'meta' => [
                    'roles' => $roles,
                    'buildings' => $buildings,
                ]
            ]);
        } catch (\Throwable $e) {
            \Log::error('Leave Index Error', [
                'user_id' => optional($request->user())->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'draw' => (int) $request->input('draw', 0),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Unable to load leave applications.',
            ], 500);
        }
    }

    // Helper method for status HTML
    private function getStatusHtml($status)
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'approved' => '<span class="badge bg-success">Approved</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>',
            'cancelled' => '<span class="badge bg-secondary">Cancelled</span>',
        ];

        return $badges[$status] ?? '<span class="badge bg-info">' . ucfirst($status) . '</span>';
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'room_number' => 'required|string|max:50',
            'bed_number'  => 'nullable|string|max:50',
            'type'        => 'required|string|max:50',
            'reason'      => 'required|string|max:1000',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'attachment'  => 'nullable|file|max:2048',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('attachment')) {
                $validated['attachment'] = $request->file('attachment')
                    ->store('leaves', 'public');
            }

            $leave = Leave::create([
                ...$validated,
                'hod_status'   => 'pending',
                'admin_status' => 'pending',
                'status'       => 'pending',
            ]);

            DB::commit();

            return $request->expectsJson()
                ? $this->success('Leave request submitted successfully', $leave)
                : redirect()->back()->with('swal_success', 'Leave request submitted successfully');
        } catch (QueryException $e) {

            DB::rollBack();
            Log::error('Leave Store DB Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Database error while submitting leave'
            );
        } catch (\Throwable $e) {

            DB::rollBack();
            Log::critical('Leave Store Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Leave submission failed'
            );
        }
    }

    // public function show(Request $request, $id)
    // {
    //     try {
    //         $user = $request->user();

    //         $leave = Leave::with('resident')
    //         ->visibleFor($user)
    //             ->findOrFail($id);

    //         // Controlled response structure
    //         $response = [
    //             'id'            => $faculty->id,
    //             'name'          => $faculty->name,
    //             'status'        => $faculty->status,
    //             'created_at'    => $faculty->created_at,

    //             'university_id' => $faculty->university_id,
    //             // 'university'    => $faculty->university
    //             //     ? [
    //             //         'id'   => $faculty->university->id,
    //             //         'name' => $faculty->university->name
    //             //     ]
    //             //     : null
    //             'university_name'  => $faculty->university->name ?? null, // flat
    //         ];

    //         return $request->expectsJson()
    //             ? $this->success('Leave fetched successfully', $leave)
    //             : view('manage.leaves.show', compact('leave'));
    //     } catch (ModelNotFoundException $e) {

    //         return $this->handleWebOrApiError(
    //             $request,
    //             'Leave record not found',
    //             404
    //         );
    //     } catch (\Throwable $e) {

    //         Log::critical('Leave Show Error', ['exception' => $e]);

    //         return $this->handleWebOrApiError(
    //             $request,
    //             'Unable to fetch leave record'
    //         );
    //     }
    // }
    public function show(Request $request, $id)
    {
        try {
            $user = $request->user();

            // Eager load with only required relationships and columns
            $leave = Leave::with([
                'resident:id,user_id,name,scholar_no',
                'resident.user:id,email,building_id',
                // 'resident.user.building:id,name',
                // 'approverHod:id,name,email',
                // 'approverAdmin:id,name,email',
            ])
                ->visibleFor($user)
                ->select([
                    'id',
                    'type',
                    'reason',
                    'description',
                    'attachment',
                    'start_date',
                    'end_date',
                    'status',
                    'hod_status',
                    'hod_remarks',
                    'hod_action_at',
                    'admin_status',
                    'admin_remarks',
                    'admin_action_at',
                    'created_at',
                    'resident_id',
                    'approvals',
                ])
                ->find($id);

            if (!$leave) {
                return $this->error('Leave application not found.', [], 404);
            }

            // If no approvals yet, start with empty array 
            $approvals = $leave->approvals ?? [];

            // Format approvals into a consistent structure 
            $formattedApprovals = collect($approvals)->map(function ($approval) {
                return ['role' => $approval['role'] ?? null, 'status' => $approval['status'] ?? null, 'remarks' => $approval['remarks'] ?? null, 'action_by' => $approval['action_by'] ?? null, 'action_at' => isset($approval['action_at']) ? \Carbon\Carbon::parse($approval['action_at'])->format('d M Y, h:i A') : null,];
            });

            // Find HOD and Admin approvals from JSON

            $hodApproval = collect($approvals)->firstWhere('role', 'Hod'); // or normalize case
            $adminApproval = collect($approvals)->firstWhere('role', 'Admin');

            // Log::info('hod approval', ['hodApproval' => $hodApproval]);
            // Log::info('admin approval', ['adminApproval' => $adminApproval]);

            // Format response
            $data = [
                'id' => $leave->id,
                'type' => ucfirst($leave->type),
                'status' => $leave->status,
                'reason' => $leave->reason,
                'description' => $leave->description,
                'attachment' => $leave->attachment,
                'start_date' => optional($leave->start_date)->format('d M Y'),
                'end_date' => optional($leave->end_date)->format('d M Y'),
                'duration' => $leave->start_date && $leave->end_date
                    ? \Carbon\Carbon::parse($leave->start_date)->diffInDays($leave->end_date) + 1
                    : null,
                'applied_at' => optional($leave->created_at)->timezone('Asia/Kolkata')->format('d M Y, h:i A'),

                // 'hod_approval' => [
                //     'status' => $leave->hod_status,
                //     'remarks' => $leave->hod_remarks,
                //     'action_at' => optional($leave->hod_action_at)->format('d M Y, h:i A'),
                //     'approver' => $leave->approverHod ? $leave->approverHod->only(['id', 'name', 'email']) : null,
                // ],

                // 'admin_approval' => [
                //     'status' => $leave->admin_status,
                //     'remarks' => $leave->admin_remarks,
                //     'action_at' => optional($leave->admin_action_at)->format('d M Y, h:i A'),
                //     'approver' => $leave->approverAdmin ? $leave->approverAdmin->only(['id', 'name', 'email']) : null,
                // ],
                'hod_approval' => [
                    'status'    => $hodApproval['status'] ?? $leave->hod_status ?? 'pending',
                    'remarks'   => $hodApproval['remarks'] ?? $leave->hod_remarks ?? null,
                    'action_at' => isset($hodApproval['action_at'])
                        ? \Carbon\Carbon::parse($hodApproval['action_at'])->format('d M Y, h:i A')
                        : optional($leave->hod_action_at)->format('d M Y, h:i A'),
                    // 'action_by'  => $leave->approverHod ? $leave->approverHod->only(['id','name','email']) : null,
                    'action_by'  => $leave->approverHod ? $leave->approverHod->only(['id', 'name', 'email']) : $hodApproval['action_by'] ?? null,
                ],

                'admin_approval' => [
                    'status'    => $adminApproval['status'] ?? $leave->admin_status ?? 'pending',
                    'remarks'   => $adminApproval['remarks'] ?? $leave->admin_remarks ?? null,
                    'action_at' => isset($adminApproval['action_at'])
                        ? \Carbon\Carbon::parse($adminApproval['action_at'])->format('d M Y, h:i A')
                        : optional($leave->admin_action_at)->format('d M Y, h:i A'),
                    'action_by'  => $leave->approverAdmin ? $leave->approverAdmin->only(['id', 'name', 'email']) : $adminApproval['action_by'] ?? null,
                ],


                'resident' => [
                    'name' => $leave->resident->name,
                    'scholar_no' => $leave->resident->scholar_no,
                    'email' => $leave->resident->user->email,
                    'phone' => $leave->resident->user->phone,
                    'room_number' => $leave->resident->user->room_number,
                    'bed_number' => $leave->resident->user->bed_number,
                    'building' => $leave->resident->user->building->name ?? null,
                ],
            ];

            return response()->json([
                'status' => true,
                'message' => 'Leave application details retrieved successfully.',
                'data' => $data
            ]);
        } catch (\Throwable $e) {
            Log::error('Leave Show Error', [
                'user_id' => optional($request->user())->id,
                'leave_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return $this->error('Unable to load leave application details.', [], 500);
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('update request', $request->all());
        DB::beginTransaction();

        try {
            $user  = $request->user();
            $leave = Leave::lockForUpdate()->findOrFail($id);

            // Normalize roles (case-insensitive, multi-role safe)
            $userRoles = collect($user->getRoleNames() ?? [])
                ->map(fn($r) => strtolower($r))
                ->toArray();

            /*
            |--------------------------------------------------------------------------
            | ACTION MAP (AUTHORITIES ONLY)
            |--------------------------------------------------------------------------
            */
            $actionMap = [

                'hod_approve' => [
                    'roles' => ['hod'],
                    'from'  => fn($l) => $l->hod_status === 'pending',
                    'apply' => fn($l, $d) => [
                        'hod_status'    => 'approved',
                        'hod_remarks'   => $d['remarks'] ?? null,
                        'hod_action_at' => now(),
                    ],
                ],

                'hod_reject' => [
                    'roles' => ['hod'],
                    'from'  => fn($l) => $l->hod_status === 'pending',
                    'apply' => fn($l, $d) => [
                        'hod_status'    => 'rejected',
                        'hod_remarks'   => $d['remarks'],
                        'hod_action_at' => now(),
                        'status'        => 'rejected',
                    ],
                ],

                // 🔧 TEMP BYPASS: admin can act even if HOD pending
                'admin_approve' => [
                    'roles' => ['admin'],
                    'from'  => fn($l) =>
                    in_array($l->hod_status, ['pending', 'approved'])
                        && $l->admin_status === 'pending',

                    'apply' => fn($l, $d) => [
                        'admin_status'    => 'approved',
                        'admin_remarks'   => $d['remarks'] ?? null,
                        'admin_action_at' => now(),
                        'status'          => 'approved',
                    ],
                ],

                'admin_reject' => [
                    'roles' => ['admin'],
                    'from'  => fn($l) =>
                    in_array($l->hod_status, ['pending', 'approved'])
                        && $l->admin_status === 'pending',

                    'apply' => fn($l, $d) => [
                        'admin_status'    => 'rejected',
                        'admin_remarks'   => $d['remarks'],
                        'admin_action_at' => now(),
                        'status'          => 'rejected',
                    ],
                ],
            ];

            /*
            |--------------------------------------------------------------------------
            | AUTHORITY ACTION FLOW
            |--------------------------------------------------------------------------
            */
            // if ($request->filled('action')) {
            $isAuthorityAction = $request->has('action')
                && array_key_exists($request->action, $actionMap);

            if ($isAuthorityAction) {


                // $validated = $request->validate([
                //     'action'     => 'required|string',
                //     'remarks'    => str_contains($request->action, 'reject')
                //         ? 'required|string|max:500'
                //         : 'nullable|string|max:500',
                //     'attachment' => 'nullable|file|max:2048',
                // ]);
                $rules = [
                    'action'     => 'required|string',
                    'attachment' => 'nullable|file|max:2048',
                ];

                if (str_contains($request->action, 'reject') || str_contains($request->action, 'cancel')) {
                    $rules['remarks'] = 'required|string|max:500';
                } else {
                    $rules['remarks'] = 'nullable|string|max:500';
                }

                $validated = $request->validate($rules);

                // abort_unless(isset($actionMap[$validated['action']]), 422, 'Invalid action');
                if (!isset($actionMap[$validated['action']])) {
                    return $this->error(
                        'Invalid action',
                        ['action' => $validated['action']]
                    );
                }


                $config = $actionMap[$validated['action']];

                // Role check (case-insensitive, multi-role safe)
                // abort_unless(
                //     collect($config['roles'])->intersect($userRoles)->isNotEmpty(),
                //     403
                // );
                if (collect($config['roles'])->intersect($userRoles)->isEmpty()) {
                    // throw new LeaveActionException(
                    return $this->error(
                        'Unauthorized action',
                        ['required_roles' => $config['roles']],
                        403
                    );
                }


                // State check
                // abort_if(!$config['from']($leave), 409, 'Invalid leave state');
                if (!$config['from']($leave)) {
                    return $this->error(
                        'Invalid leave state',
                        [
                            'action' => $validated['action'],
                            'hod_status' => $leave->hod_status,
                            'admin_status' => $leave->admin_status,
                            'status' => $leave->status,
                        ],
                        409
                    );
                }


                // Handle attachment (per approval)
                $attachmentPath = null;
                if ($request->hasFile('attachment')) {
                    Log::info('file here');
                    $attachmentPath = $request->file('attachment')
                        ->store('leave-approvals', 'public');
                }

                Log::info('attach path', ['path' => $attachmentPath]);
                // Apply DB updates
                $updates = $config['apply']($leave, $validated);

                /*
                |--------------------------------------------------------------------------
                | IMMUTABLE APPROVAL HISTORY
                |--------------------------------------------------------------------------
                */
                $approvals = $leave->approvals ?? [];

                $status = match (true) {
                    str_contains($validated['action'], 'reject') => 'rejected',
                    str_contains($validated['action'], 'cancel') => 'cancelled',
                    default => 'approved',
                };


                $approvals[] = [
                    'role'        => ucfirst($config['roles'][0]), // hod / admin
                    'action'      => $validated['action'],
                    // 'status'      => str_contains($validated['action'], 'reject')
                    //     ? 'rejected'
                    //     : 'approved',
                    'status' => $status,
                    'remarks'     => $validated['remarks'] ?? null,
                    'attachment'  => $attachmentPath,
                    'action_by'   => $user->name,
                    'action_at'   => now()->toDateTimeString(),
                ];

                $leave->update(array_merge($updates, [
                    'approvals' => $approvals,
                ]));
            }

            /*
            |--------------------------------------------------------------------------
            | APPLICANT SELF-EDIT FLOW
            |--------------------------------------------------------------------------
            */ else {

                // // Applicant can edit ONLY before review starts
                // abort_if(
                //     $leave->hod_status !== 'pending'
                //     || $leave->admin_status !== 'pending',
                //     409,
                //     'Leave cannot be modified after review started'
                // );

                // abort_unless($leave->resident->user_id === $user->id, 403);

                // Applicant can edit ONLY before review starts
                if ($leave->hod_status !== 'pending' || $leave->admin_status !== 'pending') {
                    if ($request->expectsJson()) {
                        return $this->error(
                            'Leave cannot be modified after review started',
                            [],
                            409
                        );
                    }
                    return back()->with('swal_error', 'Leave cannot be modified after review started');
                }

                // Applicant can edit ONLY their own leave
                // but allow Admin or Warden temporarily
                if (
                    $leave->resident->user_id !== $user->id
                    && !$user->hasRole('admin') && !$user->hasRole('warden')
                ) {
                    if ($request->expectsJson()) {
                        return $this->error(
                            'You are not authorized to edit this leave',
                            [],
                            403
                        );
                    }
                    return back()->with('swal_error', 'You are not authorized to edit this leave');
                }


                $validated = $request->validate([
                    'type'        => 'required|string',
                    'reason'      => 'required|string|max:1000',
                    'description'      => 'required|string|max:1000',
                    'start_date'  => 'required|date',
                    'end_date'    => 'required|date|after_or_equal:start_date',
                    'attachment' => 'nullable|file|mimes:jpg,png,pdf,doc,docx|max:5120',
                ]);

                $attachmentPath = null;
                if ($request->hasFile('attachment')) {
                    $attachmentPath = $request->file('attachment')->store('leaveapps', 'public');
                }

                // Build approvals 
                $approvals = $leave->approvals ?? [];
                $approvals[] = ['role' => 'Applicant', 'action' => 'updated', 'remarks' => null, 'attachment' => $attachmentPath, 'action_by' => $user->name, 'action_at' => now()->toDateTimeString(),];

                // Merge everything into one update 
                $leave->update(array_merge(collect($validated)->only(['type', 'reason', 'description', 'start_date', 'end_date',])->toArray(), $attachmentPath ? ['attachment' => $attachmentPath] : [], ['approvals' => $approvals]));
            }

            DB::commit();

            return $request->expectsJson()
                ? $this->success('Leave updated successfully', $leave->fresh())
                : back()->with('swal_success', 'Leave updated successfully');
        } catch (ValidationException $e) {
            DB::rollBack();
            return $request->expectsJson()
                ? $this->error('Validation failed', $e->errors(), 422)
                : back()->withErrors($e->errors())->withInput();
        } catch (ModelNotFoundException $e) {

            DB::rollBack();

            return $request->expectsJson()
                ? $this->error('Leave record not found', [], 404)
                : back()->with('swal_error', 'Leave record not found');
        } catch (QueryException $e) {

            DB::rollBack();

            Log::error('Leave Update DB Error', [
                'leave_id' => $id,
                'user_id'  => optional($request->user())->id,
                'error'    => $e->getMessage(),
            ]);

            return $request->expectsJson()
                ? $this->error('Database error while updating leave', [], 500)
                : back()->with('swal_error', 'Database error while updating leave');
        } catch (Throwable $e) {

            DB::rollBack();

            Log::error('Leave Update Error', [
                'leave_id' => $id,
                'user_id'  => optional($request->user())->id,
                'error'    => $e->getMessage(),
            ]);

            // return $this->handleWebOrApiError(
            //     $request,
            //     'Leave update failed',
            //     500
            // );

            return $request->expectsJson()
                ? $this->error('Leave update failed', ['exception' => $e->getMessage()], 500)
                // ? $this->error('Leave update failed', [], 500)
                : back()->with('swal_error', 'Leave update failed')->withInput();

            //     return $this->error(
            //     $e->getMessage(),
            //     $e->errors(),
            //     $e->status()
            // );
        }
    }


    private function userHasAnyRole($user, array $roles): bool
    {
        $userRoles = collect($user->getRoleNames())
            ->map(fn($r) => strtolower($r));

        return $userRoles->intersect(
            collect($roles)->map(fn($r) => strtolower($r))
        )->isNotEmpty();
    }

    private function state($value): string
    {
        return trim(strtolower((string) $value));
    }
}
