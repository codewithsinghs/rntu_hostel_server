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


    // public function index(Request $request)
    // {
    //     try {
    //         $user = $request->user();

    //         $roles = $user->getRoleNames(); // returns a collection of role names

    //         // DataTables params
    //         $start  = (int) $request->input('start', 0);
    //         $length = (int) $request->input('length', 10);
    //         $search = $request->input('search.value');

    //         $orderColumnIndex = $request->input('order.0.column', 0);
    //         $orderDir = $request->input('order.0.dir', 'desc');

    //         // Whitelist allowed order columns (VERY IMPORTANT)
    //         $allowedOrderColumns = [
    //             'created_at',
    //             'start_date',
    //             'end_date',
    //             'status',
    //             'type'
    //         ];

    //         $orderColumn = $request->input("columns.$orderColumnIndex.data", 'created_at');
    //         if (!in_array($orderColumn, $allowedOrderColumns)) {
    //             $orderColumn = 'created_at';
    //         }

    //         // Base query
    //         $leaveQuery = Leave::with([
    //             'resident:id,user_id,name,scholar_no',
    //             'resident.user:id,email,building_id',
    //         ])->visibleFor($user);

    //         // Filters
    //         if ($request->filled('status')) {
    //             $leaveQuery->where('status', $request->status);
    //         }

    //         if ($request->filled('building_id')) {
    //             $leaveQuery->whereHas('resident.user', function ($q) use ($request) {
    //                 $q->where('building_id', $request->building_id);
    //             });
    //         }

    //         // Search
    //         if ($search) {
    //             $leaveQuery->where(function ($q) use ($search) {
    //                 $q->where('type', 'like', "%{$search}%")
    //                     ->orWhere('status', 'like', "%{$search}%")
    //                     ->orWhereHas('resident', function ($rq) use ($search) {
    //                         $rq->where('name', 'like', "%{$search}%")
    //                             ->orWhere('scholar_no', 'like', "%{$search}%");
    //                     })
    //                     ->orWhereHas('resident.user', function ($uq) use ($search) {
    //                         $uq->where('email', 'like', "%{$search}%");
    //                     });
    //             });
    //         }

    //         // Ordering
    //         $leaveQuery->orderBy($orderColumn, $orderDir);





    //         // Counts
    //         $recordsTotal = Leave::visibleFor($user)->count();
    //         $recordsFiltered = $leaveQuery->count();

    //         // Pagination
    //         $leaves = $leaveQuery
    //             ->skip($start)
    //             ->take($length)
    //             ->get();

    //         // ✅ CONTROLLED & FORMATTED RESPONSE
    //         $formattedLeaves = $leaves->map(function ($leave) {
    //             return [
    //                 'id' => $leave->id,
    //                 'type' => ucfirst($leave->type),
    //                 'status' => $leave->status,
    //                 'reason' => ucfirst($leave->reason),
    //                 'start_date' => optional($leave->start_date)
    //                     ? \Carbon\Carbon::parse($leave->start_date)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y')
    //                     : null,

    //                 'end_date' => optional($leave->end_date)
    //                     ? \Carbon\Carbon::parse($leave->end_date)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y')
    //                     : null,

    //                 'applied_at' => optional($leave->created_at)
    //                     ? $leave->created_at
    //                     ->timezone('Asia/Kolkata')
    //                     // ->format('d M Y, h:i A') . ' IST'
    //                     ->format('d M Y, h:i A')
    //                     : null,

    //                 'hod_status' => $leave->hod_status,
    //                 'hod_remarks' => $leave->hod_remarks,
    //                 'hod_action_at' => $leave->hod_action_at
    //                     ? \Carbon\Carbon::parse($leave->hod_action_at)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y, h:i A')
    //                     : null,


    //                 'admin_status' => $leave->admin_status,
    //                 'admin_remarks' => $leave->admin_remarks,
    //                 'admin_action_at' => $leave->admin_action_at
    //                     ? \Carbon\Carbon::parse($leave->admin_action_at)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y, h:i A')
    //                     : null,




    //                 'resident' => [
    //                     'name' => $leave->resident->name ?? null,
    //                     'scholar_no' => $leave->resident->scholar_no ?? null,
    //                     'email' => $leave->resident->user->email ?? null,
    //                     'room_number' => $leave->room_number ?? null,
    //                     'bed_number' => $leave->bed_number ?? null,
    //                 ],
    //             ];
    //         });

    //         // Buildings list
    //         $buildings = Building::select('id', 'name')
    //             ->when(
    //                 $user->building_id,
    //                 fn($q) => $q->whereIn('id', (array) $user->building_id)
    //             )
    //             ->orderBy('name')
    //             ->get();

    //         // JSON (API / DataTables)
    //         if ($request->expectsJson()) {
    //             return $this->success(
    //                 $formattedLeaves->isEmpty()
    //                     ? 'No leave applications found'
    //                     : 'Leave applications fetched successfully',
    //                 [
    //                     'draw' => (int) $request->input('draw'),
    //                     'recordsTotal' => $recordsTotal,
    //                     'recordsFiltered' => $recordsFiltered,
    //                     'leaves' => $formattedLeaves,
    //                     'roles' => $roles,
    //                     'buildings' => $buildings,
    //                 ]
    //             );
    //         }

    //         return view('backend.admin.leaves.index', compact('buildings'));
    //     } catch (\Throwable $e) {

    //         Log::error('Leave Index Error', [
    //             'user_id' => optional($request->user())->id,
    //             'exception' => $e,
    //         ]);

    //         return $this->handleWebOrApiError(
    //             $request,
    //             'Unable to load leave applications',
    //             500
    //         );
    //     }
    // }
    // public function index(Request $request)
    // {
    //     try {
    //         $user  = $request->user();
    //         $roles = $user->getRoleNames()->map(fn($r) => strtolower($r));

    //         /* =====================================================
    //      | 1. DATATABLES PARAMETERS
    //      ===================================================== */
    //         $draw   = (int) $request->input('draw');
    //         $start  = (int) $request->input('start', 0);
    //         $length = (int) $request->input('length', 10);
    //         $search = $request->input('search.value');

    //         $orderColumnIndex = $request->input('order.0.column');
    //         $orderDir         = $request->input('order.0.dir', 'desc');

    //         /* =====================================================
    //      | 2. ALLOWED ORDERING (SECURITY)
    //      ===================================================== */
    //         $allowedOrderColumns = [

    //             'created_at',
    //             'start_date',
    //             'end_date',
    //             'status',
    //             'type',
    //         ];

    //         // $orderColumn = $request->input("columns.$orderColumnIndex.data");
    //         $columnKey = $request->input("columns.$orderColumnIndex.data", 'created_at');


    //         // if (! in_array($orderColumn, $allowedOrderColumns)) {
    //         //     $orderColumn = 'created_at';
    //         // }

    //         /* =====================================================
    //      | 3. BASE QUERY (NO ORDER HERE!)
    //      ===================================================== */
    //         // $baseQuery = Leave::query()
    //         //     ->with([
    //         //         'resident:id,user_id,name,scholar_no',
    //         //         'resident.user:id,email,building_id',
    //         //     ])
    //         //     ->visibleFor($user);
    //         $baseQuery = Leave::query()
    //             ->select('leaves.*')
    //             ->join('residents', 'residents.id', '=', 'leaves.resident_id')
    //             ->join('users', 'users.id', '=', 'residents.user_id')
    //             ->visibleFor($user)
    //             ->with([
    //                 'resident:id,user_id,name,scholar_no',
    //                 'resident.user:id,email,building_id',
    //             ]);


    //         /* =====================================================
    //      | 4. TOTAL RECORDS (NO FILTERS)
    //      ===================================================== */
    //         $recordsTotal = (clone $baseQuery)->count();

    //         /* =====================================================
    //      | 5. FILTERS
    //      ===================================================== */
    //         if ($request->filled('status')) {
    //             $baseQuery->where('status', $request->status);
    //         }

    //         if ($request->filled('building_id')) {
    //             $baseQuery->whereHas(
    //                 'resident.user',
    //                 fn($q) =>
    //                 $q->where('building_id', $request->building_id)
    //             );
    //         }

    //         /* =====================================================
    //      | 6. SEARCH (GLOBAL)
    //      ===================================================== */
    //         if ($search) {
    //             $baseQuery->where(function ($q) use ($search) {
    //                 $q->where('type', 'like', "%{$search}%")
    //                     ->orWhere('status', 'like', "%{$search}%")
    //                     ->orWhereHas(
    //                         'resident',
    //                         fn($r) =>
    //                         $r->where('name', 'like', "%{$search}%")
    //                             ->orWhere('scholar_no', 'like', "%{$search}%")
    //                     )
    //                     ->orWhereHas(
    //                         'resident.user',
    //                         fn($u) =>
    //                         $u->where('email', 'like', "%{$search}%")
    //                     );
    //             });
    //         }

    //         /* =====================================================
    //      | 7. FILTERED COUNT
    //      ===================================================== */
    //         $recordsFiltered = (clone $baseQuery)->count();

    //         /* =====================================================
    //      | 8. ORDERING LOGIC (IMPORTANT PART)
    //      ===================================================== */
    //         $orderable = [
    //             'type'        => 'leaves.type',
    //             'status'      => 'leaves.status',
    //             'start_date'  => 'leaves.start_date',
    //             'end_date'    => 'leaves.end_date',
    //             'created_at'  => 'leaves.created_at',

    //             // related columns (ONLY if joined)
    //             'resident'    => 'residents.name',
    //             'email'       => 'users.email',
    //         ];


    //         if (isset($orderable[$columnKey])) {
    //             $baseQuery->orderBy($orderable[$columnKey], $orderDir);
    //         } else {
    //             $baseQuery->orderBy('leaves.created_at', 'desc');
    //         }


    //         // $isDefaultOrdering =
    //         //     ! $search &&
    //         //     ! $request->filled('status') &&
    //         //     ! $request->filled('building_id') &&
    //         //     $orderColumn === 'created_at';

    //         // if ($isDefaultOrdering) {
    //         //     // 🔥 Pending first, then latest
    //         //     $baseQuery
    //         //         ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
    //         //         ->orderBy('created_at', 'desc');
    //         // } else {
    //         //     // User-driven sorting (DataTables)
    //         //     $baseQuery->orderBy($orderColumn, $orderDir);
    //         // }

    //         /* =====================================================
    //      | 9. PAGINATION
    //      ===================================================== */
    //         $leaves = $baseQuery
    //             ->skip($start)
    //             ->take($length)
    //             ->get();

    //         /* =====================================================
    //      | 10. RESPONSE TRANSFORMATION
    //      ===================================================== */
    //         $formattedLeaves = $leaves->map(fn($leave) => [
    //             'id'          => $leave->id,
    //             'type'        => ucfirst($leave->type),
    //             'status'      => $leave->status,
    //             'reason'      => ucfirst($leave->reason),

    //             'start_date'  => optional($leave->start_date)
    //                 ? $leave->start_date->timezone('Asia/Kolkata')->format('d M Y')
    //                 : null,

    //             'end_date'    => optional($leave->end_date)
    //                 ? $leave->end_date->timezone('Asia/Kolkata')->format('d M Y')
    //                 : null,

    //             'applied_at'  => optional($leave->created_at)
    //                 ? $leave->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A')
    //                 : null,

    //             'hod_status'  => $leave->hod_status,
    //             'admin_status' => $leave->admin_status,

    //             'resident' => [
    //                 'name'        => $leave->resident->name ?? null,
    //                 'scholar_no'  => $leave->resident->scholar_no ?? null,
    //                 'email'       => $leave->resident->user->email ?? null,
    //                 'room_number' => $leave->room_number,
    //                 'bed_number'  => $leave->bed_number,
    //             ],
    //         ]);

    //         /* =====================================================
    //      | 11. BUILDINGS (FILTERED BY ROLE)
    //      ===================================================== */
    //         $buildings = Building::select('id', 'name')
    //             ->when(
    //                 $user->building_id,
    //                 fn($q) =>
    //                 $q->whereIn('id', (array) $user->building_id)
    //             )
    //             ->orderBy('name')
    //             ->get();

    //         /* =====================================================
    //      | 12. FINAL RESPONSE (UNIFORM)
    //      ===================================================== */
    //         if ($request->expectsJson()) {
    //             return $this->success(
    //                 $formattedLeaves->isEmpty()
    //                     ? 'No leave applications found'
    //                     : 'Leave applications fetched successfully',
    //                 [
    //                     'draw'            => $draw,
    //                     'recordsTotal'    => $recordsTotal,
    //                     'recordsFiltered' => $recordsFiltered,
    //                     'leaves'            => $formattedLeaves,
    //                     'roles'           => $roles,
    //                     'buildings'       => $buildings,
    //                 ]
    //             );
    //         }

    //         return view('backend.admin.leaves.index', compact('buildings'));
    //     } catch (\Throwable $e) {

    //         Log::error('Leave Index Error', [
    //             'user_id'   => optional($request->user())->id,
    //             'exception' => $e,
    //         ]);

    //         return $this->handleWebOrApiError(
    //             $request,
    //             'Unable to load leave applications',
    //             500
    //         );
    //     }
    // }
    // public function index(Request $request)
    // {
    //     try {
    //         $user = $request->user();
    //         $roles = $user->getRoleNames();

    //         // DataTables parameters with validation
    //         $start = max(0, (int) $request->input('start', 0));
    //         $length = min(100, max(1, (int) $request->input('length', 10)));
    //         $search = trim($request->input('search.value', ''));

    //         // Order parameters with validation
    //         $orderColumnIndex = (int) $request->input('order.0.column', 0);
    //         $orderDir = in_array(strtolower($request->input('order.0.dir', 'desc')), ['asc', 'desc'])
    //             ? $request->input('order.0.dir', 'desc')
    //             : 'desc';

    //         // Whitelist allowed order columns
    //         $allowedOrderColumns = ['created_at', 'start_date', 'end_date', 'status', 'type'];
    //         $orderColumn = $request->input("columns.$orderColumnIndex.data", 'created_at');
    //         if (!in_array($orderColumn, $allowedOrderColumns)) {
    //             $orderColumn = 'created_at';
    //         }

    //         // Base query
    //         $leaveQuery = Leave::with([
    //             'resident:id,user_id,name,scholar_no',
    //             'resident.user:id,email,building_id',
    //         ])->visibleFor($user);

    //         // ========== PRIORITY ORDERING ==========
    //         // 1. First priority: Default ordering - latest first (created_at desc)
    //         // 2. Second priority: Status - pending first, then others
    //         // 3. Third: Apply requested ordering if not already covered
    //         // 4. Final: ID as tie-breaker

    //         $leaveQuery->orderBy('created_at', 'desc'); // Priority 1
    //         $leaveQuery->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected', 'cancelled')"); // Priority 2

    //         if ($orderColumn !== 'created_at') {
    //             $leaveQuery->orderBy($orderColumn, $orderDir); // Requested ordering
    //         }

    //         $leaveQuery->orderBy('id', 'desc'); // Consistent pagination
    //         // ========== END PRIORITY ORDERING ==========

    //         // Apply filters
    //         if ($request->filled('status')) {
    //             $status = $request->status;
    //             $validStatuses = ['pending', 'approved', 'rejected', 'cancelled'];
    //             if (in_array($status, $validStatuses)) {
    //                 $leaveQuery->where('status', $status);
    //             }
    //         }

    //         if ($request->filled('building_id') && is_numeric($request->building_id)) {
    //             $leaveQuery->whereHas('resident.user', function ($q) use ($request) {
    //                 $q->where('building_id', (int) $request->building_id);
    //             });
    //         }

    //         // Date range filters
    //         if ($request->filled('start_date_from')) {
    //             $startDateFrom = \Carbon\Carbon::parse($request->start_date_from)->startOfDay();
    //             $leaveQuery->where('start_date', '>=', $startDateFrom);
    //         }

    //         if ($request->filled('start_date_to')) {
    //             $startDateTo = \Carbon\Carbon::parse($request->start_date_to)->endOfDay();
    //             $leaveQuery->where('start_date', '<=', $startDateTo);
    //         }

    //         // Type filter
    //         if ($request->filled('type')) {
    //             $type = strtolower($request->type);
    //             $validTypes = ['casual', 'medical', 'emergency', 'annual'];
    //             if (in_array($type, $validTypes)) {
    //                 $leaveQuery->where('type', $type);
    //             }
    //         }

    //         // Apply search
    //         if (!empty($search)) {
    //             $leaveQuery->where(function ($q) use ($search) {
    //                 // Split search terms and ignore single characters
    //                 $searchTerms = explode(' ', $search);
    //                 $searchTerms = array_filter($searchTerms, function ($term) {
    //                     return strlen(trim($term)) > 1;
    //                 });

    //                 if (empty($searchTerms)) {
    //                     // If only single characters, search as is
    //                     $q->where('type', 'like', "%{$search}%")
    //                         ->orWhere('status', 'like', "%{$search}%")
    //                         ->orWhere('reason', 'like', "%{$search}%")
    //                         ->orWhereHas('resident', function ($rq) use ($search) {
    //                             $rq->where('name', 'like', "%{$search}%")
    //                                 ->orWhere('scholar_no', 'like', "%{$search}%");
    //                         })
    //                         ->orWhereHas('resident.user', function ($uq) use ($search) {
    //                             $uq->where('email', 'like', "%{$search}%");
    //                         });
    //                 } else {
    //                     // Search with multiple terms
    //                     foreach ($searchTerms as $term) {
    //                         $q->where(function ($innerQ) use ($term) {
    //                             $innerQ->where('type', 'like', "%{$term}%")
    //                                 ->orWhere('status', 'like', "%{$term}%")
    //                                 ->orWhere('reason', 'like', "%{$term}%")
    //                                 ->orWhereHas('resident', function ($rq) use ($term) {
    //                                     $rq->where('name', 'like', "%{$term}%")
    //                                         ->orWhere('scholar_no', 'like', "%{$term}%");
    //                                 })
    //                                 ->orWhereHas('resident.user', function ($uq) use ($term) {
    //                                     $uq->where('email', 'like', "%{$term}%");
    //                                 });
    //                         });
    //                     }
    //                 }
    //             });
    //         }

    //         // Get counts (important: clone before pagination)
    //         $recordsTotal = Leave::visibleFor($user)->count();

    //         // Clone for filtered count
    //         $filteredQuery = clone $leaveQuery;
    //         $recordsFiltered = $filteredQuery->count();

    //         // Apply pagination
    //         $leaves = $leaveQuery
    //             ->skip($start)
    //             ->take($length)
    //             ->get();

    //         // Format response
    //         $formattedLeaves = $leaves->map(function ($leave) {
    //             $startDate = $leave->start_date ? \Carbon\Carbon::parse($leave->start_date)->timezone('Asia/Kolkata') : null;
    //             $endDate = $leave->end_date ? \Carbon\Carbon::parse($leave->end_date)->timezone('Asia/Kolkata') : null;
    //             $createdAt = $leave->created_at ? $leave->created_at->timezone('Asia/Kolkata') : null;
    //             $hodActionAt = $leave->hod_action_at ? \Carbon\Carbon::parse($leave->hod_action_at)->timezone('Asia/Kolkata') : null;
    //             $adminActionAt = $leave->admin_action_at ? \Carbon\Carbon::parse($leave->admin_action_at)->timezone('Asia/Kolkata') : null;

    //             return [
    //                 'id' => $leave->id,
    //                 'type' => ucfirst($leave->type),
    //                 'status' => $leave->status,
    //                 'reason' => $leave->reason ? ucfirst($leave->reason) : null,
    //                 'start_date' => $startDate?->format('d M Y'),
    //                 'start_date_raw' => $leave->start_date,
    //                 'end_date' => $endDate?->format('d M Y'),
    //                 'end_date_raw' => $leave->end_date,
    //                 'applied_at' => $createdAt?->format('d M Y, h:i A'),
    //                 'applied_at_raw' => $leave->created_at,
    //                 'duration_days' => $startDate && $endDate ? $startDate->diffInDays($endDate) + 1 : null,

    //                 'hod_status' => $leave->hod_status,
    //                 'hod_remarks' => $leave->hod_remarks,
    //                 'hod_action_at' => $hodActionAt?->format('d M Y, h:i A'),
    //                 'hod_action_at_raw' => $leave->hod_action_at,

    //                 'admin_status' => $leave->admin_status,
    //                 'admin_remarks' => $leave->admin_remarks,
    //                 'admin_action_at' => $adminActionAt?->format('d M Y, h:i A'),
    //                 'admin_action_at_raw' => $leave->admin_action_at,

    //                 'resident' => [
    //                     'id' => $leave->resident->id ?? null,
    //                     'name' => $leave->resident->name ?? null,
    //                     'scholar_no' => $leave->resident->scholar_no ?? null,
    //                     'email' => $leave->resident->user->email ?? null,
    //                     'building_id' => $leave->resident->user->building_id ?? null,
    //                     // 'room_number' => $leave->resident->user->room_number ?? null,
    //                     // 'bed_number' => $leave->resident->user->bed_number ?? null,
    //                 ],
    //             ];
    //         });

    //         // Get buildings for filters
    //         $buildings = Building::select('id', 'name')
    //             ->when(
    //                 $user->building_id,
    //                 fn($q) => $q->whereIn('id', (array) $user->building_id)
    //             )
    //             ->orderBy('name')
    //             ->get();

    //         // JSON/DataTables response
    //         if ($request->expectsJson() || $request->ajax()) {
    //             return response()->json([
    //                 'draw' => (int) $request->input('draw', 0),
    //                 'recordsTotal' => $recordsTotal,
    //                 'recordsFiltered' => $recordsFiltered,
    //                 'leaves' => $formattedLeaves,
    //                 'meta' => [
    //                     'roles' => $roles,
    //                     'buildings' => $buildings,
    //                     'pagination' => [
    //                         'page' => floor($start / $length) + 1,
    //                         'per_page' => $length,
    //                         'total' => $recordsFiltered,
    //                     ]
    //                 ]
    //             ]);
    //         }

    //         // Web view response
    //         return view('backend.admin.leaves.index', compact('buildings'));
    //     } catch (\Throwable $e) {
    //         Log::error('Leave Index Error', [
    //             'user_id' => optional($request->user())->id,
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString(),
    //         ]);

    //         if ($request->expectsJson() || $request->ajax()) {
    //             return response()->json([
    //                 'draw' => (int) $request->input('draw', 0),
    //                 'recordsTotal' => 0,
    //                 'recordsFiltered' => 0,
    //                 'data' => [],
    //                 'error' => 'Unable to load leave applications. Please try again later.',
    //             ], 500);
    //         }

    //         return back()->with('error', 'Unable to load leave applications. Please try again later.');
    //     }
    // }
    // public function index(Request $request)
    // {
    //     try {
    //         $user = $request->user();
    //         $roles = $user->getRoleNames();

    //         // DataTables parameters
    //         $start = max(0, (int) $request->input('start', 0));
    //         $length = min(100, max(1, (int) $request->input('length', 10)));
    //         $search = trim($request->input('search.value', ''));

    //         // Order parameters
    //         $orderColumnIndex = (int) $request->input('order.0.column', 0);
    //         $orderDir = in_array(strtolower($request->input('order.0.dir', 'desc')), ['asc', 'desc'])
    //             ? $request->input('order.0.dir', 'desc')
    //             : 'desc';

    //         // Map DataTables columns to database columns
    //         $columnMapping = [
    //             0 => 'created_at',       // Usually the first column
    //             1 => 'resident.name',    // Resident name
    //             2 => 'type',             // Leave type
    //             3 => 'start_date',       // Start date
    //             4 => 'end_date',         // End date
    //             5 => 'status',           // Status
    //             6 => 'hod_status',       // HOD status
    //             7 => 'admin_status',     // Admin status
    //             // Add more mappings as needed
    //         ];

    //         $orderColumn = $columnMapping[$orderColumnIndex] ?? 'created_at';

    //         // Whitelist allowed order columns
    //         $allowedOrderColumns = [
    //             'created_at',
    //             'start_date',
    //             'end_date',
    //             'status',
    //             'type',
    //             'hod_status',
    //             'admin_status',
    //             'hod_action_at',
    //             'admin_action_at',
    //             'resident.name' // For resident name ordering
    //         ];

    //         if (!in_array($orderColumn, $allowedOrderColumns)) {
    //             $orderColumn = 'created_at';
    //         }

    //         // Base query
    //         $leaveQuery = Leave::with([
    //             'resident:id,user_id,name,scholar_no',
    //             'resident.user:id,email,building_id',
    //         ])->visibleFor($user);

    //         // Apply filters
    //         if ($request->filled('status')) {
    //             $status = $request->status;
    //             $validStatuses = ['pending', 'approved', 'rejected', 'cancelled'];
    //             if (in_array($status, $validStatuses)) {
    //                 $leaveQuery->where('status', $status);
    //             }
    //         }

    //         if ($request->filled('building_id') && is_numeric($request->building_id)) {
    //             $leaveQuery->whereHas('resident.user', function ($q) use ($request) {
    //                 $q->where('building_id', (int) $request->building_id);
    //             });
    //         }

    //         if ($request->filled('type')) {
    //             $type = strtolower($request->type);
    //             $validTypes = ['casual', 'medical', 'emergency', 'annual'];
    //             if (in_array($type, $validTypes)) {
    //                 $leaveQuery->where('type', $type);
    //             }
    //         }

    //         // Apply search
    //         if (!empty($search)) {
    //             $leaveQuery->where(function ($q) use ($search) {
    //                 $q->where('type', 'like', "%{$search}%")
    //                     ->orWhere('status', 'like', "%{$search}%")
    //                     ->orWhere('reason', 'like', "%{$search}%")
    //                     ->orWhereHas('resident', function ($rq) use ($search) {
    //                         $rq->where('name', 'like', "%{$search}%")
    //                             ->orWhere('scholar_no', 'like', "%{$search}%");
    //                     })
    //                     ->orWhereHas('resident.user', function ($uq) use ($search) {
    //                         $uq->where('email', 'like', "%{$search}%");
    //                     });
    //             });
    //         }

    //         // ========== ORDERING STRATEGY ==========
    //         // First: Apply DataTables ordering if explicitly requested
    //         // If no explicit ordering OR ordering is by created_at, apply priority ordering

    //         $hasExplicitOrder = $request->has('order.0.column') && $request->input('order.0.column') !== '';
    //         $isOrderingByCreatedAt = $orderColumn === 'created_at';

    //         if ($hasExplicitOrder && !$isOrderingByCreatedAt) {
    //             // User explicitly clicked to sort by a column (not created_at)
    //             if (strpos($orderColumn, 'resident.') === 0) {
    //                 // Order by relationship
    //                 $relationColumn = str_replace('resident.', '', $orderColumn);
    //                 $leaveQuery->join('residents', 'leaves.resident_id', '=', 'residents.id')
    //                     ->orderBy("residents.{$relationColumn}", $orderDir)
    //                     ->select('leaves.*');
    //             } else {
    //                 // Order by leave column
    //                 $leaveQuery->orderBy($orderColumn, $orderDir);
    //             }

    //             // Add priority ordering as secondary (pending status first)
    //             $leaveQuery->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected', 'cancelled')");

    //             // Add tie-breaker
    //             $leaveQuery->orderBy('id', 'desc');
    //         } else {
    //             // Default priority ordering (when no explicit sort OR sorting by created_at)
    //             // 1. Latest first
    //             $leaveQuery->orderBy('created_at', 'desc');

    //             // 2. Status priority: pending first
    //             $leaveQuery->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected', 'cancelled')");

    //             // 3. Apply the requested direction if sorting by created_at
    //             if ($isOrderingByCreatedAt) {
    //                 $leaveQuery->orderBy('created_at', $orderDir);
    //             } elseif ($hasExplicitOrder) {
    //                 // If explicitly sorting by something else (shouldn't happen here, but just in case)
    //                 $leaveQuery->orderBy($orderColumn, $orderDir);
    //             }

    //             // 4. Tie-breaker
    //             $leaveQuery->orderBy('id', 'desc');
    //         }
    //         // ========== END ORDERING STRATEGY ==========

    //         // Get counts
    //         $recordsTotal = Leave::visibleFor($user)->count();

    //         // Clone for filtered count
    //         $filteredQuery = clone $leaveQuery;
    //         $recordsFiltered = $filteredQuery->count();

    //         // Apply pagination
    //         $leaves = $leaveQuery
    //             ->skip($start)
    //             ->take($length)
    //             ->get();

    //         // Format response - DataTables compatible
    //         $formattedLeaves = $leaves->map(function ($leave) {
    //             return [
    //                 'DT_RowId' => 'row_' . $leave->id, // For DataTables row ID
    //                 'id' => $leave->id,
    //                 'created_at' => optional($leave->created_at)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y, h:i A'),
    //                 'created_at_raw' => $leave->created_at ? $leave->created_at->timestamp : null,

    //                 'resident' => [
    //                     'name' => $leave->resident->name ?? null,
    //                     'scholar_no' => $leave->resident->scholar_no ?? null,
    //                 ],

    //                 'type' => ucfirst($leave->type),
    //                 'type_raw' => $leave->type,

    //                 'start_date' => optional($leave->start_date)
    //                     ? \Carbon\Carbon::parse($leave->start_date)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y')
    //                     : null,
    //                 'start_date_raw' => $leave->start_date,

    //                 'end_date' => optional($leave->end_date)
    //                     ? \Carbon\Carbon::parse($leave->end_date)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y')
    //                     : null,
    //                 'end_date_raw' => $leave->end_date,

    //                 'status' => $leave->status,
    //                 'status_raw' => $leave->status,
    //                 'status_html' => $this->getStatusHtml($leave->status),

    //                 'reason' => ucfirst($leave->reason),
    //                 'duration' => $leave->start_date && $leave->end_date
    //                     ? \Carbon\Carbon::parse($leave->start_date)
    //                     ->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1
    //                     : null,

    //                 'hod_status' => $leave->hod_status,
    //                 'hod_remarks' => $leave->hod_remarks,
    //                 'hod_action_at' => $leave->hod_action_at
    //                     ? \Carbon\Carbon::parse($leave->hod_action_at)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y, h:i A')
    //                     : null,

    //                 'admin_status' => $leave->admin_status,
    //                 'admin_remarks' => $leave->admin_remarks,
    //                 'admin_action_at' => $leave->admin_action_at
    //                     ? \Carbon\Carbon::parse($leave->admin_action_at)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y, h:i A')
    //                     : null,

    //                 'email' => $leave->resident->user->email ?? null,
    //                 'room_number' => $leave->resident->user->room_number ?? null,
    //                 'bed_number' => $leave->resident->user->bed_number ?? null,
    //                 'building_id' => $leave->resident->user->building_id ?? null,
    //             ];
    //         });

    //         // Get buildings for filters
    //         $buildings = Building::select('id', 'name')
    //             ->when(
    //                 $user->building_id,
    //                 fn($q) => $q->whereIn('id', (array) $user->building_id)
    //             )
    //             ->orderBy('name')
    //             ->get();

    //         // DataTables JSON response
    //         if ($request->expectsJson() || $request->ajax()) {
    //             return response()->json([
    //                 'draw' => (int) $request->input('draw', 0),
    //                 'recordsTotal' => $recordsTotal,
    //                 'recordsFiltered' => $recordsFiltered,
    //                 'data' => $formattedLeaves,
    //                 'meta' => [
    //                     'roles' => $roles,
    //                     'buildings' => $buildings,
    //                 ]
    //             ]);
    //         }

    //         // Web view response
    //         return view('backend.admin.leaves.index', compact('buildings'));
    //     } catch (\Throwable $e) {
    //         \Log::error('Leave Index Error', [
    //             'user_id' => optional($request->user())->id,
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString(),
    //         ]);

    //         if ($request->expectsJson() || $request->ajax()) {
    //             return response()->json([
    //                 'draw' => (int) $request->input('draw', 0),
    //                 'recordsTotal' => 0,
    //                 'recordsFiltered' => 0,
    //                 'data' => [],
    //                 'error' => 'Unable to load leave applications.',
    //             ], 500);
    //         }

    //         return back()->with('error', 'Unable to load leave applications.');
    //     }
    // }

    // public function index(Request $request)
    // {
    //     Log::info($request->all());
    //     try {
    //         $user = $request->user();
    //         $roles = $user->getRoleNames();

    //         // DataTables parameters
    //         $start = max(0, (int) $request->input('start', 0));
    //         $length = min(100, max(1, (int) $request->input('length', 10)));
    //         $search = trim($request->input('search.value', ''));

    //         // Order parameters - get the column name from data attribute
    //         $orderColumnIndex = (int) $request->input('order.0.column', 0);
    //         $orderDir = in_array(strtolower($request->input('order.0.dir', 'desc')), ['asc', 'desc'])
    //             ? $request->input('order.0.dir', 'desc')
    //             : 'desc';

    //         // Get the column key from data attribute (frontend sends column.data)
    //         $columnKey = $request->input("columns.$orderColumnIndex.data", 'created_at');

    //         // Map frontend column keys to database columns
    //         // $orderable = [
    //         //     // Leave columns
    //         //     'created_at' => 'leaves.created_at',
    //         //     'start_date' => 'leaves.start_date',
    //         //     'end_date' => 'leaves.end_date',
    //         //     'status' => 'leaves.status',
    //         //     'type' => 'leaves.type',
    //         //     'reason' => 'leaves.reason',
    //         //     'hod_status' => 'leaves.hod_status',
    //         //     'admin_status' => 'leaves.admin_status',
    //         //     'hod_action_at' => 'leaves.hod_action_at',
    //         //     'admin_action_at' => 'leaves.admin_action_at',

    //         //     // Resident columns (if needed for ordering)
    //         //     'resident_name' => 'residents.name',
    //         //     'resident_scholar_no' => 'residents.scholar_no',

    //         //     // User columns
    //         //     'email' => 'users.email',
    //         //     'room_number' => 'users.room_number',
    //         //     'bed_number' => 'users.bed_number',

    //         //     // Building columns
    //         //     'building' => 'buildings.name',
    //         // ];
    //          $orderable = [
    //             // Leave columns
    //             'created_at' => 'leaves.created_at',
    //             'start_date' => 'leaves.start_date',
    //             'end_date' => 'leaves.end_date',
    //             'status' => 'leaves.status',
    //             'type' => 'leaves.type',
    //             'reason' => 'leaves.reason',
    //             'attachment' => 'leaves.attachment', // Based on your log
    //             'hod_status' => 'leaves.hod_status',
    //             'admin_status' => 'leaves.admin_status',
    //             'hod_action_at' => 'leaves.hod_action_at',
    //             'admin_action_at' => 'leaves.admin_action_at',

    //             // Resident columns
    //             'resident_name' => 'residents.name',
    //             'resident_scholar_no' => 'residents.scholar_no',

    //             // User columns
    //             'email' => 'users.email',
    //             'resident_room_number' => 'users.room_number',
    //             'resident_bed_number' => 'users.bed_number',

    //             // Building columns
    //             'building' => 'buildings.name',
    //         ];

    //         // Base query with joins for ordering
    //         $leaveQuery = Leave::with([
    //             'resident:id,user_id,name,scholar_no',
    //             // 'resident.user:id,email,building_id,room_number,bed_number',
    //             'resident.user:id,email,building_id',
    //         ])
    //             ->visibleFor($user)
    //             ->join('residents', 'leaves.resident_id', '=', 'residents.id')
    //             ->join('users', 'residents.user_id', '=', 'users.id')
    //             ->leftJoin('buildings', 'users.building_id', '=', 'buildings.id')
    //             ->select('leaves.*'); // Select only leave columns

    //         // Apply filters
    //         if ($request->filled('status')) {
    //             $status = $request->status;
    //             $validStatuses = ['pending', 'approved', 'rejected', 'cancelled'];
    //             if (in_array($status, $validStatuses)) {
    //                 $leaveQuery->where('leaves.status', $status);
    //             }
    //         }

    //         if ($request->filled('building_id') && is_numeric($request->building_id)) {
    //             $leaveQuery->where('users.building_id', (int) $request->building_id);
    //         }

    //         if ($request->filled('type')) {
    //             $type = strtolower($request->type);
    //             $validTypes = ['casual', 'medical', 'emergency', 'annual'];
    //             if (in_array($type, $validTypes)) {
    //                 $leaveQuery->where('leaves.type', $type);
    //             }
    //         }

    //         // Date range filters
    //         if ($request->filled('start_date_from')) {
    //             $startDateFrom = \Carbon\Carbon::parse($request->start_date_from)->startOfDay();
    //             $leaveQuery->where('leaves.start_date', '>=', $startDateFrom);
    //         }

    //         if ($request->filled('start_date_to')) {
    //             $startDateTo = \Carbon\Carbon::parse($request->start_date_to)->endOfDay();
    //             $leaveQuery->where('leaves.start_date', '<=', $startDateTo);
    //         }

    //         // Apply search
    //         if (!empty($search)) {
    //             $leaveQuery->where(function ($q) use ($search) {
    //                 $q->where('leaves.type', 'like', "%{$search}%")
    //                     ->orWhere('leaves.status', 'like', "%{$search}%")
    //                     ->orWhere('leaves.reason', 'like', "%{$search}%")
    //                     ->orWhere('residents.name', 'like', "%{$search}%")
    //                     ->orWhere('residents.scholar_no', 'like', "%{$search}%")
    //                     ->orWhere('users.email', 'like', "%{$search}%");
    //             });
    //         }

    //         // ========== ORDERING LOGIC ==========
    //         $hasExplicitOrder = $request->has('order.0.column') && $request->input('order.0.column') !== '';

    //         if ($hasExplicitOrder && isset($orderable[$columnKey])) {
    //             // User explicitly clicked to sort by a column
    //             $orderColumn = $orderable[$columnKey];

    //             // Apply user's sort choice
    //             $leaveQuery->orderBy($orderColumn, $orderDir);

    //             // Add priority ordering as secondary (pending status first)
    //             $leaveQuery->orderByRaw("FIELD(leaves.status, 'pending', 'approved', 'rejected', 'cancelled')");

    //             // Add tie-breaker for consistent pagination
    //             $leaveQuery->orderBy('leaves.id', 'desc');
    //         } else {
    //             // Default priority ordering (when no explicit sort OR column not orderable)
    //             // 1. Latest first
    //             $leaveQuery->orderBy('leaves.created_at', 'desc');

    //             // 2. Status priority: pending first
    //             $leaveQuery->orderByRaw("FIELD(leaves.status, 'pending', 'approved', 'rejected', 'cancelled')");

    //             // 3. Tie-breaker
    //             $leaveQuery->orderBy('leaves.id', 'desc');
    //         }
    //         // ========== END ORDERING LOGIC ==========

    //         // Get total count (without filters)
    //         $recordsTotal = Leave::visibleFor($user)->count();

    //         // Get filtered count (clone before pagination)
    //         $filteredQuery = clone $leaveQuery;
    //         $recordsFiltered = $filteredQuery->distinct('leaves.id')->count('leaves.id');

    //         // Apply pagination
    //         $leaves = $leaveQuery
    //             ->distinct('leaves.id')
    //             ->skip($start)
    //             ->take($length)
    //             ->get();

    //         // Format response - DataTables compatible
    //         $formattedLeaves = $leaves->map(function ($leave) {
    //             return [
    //                 'DT_RowId' => 'row_' . $leave->id,
    //                 'id' => $leave->id,
    //                 'created_at' => optional($leave->created_at)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y, h:i A'),
    //                 'created_at_raw' => $leave->created_at ? $leave->created_at->timestamp : null,

    //                 'resident_name' => $leave->resident->name ?? null,
    //                 'resident_scholar_no' => $leave->resident->scholar_no ?? null,

    //                 'type' => ucfirst($leave->type),
    //                 'type_raw' => $leave->type,

    //                 'start_date' => optional($leave->start_date)
    //                     ? \Carbon\Carbon::parse($leave->start_date)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y')
    //                     : null,
    //                 'start_date_raw' => $leave->start_date,

    //                 'end_date' => optional($leave->end_date)
    //                     ? \Carbon\Carbon::parse($leave->end_date)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y')
    //                     : null,
    //                 'end_date_raw' => $leave->end_date,

    //                 'status' => $leave->status,
    //                 'status_raw' => $leave->status,
    //                 'status_html' => $this->getStatusHtml($leave->status),

    //                 'reason' => ucfirst($leave->reason),
    //                 'duration' => $leave->start_date && $leave->end_date
    //                     ? \Carbon\Carbon::parse($leave->start_date)
    //                     ->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1
    //                     : null,

    //                 'hod_status' => $leave->hod_status,
    //                 'hod_remarks' => $leave->hod_remarks,
    //                 'hod_action_at' => $leave->hod_action_at
    //                     ? \Carbon\Carbon::parse($leave->hod_action_at)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y, h:i A')
    //                     : null,
    //                 'hod_action_at_raw' => $leave->hod_action_at,

    //                 'admin_status' => $leave->admin_status,
    //                 'admin_remarks' => $leave->admin_remarks,
    //                 'admin_action_at' => $leave->admin_action_at
    //                     ? \Carbon\Carbon::parse($leave->admin_action_at)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y, h:i A')
    //                     : null,
    //                 'admin_action_at_raw' => $leave->admin_action_at,

    //                 'email' => $leave->resident->user->email ?? null,
    //                 'room_number' => $leave->resident->user->room_number ?? null,
    //                 'bed_number' => $leave->resident->user->bed_number ?? null,
    //                 'building' => $leave->resident->user->building->name ?? null,
    //                 'building_id' => $leave->resident->user->building_id ?? null,
    //             ];
    //         });

    //         // Get buildings for filters
    //         $buildings = Building::select('id', 'name')
    //             ->when(
    //                 $user->building_id,
    //                 fn($q) => $q->whereIn('id', (array) $user->building_id)
    //             )
    //             ->orderBy('name')
    //             ->get();

    //         // DataTables JSON response
    //         if ($request->expectsJson() || $request->ajax()) {
    //             return response()->json([
    //                 'draw' => (int) $request->input('draw', 0),
    //                 'recordsTotal' => $recordsTotal,
    //                 'recordsFiltered' => $recordsFiltered,
    //                 'data' => $formattedLeaves,
    //                 'meta' => [
    //                     'roles' => $roles,
    //                     'buildings' => $buildings,
    //                 ]
    //             ]);
    //         }

    //         // Web view response
    //         return view('backend.admin.leaves.index', compact('buildings'));
    //     } catch (\Throwable $e) {
    //         \Log::error('Leave Index Error', [
    //             'user_id' => optional($request->user())->id,
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString(),
    //         ]);

    //         if ($request->expectsJson() || $request->ajax()) {
    //             return response()->json([
    //                 'draw' => (int) $request->input('draw', 0),
    //                 'recordsTotal' => 0,
    //                 'recordsFiltered' => 0,
    //                 'data' => [],
    //                 'error' => 'Unable to load leave applications.',
    //             ], 500);
    //         }

    //         return back()->with('error', 'Unable to load leave applications.');
    //     }
    // }

    // Orig
    // public function index(Request $request)
    // {
    //     if (! $request->ajax()) {
    //         return view('leaves.index');
    //     }

    //     try {

    //         $user = $request->user();
    //         $roles = $user->getRoleNames();

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Base Query (University-safe via scopeAccessible)
    //         |--------------------------------------------------------------------------
    //         */
    //         $query = Leave::query()
    //             // ->accessible()
    //             ->visibleFor($user)
    //             ->leftJoin('residents', 'residents.id', '=', 'leaves.resident_id')
    //             // ->leftJoin('universities', 'universities.id', '=', 'buildings.university_id')
    //             ->select([
    //                 'leaves.id',
    //                 'leaves.room_number',
    //                 'leaves.type',
    //                 'leaves.reason',
    //                 'leaves.start_date',
    //                 'leaves.end_date',
    //                 'leaves.hod_status',
    //                 'leaves.hod_remarks',
    //                 'leaves.hod_action_at',
    //                 'leaves.admin_status',
    //                 'leaves.admin_remarks',
    //                 'leaves.admin_action_at',
    //                 'leaves.status',
    //                 'leaves.created_at',

    //                 // Related display fields
    //                 'residents.name as resident_name',
    //                 'residents.scholar_no as resident_scholar_no',
    //             ]);

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Summary (Clone-safe)
    //         |--------------------------------------------------------------------------
    //         */
    //         $buildings = Building::query()
    //             ->accessible()
    //             ->where('status', 1)
    //             ->orderBy('name')
    //             ->get(['id', 'name', 'floors']);

    //         $summary = [
    //             'total' => (clone $query)->count(),
    //             'active' => (clone $query)->whereRaw("
    //                 CASE
    //                     WHEN leaves.status IN (1, '1', 'active', 'enabled', 'yes') THEN 1
    //                     ELSE 0
    //                 END = 1
    //             ")->count(),
    //             'inactive' => (clone $query)->whereRaw("
    //                 CASE
    //                     WHEN leaves.status IN (1, '1', 'active', 'enabled', 'yes') THEN 1
    //                     ELSE 0
    //                 END = 0
    //             ")->count(),
    //         ];

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Records Total
    //         |--------------------------------------------------------------------------
    //         */
    //         $recordsTotal = (clone $query)->count();

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Global Search
    //         |--------------------------------------------------------------------------
    //         */
    //         if ($search = trim($request->input('search.value'))) {
    //             $query->where(function ($q) use ($search) {
    //                 $q->where('leaves.room_number', 'like', "%{$search}%")
    //                     ->orWhere('leaves.room_type', 'like', "%{$search}%")
    //                     ->orWhere('leaves.floor_no', 'like', "%{$search}%")
    //                     ->orWhere('residents_name', 'like', "%{$search}%");


    //                 if (in_array(strtolower($search), ['active', 'inactive', '1', '0'])) {
    //                     $q->orWhereRaw("
    //                         CASE
    //                             WHEN leaves.status IN (1, '1', 'active', 'enabled', 'yes') THEN 1
    //                             ELSE 0
    //                         END = ?
    //                     ", [in_array(strtolower($search), ['active', '1']) ? 1 : 0]);
    //                 }
    //             });
    //         }

    //         $recordsFiltered = (clone $query)->count();

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Ordering
    //         |--------------------------------------------------------------------------
    //         */
    //         $orderColumnIndex = $request->input('order.0.column');
    //         $orderDir = $request->input('order.0.dir', 'desc');
    //         $columnKey = $request->input("columns.$orderColumnIndex.data");

    //         $orderable = [
    //             // Leave columns
    //             'created_at' => 'leaves.created_at',
    //             'start_date' => 'leaves.start_date',
    //             'end_date' => 'leaves.end_date',
    //             'status' => 'leaves.status',
    //             'type' => 'leaves.type',
    //             'reason' => 'leaves.reason',
    //             'attachment' => 'leaves.attachment', // Based on your log
    //             'hod_status' => 'leaves.hod_status',
    //             'admin_status' => 'leaves.admin_status',
    //             'hod_action_at' => 'leaves.hod_action_at',
    //             'admin_action_at' => 'leaves.admin_action_at',

    //             // Resident columns
    //             'resident_name' => 'residents.name',
    //             'resident_scholar_no' => 'residents.scholar_no',

    //             // User columns
    //             'email' => 'users.email',
    //             'resident_room_number' => 'users.room_number',
    //             'resident_bed_number' => 'users.bed_number',

    //             // Building columns
    //             'building' => 'buildings.name',
    //         ];

    //         if (isset($orderable[$columnKey])) {
    //             $query->orderBy($orderable[$columnKey], $orderDir);
    //         } else {
    //             $query->orderBy('leaves.created_at', 'desc');
    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Pagination
    //         |--------------------------------------------------------------------------
    //         */
    //         $records = $query
    //             ->skip((int) $request->start)
    //             ->take((int) $request->length)
    //             ->get();

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Response Mapping
    //         |--------------------------------------------------------------------------
    //         */
    //                 // Format response - DataTables compatible
    //         $formattedLeaves = $records->map(function ($leave) {
    //             return [
    //                 'DT_RowId' => 'row_' . $leave->id,
    //                 'id' => $leave->id,
    //                 'created_at' => optional($leave->created_at)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y, h:i A'),
    //                 'created_at_raw' => $leave->created_at ? $leave->created_at->timestamp : null,

    //                 'resident_name' => $leave->resident->name ?? null,
    //                 'resident_scholar_no' => $leave->resident->scholar_no ?? null,

    //                 'type' => ucfirst($leave->type),
    //                 'type_raw' => $leave->type,

    //                 'start_date' => optional($leave->start_date)
    //                     ? \Carbon\Carbon::parse($leave->start_date)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y')
    //                     : null,
    //                 'start_date_raw' => $leave->start_date,

    //                 'end_date' => optional($leave->end_date)
    //                     ? \Carbon\Carbon::parse($leave->end_date)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y')
    //                     : null,
    //                 'end_date_raw' => $leave->end_date,

    //                 'status' => $leave->status,
    //                 'status_raw' => $leave->status,
    //                 'status_html' => $this->getStatusHtml($leave->status),

    //                 'reason' => ucfirst($leave->reason),
    //                 'duration' => $leave->start_date && $leave->end_date
    //                     ? \Carbon\Carbon::parse($leave->start_date)
    //                     ->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1
    //                     : null,

    //                 'hod_status' => $leave->hod_status,
    //                 'hod_remarks' => $leave->hod_remarks,
    //                 'hod_action_at' => $leave->hod_action_at
    //                     ? \Carbon\Carbon::parse($leave->hod_action_at)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y, h:i A')
    //                     : null,
    //                 'hod_action_at_raw' => $leave->hod_action_at,

    //                 'admin_status' => $leave->admin_status,
    //                 'admin_remarks' => $leave->admin_remarks,
    //                 'admin_action_at' => $leave->admin_action_at
    //                     ? \Carbon\Carbon::parse($leave->admin_action_at)
    //                     ->timezone('Asia/Kolkata')
    //                     ->format('d M Y, h:i A')
    //                     : null,
    //                 'admin_action_at_raw' => $leave->admin_action_at,

    //                 'email' => $leave->resident->user->email ?? null,
    //                 'room_number' => $leave->resident->user->room_number ?? null,
    //                 'bed_number' => $leave->resident->user->bed_number ?? null,
    //                 'building' => $leave->resident->user->building->name ?? null,
    //                 'building_id' => $leave->resident->user->building_id ?? null,
    //             ];
    //         });

    //         // Get buildings for filters
    //         $buildings = Building::select('id', 'name')
    //             ->when(
    //                 $user->building_id,
    //                 fn($q) => $q->whereIn('id', (array) $user->building_id)
    //             )
    //             ->orderBy('name')
    //             ->get();

    //         // DataTables JSON response
    //         if ($request->expectsJson() || $request->ajax()) {
    //             return response()->json([
    //                 'draw' => (int) $request->input('draw', 0),
    //                 'recordsTotal' => $recordsTotal,
    //                 'recordsFiltered' => $recordsFiltered,
    //                 'data' => $formattedLeaves,
    //                 'meta' => [
    //                     'roles' => $roles,
    //                     'buildings' => $buildings,
    //                 ]
    //             ]);
    //         }

    //         // Web view response
    //         return view('backend.admin.leaves.index', compact('buildings'));
    //     } catch (\Throwable $e) {
    //         Log::error('Leave Index Error', [
    //             'user_id' => optional($request->user())->id,
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString(),
    //         ]);

    //         if ($request->expectsJson() || $request->ajax()) {
    //             return response()->json([
    //                 'draw' => (int) $request->input('draw', 0),
    //                 'recordsTotal' => 0,
    //                 'recordsFiltered' => 0,
    //                 'data' => [],
    //                 'error' => 'Unable to load leave applications.',
    //             ], 500);
    //         }

    //         return back()->with('error', 'Unable to load leave applications.');
    //     }

    // }

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


    // public function update(Request $request, $id)
    // {
    //     DB::beginTransaction();

    //     Log::info('update request', $request->all());
    //     $request->validate([
    //         'action'   => 'required|string',
    //         'remarks'  => str_contains($request->action, 'reject')
    //             ? 'required|string|max:500'
    //             : 'nullable|string|max:500',
    //         'attachment' => 'nullable|file|max:2048'
    //     ]);

    //     try {
    //         $leave = Leave::findOrFail($id);
    //         // $user  = auth()->user();
    //         $user = $request->user();

    //         /*
    //     |--------------------------------------------------------------------------
    //     | ACTION-BASED VALIDATION
    //     |--------------------------------------------------------------------------
    //     */
    //         if ($request->filled('action')) {

    //             $rules = [
    //                 'action'  => 'required|string',
    //                 'remarks' => 'nullable|string|max:500',
    //                 'attachment' => 'nullable|file|max:2048',
    //             ];

    //             if (str_contains($request->action, 'reject')) {
    //                 $rules['remarks'] = 'required|string|max:500';
    //             }

    //             $validated = $request->validate($rules);

    //             /* =======================
    //          | HANDLE ATTACHMENT
    //          ======================= */
    //             $attachmentPath = null;
    //             if ($request->hasFile('attachment')) {
    //                 $attachmentPath = $request->file('attachment')
    //                     ->store('leave-actions', 'public');
    //             }

    //             /* =======================
    //          | HOD ACTIONS
    //          ======================= */
    //             if ($validated['action'] === 'hod_approve') {

    //                 abort_unless($user->hasRole('hod'), 403);
    //                 abort_if($leave->hod_status !== 'pending', 409);

    //                 $leave->update([
    //                     'hod_status'      => 'approved',
    //                     'hod_remarks'     => $validated['remarks'] ?? null,
    //                     'hod_action_at' => now(),
    //                     'attachment'      => $attachmentPath ?? $leave->attachment,
    //                 ]);
    //             }

    //             if ($validated['action'] === 'hod_reject') {

    //                 abort_unless($user->hasRole('hod'), 403);
    //                 abort_if($leave->hod_status !== 'pending', 409);

    //                 $leave->update([
    //                     'hod_status'      => 'rejected',
    //                     'hod_remarks'     => $validated['remarks'],
    //                     'hod_action_at' => now(),
    //                     'status'          => 'rejected',
    //                     'attachment'      => $attachmentPath ?? $leave->attachment,
    //                 ]);
    //             }

    //             /* =======================
    //          | ADMIN ACTIONS
    //          ======================= */
    //             if ($validated['action'] === 'admin_approve') {

    //                 abort_unless($user->hasRole('admin'), 403);
    //                 abort_if($leave->hod_status !== 'approved', 409);
    //                 abort_if($leave->admin_status !== 'pending', 409);

    //                 $leave->update([
    //                     'admin_status'      => 'approved',
    //                     'admin_remarks'     => $validated['remarks'] ?? null,
    //                     'admin_action_at' => now(),
    //                     'status'            => 'approved',
    //                     'attachment'        => $attachmentPath ?? $leave->attachment,
    //                 ]);
    //             }

    //             if ($validated['action'] === 'admin_reject') {

    //                 abort_unless($user->hasRole('admin'), 403);
    //                 abort_if($leave->hod_status !== 'approved', 409);
    //                 abort_if($leave->admin_status !== 'pending', 409);

    //                 $updatedData = [
    //                     'admin_status'      => 'rejected',
    //                     'admin_remarks'     => $validated['remarks'],
    //                     'admin_action_at' => now(),
    //                     'status'            => 'rejected',
    //                     'attachment'        => $attachmentPath ?? $leave->attachment,
    //                 ];
    //                 Log::info('updated', $updatedData);

    //                 $leave->update($updatedData);
    //             }
    //         } else {
    //             /*
    //         |--------------------------------------------------------------------------
    //         | NORMAL UPDATE (if ever allowed)
    //         |--------------------------------------------------------------------------
    //         */
    //             $validated = $request->validate([
    //                 'reason' => 'nullable|string|max:1000',
    //                 'remarks' => 'nullable|string|max:1000',
    //             ]);

    //             $leave->update($validated);
    //         }

    //         DB::commit();

    //         return $request->expectsJson()
    //             ? $this->success('Leave updated successfully', $leave)
    //             : redirect()->back()->with('swal_success', 'Leave updated successfully');
    //     } catch (ModelNotFoundException $e) {

    //         DB::rollBack();

    //         return $this->handleWebOrApiError(
    //             $request,
    //             'Leave record not found',
    //             404
    //         );
    //     } catch (QueryException $e) {

    //         DB::rollBack();
    //         Log::error('Leave Update DB Error', ['exception' => $e]);

    //         return $this->handleWebOrApiError(
    //             $request,
    //             'Database error while updating leave'
    //         );
    //     } catch (\Throwable $e) {

    //         DB::rollBack();
    //         Log::critical('Leave Update Error', ['exception' => $e]);

    //         return $this->handleWebOrApiError(
    //             $request,
    //             'Leave update failed'
    //         );
    //     }
    // }


    // public function update(Request $request, $id)
    // {
    //     Log::info('update request', $request->all());
    //     DB::beginTransaction();

    //     try {
    //         $leave = Leave::findOrFail($id);
    //         Log::info('leave found', ['leave_id' => $leave->id]);
    //         $user  = $request->user();

    //         // Validate base request
    //         $request->validate([
    //             'action'     => 'required|string',
    //             'remarks'    => str_contains(Str::lower($request->action), 'reject')
    //                 ? 'required|string|max:500'
    //                 : 'nullable|string|max:500',
    //             'attachment' => 'nullable|file|max:2048',
    //         ]);

    //         $validated = $request->only(['action', 'remarks', 'attachment']);

    //         // Handle attachment
    //         $attachmentPath = $request->hasFile('attachment')
    //             ? $request->file('attachment')->store('leave-actions', 'public')
    //             : $leave->attachment;

    //          if ($request->filled('action')) {

    //         // Normalize role and action
    //         $role   = Str::lower($user->getRoleNames()->first()); // always lowercase
    //         $action = Str::lower($validated['action']);           // always lowercase
    //         $status = str_contains($action, 'approve') ? 'approved' : 'rejected';

    //         // Role-based guards
    //         // if ($role === 'hod') {
    //         //     abort_unless(in_array($action, ['hod_approve','hod_reject']), 403);
    //         //     abort_if($leave->hod_status !== 'pending', 409);
    //         // }
    //         // if ($role === 'admin') {
    //         //     abort_unless(in_array($action, ['admin_approve','admin_reject']), 403);
    //         //     abort_if($leave->hod_status !== 'approved', 409);
    //         //     abort_if($leave->admin_status !== 'pending', 409);
    //         // }

    //         if ($role !== 'hod' && Str::startsWith($action, 'hod_')) {
    //                 return response()->json(['error' => 'Only HOD can perform this action'], 403);
    //             }

    //             if ($role !== 'admin' && Str::startsWith($action, 'admin_')) {
    //                 return response()->json(['error' => 'Only Admin can perform this action'], 403);
    //             }


    //         // Build approval record
    //         $approvalRecord = [
    //             'role'      => ucfirst($role), // store nicely formatted role name
    //             'status'    => $status,
    //             'remarks'   => $validated['remarks'] ?? null,
    //             // 'action_by' => $user->id,
    //             'action_by' => $user->name,
    //             'action_at' => now()->toDateTimeString(),
    //         ];

    //         // Append to approvals JSON
    //         $approvals = $leave->approvals ?? [];
    //         $approvals[] = $approvalRecord;

    //         // Update leave record
    //         $updateData = [
    //             'approvals'  => $approvals,
    //             'status'     => $status,
    //             'attachment' => $attachmentPath,
    //         ];

    //         // Keep legacy columns in sync if needed
    //         if ($role === 'hod') {
    //             $updateData['hod_status']    = $status;
    //             $updateData['hod_remarks']   = $validated['remarks'] ?? null;
    //             $updateData['hod_action_at'] = now();
    //         }
    //         if ($role === 'admin') {
    //             $updateData['admin_status']    = $status;
    //             $updateData['admin_remarks']   = $validated['remarks'] ?? null;
    //             $updateData['admin_action_at'] = now();
    //         }
    //          }

    //         $leave->update($updateData);

    //         DB::commit();

    //         return $request->expectsJson()
    //             ? response()->json(['message' => 'Leave updated successfully', 'leave' => $leave])
    //             : redirect()->back()->with('swal_success', 'Leave updated successfully');

    //     } catch (ModelNotFoundException $e) {
    //         DB::rollBack();
    //         return response()->json(['error' => 'Leave record not found'], 404);
    //     } catch (QueryException $e) {
    //         DB::rollBack();
    //         Log::error('Leave Update DB Error', ['exception' => $e]);
    //         return response()->json(['error' => 'Database error while updating leave'], 500);
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         Log::critical('Leave Update Error', ['exception' => $e]);
    //         return response()->json(['error' => 'Leave update failed'], 500);
    //     }
    // }


    //       $roles = collect($user->getRoleNames())
    //     ->map(fn ($r) => strtolower(trim($r)))
    //     ->toArray();

    //     $isAdmin       = in_array('admin', $roles);
    // $isSuperAdmin  = in_array('super-admin', $roles);
    // $isWarden      = in_array('warden', $roles);
    // $isResident    = in_array('resident', $roles);
    // public function update(Request $request, $id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $user  = $request->user();
    //         $leave = Leave::lockForUpdate()->findOrFail($id);

    //         /*
    //         |------------------------------------------------------------------
    //         | ACTION MAP
    //         |------------------------------------------------------------------
    //         */
    //         $actionMap = [
    //             'hod_approve' => [
    //                 'roles' => ['hod'],
    //                 'from'  => fn ($l) => strtolower($l->hod_status) === 'pending',
    //                 'apply' => fn ($l, $d) => [
    //                     'hod_status'    => 'approved',
    //                     'hod_remarks'   => $d['remarks'] ?? null,
    //                     'hod_action_at' => now(),
    //                 ],
    //             ],

    //             'hod_reject' => [
    //                 'roles' => ['hod'],
    //                 'from'  => fn ($l) => strtolower($l->hod_status) === 'pending',
    //                 'apply' => fn ($l, $d) => [
    //                     'hod_status'    => 'rejected',
    //                     'hod_remarks'   => $d['remarks'],
    //                     'hod_action_at' => now(),
    //                     'status'        => 'rejected',
    //                 ],
    //             ],

    //             'admin_approve' => [
    //                 'roles' => ['admin'],
    //                 // 'from'  => fn ($l) =>
    //                 //     strtolower($l->hod_status) === 'approved'
    //                 //     && strtolower($l->admin_status) === 'pending',
    //                 'from' => fn($l) =>
    //                     in_array($this->state($l->hod_status), ['pending', 'approved'])
    //                     && $this->state($l->admin_status) === 'pending',

    //                 'apply' => fn ($l, $d) => [
    //                     'admin_status'    => 'approved',
    //                     'admin_remarks'   => $d['remarks'] ?? null,
    //                     'admin_action_at' => now(),
    //                     'status'          => 'approved',
    //                 ],
    //             ],
    //             // 'admin_approve' => [
    //             //     'role' => ['admin', 'Admin', 'ADMIN'],
    //             //     'from' => fn($l) =>
    //             //         in_array($this->state($l->hod_status), ['pending', 'approved'])
    //             //         && $this->state($l->admin_status) === 'pending',

    //             //     'apply' => fn($l, $d) => [
    //             //         'admin_status'    => 'approved',
    //             //         'admin_remarks'   => $d['remarks'] ?? null,
    //             //         'admin_action_at' => now(),
    //             //         'status'          => 'approved',
    //             //     ],
    //             // ],

    //             'admin_reject' => [
    //                 'roles' => ['admin'],
    //                 // 'from'  => fn ($l) =>
    //                 //     strtolower($l->hod_status) === 'approved'
    //                 //     && strtolower($l->admin_status) === 'pending',
    //                 'from' => fn($l) =>
    //                     in_array($this->state($l->hod_status), ['pending', 'approved'])
    //                     && $this->state($l->admin_status) === 'pending',

    //                 'apply' => fn ($l, $d) => [
    //                     'admin_status'    => 'rejected',
    //                     'admin_remarks'   => $d['remarks'],
    //                     'admin_action_at' => now(),
    //                     'status'          => 'rejected',
    //                 ],
    //             ],
    //         ];

    //         /*
    //         |------------------------------------------------------------------
    //         | VALIDATION
    //         |------------------------------------------------------------------
    //         */
    //         // $validated = $request->validate([
    //         //     'action'     => 'nullable|string',
    //         //     'remarks'    => str_contains($request->action ?? '', 'reject')
    //         //         ? 'required|string|max:500'
    //         //         : 'nullable|string|max:500',
    //         //     'attachment' => 'nullable|file|max:2048',

    //         //     // applicant fields
    //         //     'type'     => 'required|string|max:1000',
    //         //     'reason'     => 'required|string|max:1000',
    //         //     'start_date' => 'required|date',
    //         //     'end_date'   => 'required|date|after_or_equal:start_date',
    //         // ]);

    //         /*
    //         |------------------------------------------------------------------
    //         | FILE
    //         |------------------------------------------------------------------
    //         */
    //         if ($request->hasFile('attachment')) {
    //             $validated['attachment'] =
    //                 $request->file('attachment')->store('leave-actions', 'public');
    //         }

    //         /*
    //         |------------------------------------------------------------------
    //         | AUTHORITY FLOW
    //         |------------------------------------------------------------------
    //         */
    //         if ($request->filled('action')) {

    //             $rules = [
    //                     'action'  => 'required|string',
    //                     'remarks' => 'nullable|string|max:500',
    //                     'attachment' => 'nullable|file|max:2048',
    //                 ];

    //                 if (str_contains($request->action, 'reject')) {
    //                     $rules['remarks'] = 'required|string|max:500';
    //                 }

    //             $validated = $request->validate($rules);

    //             abort_unless(isset($actionMap[$validated['action']]), 422);

    //             $config = $actionMap[$validated['action']];

    //             abort_unless(
    //                 $this->userHasAnyRole($user, $config['roles']),
    //                 403
    //             );

    //             Log::error('LEAVE STATE DEBUG', [
    //                 'action'        => $validated['action'],
    //                 'hod_status'    => $leave->hod_status,
    //                 'admin_status'  => $leave->admin_status,
    //                 'status'        => $leave->status,
    //                 'from_result'   => $config['from']($leave),
    //             ]);
    //             abort_if(
    //                 !$config['from']($leave),
    //                 409,
    //                 'Invalid leave state'
    //             );

    //             $updates = $config['apply']($leave, $validated);

    //             if (isset($validated['attachment'])) {
    //                 $updates['attachment'] = $validated['attachment'];
    //             }

    //             // Approval history
    //             $approvals   = $leave->approvals ?? [];
    //             $approvals[] = [
    //                 // 'role'      => strtoupper($config['roles'][0]),
    //                 'role'      => ucfirst($config['roles'][0]),
    //                 'status'    => str_contains($validated['action'], 'reject')
    //                     ? 'rejected'
    //                     : 'approved',
    //                 'remarks'   => $validated['remarks'] ?? null,
    //                 'attachment'   => $validated['remarks'] ?? null,
    //                 'action_by' => $user->name,
    //                 'action_at' => now()->toDateTimeString(),
    //             ];

    //             $leave->update($updates + ['approvals' => $approvals]);
    //         }

    //         /*
    //         |------------------------------------------------------------------
    //         | APPLICANT SELF EDIT
    //         |------------------------------------------------------------------
    //         */
    //         else {
    //             abort_if(
    //                 strtolower($leave->hod_status) !== 'pending',
    //                 409,
    //                 'Leave cannot be modified after review'
    //             );

    //             // abort_unless($leave->resident->user_id === $user->id, 403);

    //             $leave->update(
    //                 collect($validated)->only([
    //                     'reason',
    //                     'start_date',
    //                     'end_date',
    //                 ])->toArray()
    //             );
    //         }

    //         DB::commit();

    //         return $request->expectsJson()
    //             ? $this->success('Leave updated successfully', $leave->fresh())
    //             : back()->with('swal_success', 'Leave updated successfully');

    //     } catch (\Throwable $e) {
    //         DB::rollBack();

    //         Log::error('Leave Update Error', [
    //             'leave_id' => $id,
    //             'user_id'  => optional($user)->id,
    //             'error'    => $e->getMessage(),
    //         ]);

    //         return $this->handleWebOrApiError($request, 'Leave update failed');
    //     }
    // }

    public function update(Request $request, $id)
    {
        // Log::info('update request', $request->all());
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
            if ($request->filled('action')) {

                $validated = $request->validate([
                    'action'     => 'required|string',
                    'remarks'    => str_contains($request->action, 'reject')
                        ? 'required|string|max:500'
                        : 'nullable|string|max:500',
                    'attachment' => 'nullable|file|max:2048',
                ]);

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

                $approvals[] = [
                    'role'        => ucfirst($config['roles'][0]), // hod / admin
                    'action'      => $validated['action'],
                    'status'      => str_contains($validated['action'], 'reject')
                        ? 'rejected'
                        : 'approved',
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
                    'attachment'  => 'nullable|file|max:2048',
                ]);

                $attachmentPath = null;

                if ($request->hasFile('attachment')) {
                    $attachmentPath = $request->file('attachment')
                        ->store('leave-applications', 'public');
                }

                // Update editable fields
                $leave->update(
                    collect($validated)->only([
                        'type',
                        'reason',
                        'description',
                        'start_date',
                        'end_date',
                    ])->toArray()
                );

                if ($attachmentPath) {
                    $leave->update(['attachment' => $attachmentPath]);
                }


                // Track applicant change in approvals
                $approvals = $leave->approvals ?? [];

                $approvals[] = [
                    'role'        => 'Applicant',
                    'action'      => 'updated',
                    'remarks'     => null,
                    'attachment'  => $attachmentPath,
                    'action_by'   => $user->name,
                    'action_at'   => now()->toDateTimeString(),
                ];

                $leave->update(['approvals' => $approvals]);
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
                // ? $this->error('Leave update failed', ['exception' => $e->getMessage()], 500) 
                ? $this->error('Leave update failed', [], 500)
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
