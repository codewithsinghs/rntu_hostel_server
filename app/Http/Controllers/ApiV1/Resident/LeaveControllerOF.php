<?php

namespace App\Http\Controllers\ApiV1\Resident;

use Throwable;
use App\Models\Leave;
use App\Helpers\Helper;
use App\Models\Faculty;
use App\Models\Building;
use App\Models\Resident;
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
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
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
        try {
            /** ---------------------------------------------
             * 1️⃣ Resolve Resident (single source of truth)
             * --------------------------------------------*/
            $user = $request->user();
            // Log::info("user: " . json_encode($user));
            // Fallback to Sanctum guard if null
            if (!$user) {
                $user = auth('sanctum')->user();
            }

            $resident = $user->resident;
            // $resident = Resident::where('user_id', $user->id)->first();
            // Log::info("resident user: " . json_encode($resident));
            if (!$resident) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resident not found.',
                    'data' => null
                ], 404);
            }

            /** ---------------------------------------------
             * 2️⃣ Base Query (reusable & extendable)
             * --------------------------------------------*/
            $query = Leave::with(['resident:id,name,email'])
                ->where('resident_id', $resident->id)
                ->latest();

            /** ---------------------------------------------
             * 3️⃣ Optional Filters (future-proof)
             * --------------------------------------------*/
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('from_date') && $request->filled('to_date')) {
                $query->whereBetween('from_date', [
                    $request->from_date,
                    $request->to_date
                ]);
            }

            /** ---------------------------------------------
             * 4️⃣ Pagination (important for residents panel)
             * --------------------------------------------*/
            // $leaveRequests = $query->paginate(
            //     $request->get('per_page', 10)
            // );
            $records = $query
                ->latest() // always newest records first
                ->orderByRaw("status = 'pending' DESC") // pending rows float to the top
                // ->skip((int) $request->start)
                // ->take((int) $request->length)
                ->get();


            /** ---------------------------------------------
             * 5️⃣ Summary (DB-level counts – faster)
             * --------------------------------------------*/
            $summary = Leave::where('resident_id', $resident->id)
                ->selectRaw("
                COUNT(*) as total,
                SUM(status = 'pending') as pending,
                SUM(status = 'approved') as approved,
                SUM(status = 'rejected') as rejected
            ")
                ->first();

            /** ---------------------------------------------
             * 6️⃣ Final Response
             * --------------------------------------------*/
            return $this->success(
                'Leave requests retrieved successfully.',
                [

                    'requests' => $records->map(function ($leave) {
                        return [
                            'id'            => $leave->id,
                            'name'          => $leave->resident->name,
                            'email'          => $leave->resident->email,
                            'type'          => ucfirst($leave->type),
                            'token'          => $leave->token,
                            'reason'        => $leave->reason,
                            'description'        => $leave->description,
                            'start_date'    => optional($leave->start_date)->format('d M Y'),
                            'end_date'      => optional($leave->end_date)->format('d M Y'),
                            'applied_at'    => optional($leave->created_at)
                                ->timezone('Asia/Kolkata')
                                ->format('d M Y, h:i A'),
                            'status'        => $leave->status,
                            // 'hod_status'    => $leave->hod_status,
                            // 'hod_remarks'  => $leave->hod_remarks,
                            // 'hod_action_at'  => $leave->hod_action_at,
                            'hod_status'    => 'approoved',
                            'hod_remarks'  => 'auto approoved',
                            'hod_action_at'  => '',
                            'admin_status'  => $leave->admin_status,
                            'admin_remarks' => $leave->admin_remarks,
                            'admin_action_at' => optional($leave->admin_action_at)->format('d M Y'),
                            'status_html'   => $this->getStatusHtml($leave->status),
                            // ✅ Add QR code as base64 string
                            'qr_code' => $leave->token ? base64_encode(QrCode::format('png')->size(150)->generate(url("/leave/verify/{$leave->token}"))) : null,
                        ];
                    })->toArray(),
                    'summary' => [
                        'total_leaves' => (int) $summary->total,
                        'pending'      => (int) $summary->pending,
                        'approved'     => (int) $summary->approved,
                        'rejected'     => (int) $summary->rejected,
                    ],
                ]
            );
        } catch (\Throwable $e) {

            \Log::error('[LEAVE][RESIDENT][INDEX]', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile()
            ]);

            return $this->error(
                'Failed to retrieve leave requests.',
                ['exception' => $e->getMessage()],
                500
            );
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
        // Log::info('Leave Store Request', ['request' => $request->all()]);
        $user = $request->user();

        $validated = $request->validate([
            // 'resident_id' => 'required|exists:residents,id',
            // 'room_number' => 'required|string|max:50',
            // 'bed_number'  => 'nullable|string|max:50',
            'type'        => 'required|string|max:50',
            'reason'      => 'required|string|max:100',
            'description'      => 'required|string|max:500',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'attachment'  => 'nullable|file|max:2048',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('attachment')) {
                $validated['attachment'] = $request->file('attachment')
                    ->store('leaveapps', 'public');
            }

            $leave = Leave::create([
                ...$validated,
                'application_no' => 'LEAVE-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(5)),
                'resident_id' => $user->resident->id,
                'token'        => Str::uuid()->toString(),
                'room_number' => $user->resident->room->room_number ?? null,
                'bed_number' => $user->resident->bed->bed_number ?? null,
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
        } catch (ValidationException $e) {
            DB::rollBack();
            return $request->expectsJson()
                ? $this->error('Validation failed', $e->errors(), 422)
                : back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {

            // Log::critical('Leave Store Error', ['exception' => $e]);

            DB::rollBack();

            Log::error('Leave Update Error', [
                'user_id'  => optional($request->user())->id,
                'error'    => $e->getMessage(),
            ]);

            return $request->expectsJson()
                ? $this->error('Leave update failed', ['exception' => $e->getMessage()], 500)
                // ? $this->error('Leave update failed', [], 500)
                : back()->with('swal_error', 'Leave update failed')->withInput();
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



    // public function show(Request $request, $id)
    // {
    //     try {
    //         $user = $request->user();

    //         // Eager load with only required relationships and columns
    //         $leave = Leave::with([
    //             'resident:id,user_id,name,scholar_no',
    //             'resident.user:id,email,building_id',
    //             // 'resident.user.building:id,name',
    //             // 'approverHod:id,name,email',
    //             // 'approverAdmin:id,name,email',
    //         ])
    //             ->visibleFor($user)
    //             ->select([
    //                 'id',
    //                 'type',
    //                 'reason',
    //                 'description',
    //                 'attachment',
    //                 'start_date',
    //                 'end_date',
    //                 'status',
    //                 'hod_status',
    //                 'hod_remarks',
    //                 'hod_action_at',
    //                 'admin_status',
    //                 'admin_remarks',
    //                 'admin_action_at',
    //                 'created_at',
    //                 'resident_id',
    //                 'approvals',
    //             ])
    //             ->find($id);

    //         if (!$leave) {
    //             return $this->error('Leave application not found.', [], 404);
    //         }

    //         // If no approvals yet, start with empty array 
    //         $approvals = $leave->approvals ?? [];

    //         // Format approvals into a consistent structure 
    //         $formattedApprovals = collect($approvals)->map(function ($approval) {
    //             return ['role' => $approval['role'] ?? null, 'status' => $approval['status'] ?? null, 'remarks' => $approval['remarks'] ?? null, 'action_by' => $approval['action_by'] ?? null, 'action_at' => isset($approval['action_at']) ? \Carbon\Carbon::parse($approval['action_at'])->format('d M Y, h:i A') : null,];
    //         });

    //         // Find HOD and Admin approvals from JSON

    //         $hodApproval = collect($approvals)->firstWhere('role', 'Hod'); // or normalize case
    //         $adminApproval = collect($approvals)->firstWhere('role', 'Admin');

    //         // Log::info('hod approval', ['hodApproval' => $hodApproval]);
    //         // Log::info('admin approval', ['adminApproval' => $adminApproval]);

    //         // Format response
    //         $data = [
    //             'id' => $leave->id,
    //             'type' => ucfirst($leave->type),
    //             'status' => $leave->status,
    //             'reason' => $leave->reason,
    //             'description' => $leave->description,
    //             'attachment' => $leave->attachment,
    //             'start_date' => optional($leave->start_date)->format('d M Y'),
    //             'end_date' => optional($leave->end_date)->format('d M Y'),
    //             'duration' => $leave->start_date && $leave->end_date
    //                 ? \Carbon\Carbon::parse($leave->start_date)->diffInDays($leave->end_date) + 1
    //                 : null,
    //             'applied_at' => optional($leave->created_at)->timezone('Asia/Kolkata')->format('d M Y, h:i A'),

    //             // 'hod_approval' => [
    //             //     'status' => $leave->hod_status,
    //             //     'remarks' => $leave->hod_remarks,
    //             //     'action_at' => optional($leave->hod_action_at)->format('d M Y, h:i A'),
    //             //     'approver' => $leave->approverHod ? $leave->approverHod->only(['id', 'name', 'email']) : null,
    //             // ],

    //             // 'admin_approval' => [
    //             //     'status' => $leave->admin_status,
    //             //     'remarks' => $leave->admin_remarks,
    //             //     'action_at' => optional($leave->admin_action_at)->format('d M Y, h:i A'),
    //             //     'approver' => $leave->approverAdmin ? $leave->approverAdmin->only(['id', 'name', 'email']) : null,
    //             // ],
    //             'hod_approval' => [
    //                 'status'    => $hodApproval['status'] ?? $leave->hod_status ?? 'pending',
    //                 'remarks'   => $hodApproval['remarks'] ?? $leave->hod_remarks ?? null,
    //                 'action_at' => isset($hodApproval['action_at'])
    //                     ? \Carbon\Carbon::parse($hodApproval['action_at'])->format('d M Y, h:i A')
    //                     : optional($leave->hod_action_at)->format('d M Y, h:i A'),
    //                 // 'action_by'  => $leave->approverHod ? $leave->approverHod->only(['id','name','email']) : null,
    //                 'action_by'  => $leave->approverHod ? $leave->approverHod->only(['id', 'name', 'email']) : $hodApproval['action_by'] ?? null,
    //             ],

    //             'admin_approval' => [
    //                 'status'    => $adminApproval['status'] ?? $leave->admin_status ?? 'pending',
    //                 'remarks'   => $adminApproval['remarks'] ?? $leave->admin_remarks ?? null,
    //                 'action_at' => isset($adminApproval['action_at'])
    //                     ? \Carbon\Carbon::parse($adminApproval['action_at'])->format('d M Y, h:i A')
    //                     : optional($leave->admin_action_at)->format('d M Y, h:i A'),
    //                 'action_by'  => $leave->approverAdmin ? $leave->approverAdmin->only(['id', 'name', 'email']) : $adminApproval['action_by'] ?? null,
    //             ],


    //             'resident' => [
    //                 'name' => $leave->resident->name,
    //                 'scholar_no' => $leave->resident->scholar_no,
    //                 'email' => $leave->resident->user->email,
    //                 'phone' => $leave->resident->user->phone,
    //                 'room_number' => $leave->resident->user->room_number,
    //                 'bed_number' => $leave->resident->user->bed_number,
    //                 'building' => $leave->resident->user->building->name ?? null,
    //             ],
    //         ];

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Leave application details retrieved successfully.',
    //             'data' => $data
    //         ]);
    //     } catch (\Throwable $e) {
    //         Log::error('Leave Show Error', [
    //             'user_id' => optional($request->user())->id,
    //             'leave_id' => $id,
    //             'error' => $e->getMessage(),
    //         ]);

    //         return $this->error('Unable to load leave application details.', [], 500);
    //     }
    // }

    public function show(Request $request, $id)
    {
        try {
            $leave = Leave::with(['resident.course', 'resident.department'])
                ->findOrFail($id);

            return $request->expectsJson()
                ? $this->success('Leave retrieved successfully.', [
                    'id'          => $leave->id,
                    'name'        => $leave->resident->name,
                    'scholar_no'  => $leave->resident->scholar_no,
                    'hostel_name' => $leave->resident->hostel->name ?? null,
                    'room_number' => $leave->resident->room->room_number ?? null,
                    'email'       => $leave->resident->email,
                    'mobile'      => $leave->resident->number ??  $leave->resident->profile->mobile ?? $leave->resident->guest->number,
                    'course'      => $leave->resident->profile->course ?? 'N/A',
                    'department'  => $leave->resident->department->name ?? 'N/A',
                    'type'        => $leave->type,
                    'reason'        => $leave->reason,
                    'description'        => $leave->description,
                    'attachment'  => $leave->attachment
                        ? url(Storage::url($leave->attachment))
                        : null,
                    'start_date'  => optional($leave->start_date)->format('d M Y'),
                    'end_date'    => optional($leave->end_date)->format('d M Y'),
                    'applied_on'  => optional($leave->created_at)->format('d M Y, h:i A'),
                    'status'      => ucfirst($leave->status),
                    'remarks'     => $leave->admin_remarks ?? 'No remarks',
                    'action_at'   => optional($leave->admin_action_at)->format('d M Y, h:i A'),
                    'qr_code'     => $leave->token
                        ? base64_encode(
                            \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                                ->size(150)
                                ->generate(url("/leave/verify/{$leave->token}"))
                        )
                        : null,

                    'hod_status'    => $leave->hod_status,
                    'hod_remarks'  => $leave->hod_remarks,
                    'hod_action_at'  => optional($leave->hod_action_at)->format('d M Y, H:i A'),
                    'admin_status'  => $leave->admin_status,
                    'admin_remarks' => $leave->admin_remarks,
                    'admin_action_at' => optional($leave->admin_action_at)->format('d M Y, H:i A'),
                ])
                : back()->with('swal_success', 'Leave retrieved successfully.');
        } catch (\Throwable $e) {
            \Log::error('[LEAVE][SHOW]', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            return $request->expectsJson()
                ? $this->error('Failed to retrieve leave.', ['exception' => $e->getMessage()], 500)
                : back()->with('swal_error', 'Failed to retrieve leave.');
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

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $user  = $request->user();
            $leave = Leave::lockForUpdate()->findOrFail($id);

            // Normalize roles
            $roles = collect($user->getRoleNames() ?? [])
                ->map(fn($r) => strtolower($r));

            /*
        |----------------------------------------------------------
        | AUTHORIZATION
        |----------------------------------------------------------
        */

            // Applicant can delete only if pending
            if (
                $leave->resident->user_id === $user->id
                && $leave->status !== 'pending'
            ) {
                return $this->error(
                    'Approved or rejected leave cannot be deleted',
                    [],
                    409
                );
            }

            // Non-admin, non-owner cannot delete
            if (
                $leave->resident->user_id !== $user->id
                && !$roles->contains('admin')
            ) {
                return $this->error(
                    'You are not authorized to delete this leave',
                    [],
                    403
                );
            }

            /*
        |----------------------------------------------------------
        | DELETE FILES (SAFE)
        |----------------------------------------------------------
        */

            // 1️⃣ Main leave attachment
            if ($leave->attachment && \Storage::disk('public')->exists($leave->attachment)) {
                \Storage::disk('public')->delete($leave->attachment);
            }

            // 2️⃣ Approval attachments (HOD / Admin / etc)
            foreach ($leave->approvals ?? [] as $approval) {
                if (
                    !empty($approval['attachment'])
                    && \Storage::disk('public')->exists($approval['attachment'])
                ) {
                    Storage::disk('public')->delete($approval['attachment']);
                }
            }

            /*
        |----------------------------------------------------------
        | DELETE RECORD
        |----------------------------------------------------------
        */
            $leave->delete();

            DB::commit();

            return $request->expectsJson()
                ? $this->success('Leave deleted successfully')
                : back()->with('swal_success', 'Leave deleted successfully');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return $request->expectsJson()
                ? $this->error('Leave not found', [], 404)
                : back()->with('swal_error', 'Leave not found');
        } catch (QueryException $e) {
            DB::rollBack();

            Log::error('Leave Delete DB Error', [
                'leave_id' => $id,
                'user_id'  => optional($request->user())->id,
                'error'    => $e->getMessage(),
            ]);

            return $request->expectsJson()
                ? $this->error('Database error while deleting leave', [], 500)
                : back()->with('swal_error', 'Database error');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Leave Delete Error', [
                'leave_id' => $id,
                'user_id'  => optional($request->user())->id,
                'error'    => $e->getMessage(),
            ]);

            return $request->expectsJson()
                ? $this->error('Failed to delete leave', [], 500)
                : back()->with('swal_error', 'Failed to delete leave');
        }
    }

    public function verifyPage($token)
    {
        $leave = Leave::where('token', $token)->first();

        if (!$leave) {
            return view('leave.verify', [
                'error' => 'Invalid or expired token.'
            ]);
        }

        return view('leave.verify', [
            'data' => [
                'name'      => $leave->resident->name,
                'status'    => ucfirst($leave->status),
                'remarks'   => $leave->admin_remarks ?? 'No remarks',
                'action_at' => optional($leave->admin_action_at)->format('d M Y, h:i A'),
            ]
        ]);
    }
}
