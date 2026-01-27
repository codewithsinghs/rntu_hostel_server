<?php

namespace App\Http\Controllers\ApiV1;

use Throwable;
use App\Models\User;
use App\Models\Resident;
use Illuminate\Support\Js;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Services\DataTableService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ResidentResController extends Controller
{
    use ApiResponses;

    /**
     * Display a listing of residents
     */
    // public function index(Request $request)
    // {
    //     try {
    //         $user = $request->user();
    //         // $residents = Resident::latest()->get();
    //         $residents = Resident::with(['user'])
    //             ->whereHas('user', function ($q) use ($user) {
    //                 $q->where('university_id', $user->university_id);
    //             }, '<=', 1)
    //             ->get();


    //         // $residents = $residents->getCollection()
    //         //     ->map(fn ($r) => $r->getFullInfo());

    //         if ($request->expectsJson()) {
    //             return $this->success('Residents fetched successfully', $residents);
    //         }

    //         return view('residents.index', compact('residents'));
    //     } catch (Throwable $e) {
    //         return $this->handleException($e, $request);
    //     }
    // }

    // public function index(Request $request)
    // {
    //     try {
    //         $user = $request->user();

    //         Log::info('user'. json_encode($user));
    //         /* ---------------------------------
    //      | Base Query (authoritative)
    //      |---------------------------------*/
    //         $query = Resident::query()
    //             ->with([
    //                 'user:id,name,email',
    //                 'bed:id,room_id,bed_no',
    //                 'bed.room:id,room_no,building_id',
    //                 'bed.room.building:id,name',
    //                 'guest:id,faculty_id,department_id,course_id',
    //                 'guest.faculty:id,name',
    //                 'guest.department:id,name',
    //                 'guest.course:id,name',
    //                 'creator:id,name'
    //             ])
    //             ->visibleFor($user); // 🔥 role + university scope

    //         /* ---------------------------------
    //      | Optional Filters (safe)
    //      |---------------------------------*/
    //         $query->when(
    //             $request->filled('status'),
    //             fn($q) =>
    //             $q->where('status', $request->status)
    //         );

    //         $query->when($request->filled('search'), function ($q) use ($request) {
    //             $q->where(function ($sq) use ($request) {
    //                 $sq->where('name', 'like', "%{$request->search}%")
    //                     ->orWhere('mobile', 'like', "%{$request->search}%")
    //                     ->orWhereHas(
    //                         'user',
    //                         fn($uq) =>
    //                         $uq->where('email', 'like', "%{$request->search}%")
    //                     );
    //             });
    //         });

    //         /* ---------------------------------
    //      | Pagination / Collection
    //      |---------------------------------*/
    //         $perPage = (int) $request->get('per_page', 10);
    //         $residents = $request->boolean('all')
    //             ? $query->latest()->get()
    //             : $query->latest()->paginate($perPage);

    //         /* ---------------------------------
    //      | Controlled Output
    //      |---------------------------------*/
    //         $records = $residents instanceof \Illuminate\Pagination\AbstractPaginator
    //             ? $residents->getCollection()->map->summary()
    //             : $residents->map->summary();

    //         /* ---------------------------------
    //      | API / AJAX Response
    //      |---------------------------------*/
    //         if ($request->expectsJson()) {
    //             return $this->success('Residents fetched successfully', [
    //                 'records' => $records,
    //                 'meta'    => $residents instanceof \Illuminate\Pagination\AbstractPaginator
    //                     ? [
    //                         'total'        => $residents->total(),
    //                         'per_page'     => $residents->perPage(),
    //                         'current_page' => $residents->currentPage(),
    //                         'last_page'    => $residents->lastPage(),
    //                     ]
    //                     : null
    //             ]);
    //         }

    //         /* ---------------------------------
    //      | Blade View Response
    //      |---------------------------------*/
    //         return view('residents.index', [
    //             'residents'  => $records,
    //             // 'pagination' => $residents instanceof \Illuminate\Pagination\AbstractPaginator
    //             //     ? $residents
    //             //     : null
    //         ]);
    //     } catch (\Throwable $e) {

    //         /* ---------------------------------
    //      | Unified Error Handling
    //      |---------------------------------*/
    //         return $request->expectsJson()
    //             ? $this->error('Failed to load residents', [
    //                 'exception' => config('app.debug') ? $e->getMessage() : null
    //             ], 500)
    //             : back()->with('error', 'Failed to load residents');
    //     }
    // }

    // Client Side
    // public function index(Request $request)
    // {
    //     try {
    //         $admin = $request->user();

    //         if (!$admin) {
    //             return $this->error('Unauthenticated', [], 401);
    //         }

    //         /* ---------------------------------
    //      | Base Query (lightweight)
    //      |---------------------------------*/
    //         $residents = Resident::query()
    //             // ->select(['id', 'name', 'number', 'status', 'bed_id', 'user_id', ])
    //             ->with([
    //                 // ONLY what index really needs
    //                 'user:id,university_id,name,email',
    //                 'profile',
    //                 'bed.room.building',
    //             ])
    //             ->visibleFor($admin)
    //             ->latest()
    //             ->get();

    //         // Log::info('residents' . json_encode($residents));
    //         /* ---------------------------------
    //      | Controlled Output
    //      |---------------------------------*/
    //         // $records = $residents->map->getHostelInfo();
    //         $records = $residents->map(function ($resident) {
    //             $hostel = $resident->getHostelInfo();
    //             $academic = $resident->getAcademicInfo();
    //             // $academic = $resident->getAcademicFromUserHierarchy();

    //             return [
    //                 'id'             => $resident->id,
    //                 'scholar_no'     => $resident->scholar_no,
    //                 'name'           => $resident->name,
    //                 'email'          => $resident->email,
    //                 'mobile'         => $resident->number,
    //                 'gender'         => $resident->gender,
    //                 'parent_contact' => $resident->parent_no ?? $resident->profile->parent_mobile,
    //                 'fathers_name'   => $resident->fathers_name ?? $resident->profile->fathers_name,
    //                 'mothers_name'   => $resident->mothers_name ?? $resident->profile->mothers_name,
    //                 // 'guardians_name'   => $resident->guardian_name ?? $resident->profile->guardian_name,
    //                 'guardians_name' => $resident->guardian_name ?? optional($resident->profile)->guardian_name,
    //                 'guardians_contact'   => $resident->guardian_no ?? $resident->profile->guardian_mobile,

    //                 // 'check_in_date'  => $resident->check_in_date
    //                 //     ? \Carbon\Carbon::parse($resident->check_in_date)->format('d M Y H i A')
    //                 //     : null,
    //                 'check_in_date'  => $resident->check_in_date ?? null,
    //                 // ? \Carbon\Carbon::parse($resident->check_in_date)->format('d M Y H i A')
    //                 // : null,
    //                 'status'         => $resident->status,
    //                 'created_at'     => $resident->created_at
    //                     ? \Carbon\Carbon::parse($resident->created_at)->format('d M Y')
    //                     : null,
    //                 'hostel'         => $hostel,
    //                 // 'course' => $resident->profile->course,
    //                 'academic'       => $academic,
    //                 // 'university'     => optional($resident->getUniversity())->name,
    //                 // 'bed'            => optional($resident->bed)->bed_number,
    //                 // 'room'           => optional($resident->bed?->room)->room_number,
    //                 // 'building'       => optional($resident->bed?->room?->building)->name,
    //             ];
    //         });


    //         if ($request->expectsJson()) {
    //             return $this->success('Residents fetched successfully', $records);
    //         }

    //         return view('residents.index', compact('residents'));
    //         // return $this->success('Residents fetched successfully', [
    //         //     'records' => $records,
    //         //     'count'   => $records->count(),
    //         // ]);
    //     } catch (Throwable $e) {
    //         Log::error('Resident index exception', [
    //             'message' => $e->getMessage(),
    //             // 'trace'   => $e->getTraceAsString()
    //         ]);

    //         if (config('app.debug')) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => $e->getMessage(),
    //                 'trace'   => $e->getTrace(),
    //             ], 500);
    //         }


    //         return $this->handleException($e, $request);
    //     }
    // }

    // ServerSide
    // public function index(Request $request)
    // {
    //     Log::info('Resident index called');
    //     try {
    //         $admin = $request->user();

    //         if (!$admin) {
    //             return $this->error('Unauthenticated', [], 401);
    //         }

    //         $query = Resident::query()
    //             ->with([
    //                 'user:id,university_id,name,email',
    //                 'profile',
    //                 'bed.room.building',
    //             ])
    //             ->visibleFor($admin)
    //             ->latest();

    //         if ($request->expectsJson()) {
    //             return response()->json(
    //                 DataTableService::fromQuery(
    //                     $request,
    //                     $query,
    //                     function ($resident) {

    //                         return [
    //                             'id'         => $resident->id,
    //                             'scholar_no' => $resident->scholar_no,
    //                             'name'       => $resident->name,
    //                             'email'      => $resident->email,
    //                             'mobile'     => $resident->number,
    //                             'gender'     => $resident->gender,

    //                             'parent_contact' =>
    //                             $resident->parent_no
    //                                 ?? optional($resident->profile)->parent_mobile,

    //                             'fathers_name' =>
    //                             $resident->fathers_name
    //                                 ?? optional($resident->profile)->fathers_name,

    //                             'mothers_name' =>
    //                             $resident->mothers_name
    //                                 ?? optional($resident->profile)->mothers_name,

    //                             'guardians_name' =>
    //                             $resident->guardian_name
    //                                 ?? optional($resident->profile)->guardian_name,

    //                             'guardians_contact' =>
    //                             $resident->guardian_no
    //                                 ?? optional($resident->profile)->guardian_mobile,

    //                             'check_in_date' => $resident->check_in_date,
    //                             'status'        => $resident->status,

    //                             'created_at' =>
    //                             optional($resident->created_at)->format('d M Y'),

    //                             'hostel'   => $resident->getHostelInfo(),
    //                             'academic' => $resident->getAcademicInfo(),
    //                         ];
    //                     }
    //                 )
    //             );
    //         }

    //         return view('residents.index');
    //     } catch (\Throwable $e) {
    //         \Log::error('Resident index exception', [
    //             'message' => $e->getMessage()
    //         ]);

    //         return $this->handleException($e, $request);
    //     }
    // }

    // public function index(Request $request)
    // {
    //     Log::info('Resident index called');

    //     try {
    //         $admin = $request->user();

    //         if (!$admin) {
    //             return $this->error('Unauthenticated', [], 401);
    //         }

    //         /* ---------------------------------
    //      | Base Query
    //      |---------------------------------*/
    //         $query = Resident::query()
    //             ->with([
    //                 'user:id,university_id,name,email',
    //                 'profile',
    //                 'bed.room.building',
    //             ])
    //             ->visibleFor($admin)
    //             ->latest();

    //         /* ---------------------------------
    //      | Global Search (DataTables)
    //      |---------------------------------*/
    //         if ($request->expectsJson()) {

    //             $search = trim($request->input('search.value'));

    //             if ($search !== '') {
    //                 $query->where(function ($q) use ($search) {

    //                     /* ===== Resident columns ===== */
    //                     $q->where('residents.name', 'like', "%{$search}%")
    //                         ->orWhere('residents.email', 'like', "%{$search}%")
    //                         ->orWhere('residents.number', 'like', "%{$search}%")
    //                         ->orWhere('residents.scholar_no', 'like', "%{$search}%")
    //                         ->orWhere('residents.gender', 'like', "%{$search}%")
    //                         ->orWhere('residents.status', 'like', "%{$search}%");

    //                     /* ===== Profile relation ===== */
    //                     $q->orWhereHas('profile', function ($p) use ($search) {
    //                         $p->where('parent_mobile', 'like', "%{$search}%")
    //                             ->orWhere('fathers_name', 'like', "%{$search}%")
    //                             ->orWhere('mothers_name', 'like', "%{$search}%")
    //                             ->orWhere('guardian_name', 'like', "%{$search}%")
    //                             ->orWhere('guardian_mobile', 'like', "%{$search}%");
    //                     });

    //                     /* ===== User relation ===== */
    //                     $q->orWhereHas('user', function ($u) use ($search) {
    //                         $u->where('name', 'like', "%{$search}%")
    //                             ->orWhere('email', 'like', "%{$search}%");
    //                     });

    //                     /* ===== Hostel hierarchy ===== */
    //                     $q->orWhereHas('bed.room.building', function ($b) use ($search) {
    //                         $b->where('name', 'like', "%{$search}%");
    //                     });

    //                     $q->orWhereHas('bed.room', function ($r) use ($search) {
    //                         $r->where('room_number', 'like', "%{$search}%");
    //                     });

    //                     $q->orWhereHas('bed', function ($b) use ($search) {
    //                         $b->where('bed_number', 'like', "%{$search}%");
    //                     });
    //                 });
    //             }

    //             /* ---------------------------------
    //          | DataTable Response
    //          |---------------------------------*/
    //             return response()->json(
    //                 DataTableService::fromQuery(
    //                     $request,
    //                     $query,
    //                     function ($resident) {

    //                         return [
    //                             'id'         => $resident->id,
    //                             'scholar_no' => $resident->scholar_no,
    //                             'name'       => $resident->name,
    //                             'email'      => $resident->email,
    //                             'mobile'     => $resident->number,
    //                             'gender'     => strtolower(trim($resident->gender ?? '')),

    //                             'parent_contact' =>
    //                             $resident->parent_no
    //                                 ?? optional($resident->profile)->parent_mobile,

    //                             'fathers_name' =>
    //                             $resident->fathers_name
    //                                 ?? optional($resident->profile)->fathers_name,

    //                             'mothers_name' =>
    //                             $resident->mothers_name
    //                                 ?? optional($resident->profile)->mothers_name,

    //                             'guardians_name' =>
    //                             $resident->guardian_name
    //                                 ?? optional($resident->profile)->guardian_name,

    //                             'guardians_contact' =>
    //                             $resident->guardian_no
    //                                 ?? optional($resident->profile)->guardian_mobile,

    //                             'check_in_date' => $resident->check_in_date,
    //                             'status'        => $resident->status,

    //                             'created_at' =>
    //                             optional($resident->created_at)->format('d M Y'),

    //                             'hostel'   => $resident->getHostelInfo(),
    //                             'academic' => $resident->getAcademicInfo(),
    //                         ];
    //                     }
    //                 )
    //             );
    //         }

    //         return view('residents.index');
    //     } catch (\Throwable $e) {

    //         Log::error('Resident index exception', [
    //             'message' => $e->getMessage(),
    //             'file'    => $e->getFile(),
    //             'line'    => $e->getLine(),
    //         ]);

    //         return $this->handleException($e, $request);
    //     }
    // }


    public function index(Request $request)
    {
        // Log::info('Resident index called');

        try {
            $admin = $request->user();

            if (!$admin) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }

            /* ---------------------------------
         | Base Query
         |---------------------------------*/
            $query = Resident::query()
                ->with([
                    'user:id,university_id,name,email',
                    'profile',
                    'bed.room.building',
                ])
                ->visibleFor($admin);
            // ->latest();

            /* ---------------------------------
         | DataTables Params
         |---------------------------------*/
            $draw   = (int) $request->input('draw');
            $start  = (int) $request->input('start', 0);
            $length = (int) $request->input('length', 10);
            $search = trim($request->input('search.value'));

            /* ---------------------------------
         | Total Records (before search)
         |---------------------------------*/
            $recordsTotal = (clone $query)->count();

            /* ---------------------------------
         | Global Search (ALL IMPORTANT COLUMNS)
         |---------------------------------*/
            if ($search !== '') {
                $query->where(function ($q) use ($search) {

                    // Resident table
                    $q->where('residents.name', 'like', "%{$search}%")
                        ->orWhere('residents.email', 'like', "%{$search}%")
                        ->orWhere('residents.number', 'like', "%{$search}%")
                        ->orWhere('residents.scholar_no', 'like', "%{$search}%")
                        ->orWhere('residents.gender', 'like', "%{$search}%")
                        ->orWhere('residents.status', 'like', "%{$search}%");

                    // User table
                    $q->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });

                    // Profile table
                    $q->orWhereHas('profile', function ($pq) use ($search) {
                        $pq->where('parent_mobile', 'like', "%{$search}%")
                            ->orWhere('guardian_mobile', 'like', "%{$search}%")
                            ->orWhere('fathers_name', 'like', "%{$search}%")
                            ->orWhere('mothers_name', 'like', "%{$search}%")
                            ->orWhere('guardian_name', 'like', "%{$search}%");
                    });

                    // Hostel hierarchy
                    $q->orWhereHas('bed.room.building', function ($hq) use ($search) {
                        $hq->where('buildings.name', 'like', "%{$search}%")
                            ->orWhere('rooms.room_number', 'like', "%{$search}%")
                            ->orWhere('beds.bed_number', 'like', "%{$search}%");
                    });
                });
            }

            /* ---------------------------------
         | Records After Search
         |---------------------------------*/
            $recordsFiltered = (clone $query)->count();

            /* ---------------------------------
                | Ordering (DATA-NAME BASED — SAFE)
                |---------------------------------*/
            $orderColumnIndex = $request->input('order.0.column');

            $orderDirection   = $request->input('order.0.dir', 'desc');

            // 🔑 ONLY use column NAME for SQL
            $columnName = $request->input("columns.$orderColumnIndex.name");

            // Fallback to `data` if name not provided
            if (!$columnName) {
                $columnName = $request->input("columns.$orderColumnIndex.data");
            }

           // Log::info("Ordering request → column: {$columnName}, direction: {$orderDirection}");

            // Ignore null / object / array columns
            // Normalize column name (remove nested dots like hostel.building)
            // if (strpos($columnName, '.') !== false) {
            //     $parts = explode('.', $columnName);
            //     $columnName = end($parts); // e.g., 'hostel.building' → 'building'
            // }

            // Log::info("Ordering request → column: {$columnName}, direction: {$orderDirection}");
            if (!is_string($columnName)) {
                $columnName = null;
            }



            /*
                |--------------------------------------------------------------------------
                | Whitelisted sortable columns (DB only)
                |--------------------------------------------------------------------------
                */
            $columnMap = [
                'scholar_no'    => 'residents.scholar_no',
                'name'          => 'residents.name',
                'email'         => 'residents.email',
                'mobile'        => 'residents.number',
                'gender'        => 'residents.gender',
                'check_in_date' => 'residents.check_in_date',
                'status'        => 'residents.status',
                'created_at'    => 'residents.created_at',
                'fathers_name'   => 'residents.fathers_name',
                'mothers_name'   => 'residents.mothers_name',

                // Hostel related
                'building'      => 'building_name',
                'room'          => 'room_number',
                'bed'           => 'bed_number',
            ];

            /*
                |--------------------------------------------------------------------------
                | Conditionally apply hostel joins
                |--------------------------------------------------------------------------
                */

            if (in_array($columnName, ['building', 'room', 'bed'])) {

                // Log::info('Applying hostel joins for ordering');

                $query
                    ->leftJoin('beds', 'beds.id', '=', 'residents.bed_id')
                    ->leftJoin('rooms', 'rooms.id', '=', 'beds.room_id')
                    ->leftJoin('buildings', 'buildings.id', '=', 'rooms.building_id')
                    ->addSelect([
                        'residents.*',
                        'buildings.name as building_name',
                        'rooms.room_number as room_number',
                        'beds.bed_number as bed_number',
                    ]);
            }

            /*
                |--------------------------------------------------------------------------
                | Apply ordering safely
                |--------------------------------------------------------------------------
                */
            // if (isset($sortableColumns[$columnData])) {
            //     $query->orderBy($sortableColumns[$columnData], $orderDirection);
            // } else {
            //     $query->latest('residents.created_at', 'desc');
            // }
            /*
                |--------------------------------------------------------------------------
                | Apply ORDER BY
                |--------------------------------------------------------------------------
                */
            if (isset($columnMap[$columnName])) {

                // Log::info('Ordering SQL column: ' . $columnMap[$columnName]);

                $query->orderBy($columnMap[$columnName], $orderDirection);
            } else {
                $query->latest('residents.created_at', 'desc');
            }


            /* ---------------------------------
            | Summary Data (ONLY ON FIRST DRAW)
            |---------------------------------*/
            $summary = null;

            if ((int) $draw === 1) {
                $summary = [
                    'total_residents'   => $recordsTotal,
                    'active_residents'  => (clone $query)->where('residents.status', 'active')->count(),
                    'inactive_residents' => (clone $query)->where('residents.status', 'inactive')->count(),
                    'male_count'        => (clone $query)->where('residents.gender', 'male')->count(),
                    'female_count'      => (clone $query)->where('residents.gender', 'female')->count(),
                    'other_gender'      => (clone $query)->where('residents.gender', 'other')->count(),
                    'checked_in_today'  => (clone $query)
                        ->whereDate('residents.check_in_date', today())
                        ->count(),
                ];
            }



            /* ---------------------------------
         | Pagination
         |---------------------------------*/
            $residents = $query
                ->skip($start)
                ->take($length)
                ->get();

            /* ---------------------------------
         | Data Mapping (Frontend-safe)
         |---------------------------------*/
            $counter = $start + 1;

            $data = $residents->map(function ($resident) use (&$counter) {

                return [
                    // 'DT_RowIndex' => $counter++,

                    'id'         => $resident->id,
                    'scholar_no' => $resident->scholar_no,
                    'name'       => $resident->name,
                    'email'      => $resident->email,
                    'mobile'     => $resident->number,
                    'gender'     => strtolower(trim($resident->gender ?? '')),

                    'parent_contact' =>
                    $resident->parent_no
                        ?? optional($resident->profile)->parent_mobile,

                    'fathers_name' =>
                    $resident->fathers_name
                        ?? optional($resident->profile)->fathers_name,

                    'mothers_name' =>
                    $resident->mothers_name
                        ?? optional($resident->profile)->mothers_name,

                    'guardians_name' =>
                    $resident->guardian_name
                        ?? optional($resident->profile)->guardian_name,

                    'guardians_contact' =>
                    $resident->guardian_no
                        ?? optional($resident->profile)->guardian_mobile,
                    'emergency_contact' =>
                    $resident->emergency_no ?? optional($resident->guest)->emergency_no
                        ?? optional($resident->profile)->emergency_contact,

                    'check_in_date' => $resident->check_in_date,
                    'status'        => $resident->status,

                    'created_at' =>
                    optional($resident->created_at)->format('d M Y'),

                    'hostel'   => $resident->getHostelInfo(),
                    'academic' => $resident->getAcademicInfo(),
                ];
            });

            /* ---------------------------------
         | Final DataTables Response
         |---------------------------------*/
            if ($request->expectsJson()) {
                return response()->json([
                    'draw'            => $draw,
                    'recordsTotal'    => $recordsTotal,
                    'recordsFiltered' => $recordsFiltered,
                    'data'            => $data,
                    'summary'         => $summary, // null after first draw
                ]);
            }

            return view('residents.index');
        } catch (\Throwable $e) {

            Log::error('Resident index exception', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'message' => 'Something went wrong'
            ], 500);
        }
    }




    /**
     * Show the form for creating a resident
     */
    public function create(Request $request)
    {
        if ($request->expectsJson()) {
            return $this->error('Create form not applicable for API', [], 405);
        }

        return view('residents.create');
    }

    /**
     * Store a newly created resident
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $this->validateResident($request);

            // Create User
            $user = User::create([
                'name'          => $validated['name'],
                'email'         => $validated['email'],
                'university_id' => $validated['university_id'],
                'password'      => bcrypt(Str::random(12)), // Random password (can send email to reset)
            ]);

            // Assign 'resident' role
            if (! $user->hasRole('resident')) {
                $user->assignRole('resident');
            }

            // Create Resident
            $resident = Resident::create([
                'user_id'      => $user->id,
                'scholar_no'   => $validated['scholar_no'],
                'gender'       => $validated['gender'],
                'check_in_date' => $validated['check_in_date'] ?? now(),
                'bed_id'       => $validated['bed_id'],
                'status'       => $validated['status'],
            ]);

            // $resident = Resident::create($validated);

            // Create Profile
            $resident->profile()->create([
                'number'        => $validated['number'],
                'parent_no'     => $validated['parent_no'] ?? null,
                'guardian_no'   => $validated['guardian_no'] ?? null,
                'fathers_name'  => $validated['fathers_name'] ?? null,
                'mothers_name'  => $validated['mothers_name'] ?? null,
                'faculty_id'    => $validated['faculty_id'] ?? null,
                'department_id' => $validated['department_id'] ?? null,
                'course_id'     => $validated['course_id'] ?? null,
            ]);

            DB::commit();

            // Controlled payload for response
            // $payload = [
            //     'id'       => $resident->id,
            //     'name'     => $user->name,
            //     'email'    => $user->email,
            //     'scholar_no' => $resident->scholar_no,
            //     'number'   => $resident->profile->number,
            //     'status'   => $resident->status,
            //     'check_in_date' => $resident->check_in_date,
            //     'academic' => [
            //         'university' => $user->university?->name,
            //         'faculty'    => $resident->profile?->faculty?->name,
            //         'department' => $resident->profile?->department?->name,
            //         'course'     => $resident->profile?->course?->name,
            //     ],
            //     'hostel' => [
            //         'building' => $resident->bed?->room?->hostel?->name,
            //         'room'     => $resident->bed?->room?->number,
            //         'bed'      => $resident->bed?->number,
            //     ],
            // ];

            if ($request->expectsJson()) {
                return $this->success('Resident created successfully', $resident, 201);
            }

            return redirect()
                ->route('residents.index')
                ->with('success', 'Resident created successfully');
        } catch (Throwable $e) {
            DB::rollBack();

            return $this->handleException($e, $request);
        }
    }

    /**
     * Display the specified resident
     */
    public function show(Request $request, $id)
    {
        try {
            $resident = Resident::with('profile', 'guest')->findOrFail($id);

            $record = [
                'id'  => $resident->id ?? null,
                'name'  => $resident->name ?? null,
                'email'  => $resident->email ?? null,
                'gender'  => $resident->gender ?? null,
                'scholar_no'  => $resident->scholar_no ?? null,
                'mobile'  => $resident->number ?? null,
                'parent_contact'  => $resident->parent_no ?? null,
                'guardian_contact'  => $resident->guardian_no ?? null,
                'emergency_contact' =>
                    $resident->emergency_no ?? optional($resident->guest)->emergency_no
                        ?? optional($resident->profile)->emergency_contact,
                'fathers_name'  => $resident->fathers_name ?? null,
                'mothers_name'  => $resident->mothers_name ?? null,
                'guardians_name'  => $resident->profile->guardian_name ?? null,
                'check_in_date'  => $resident->check_in_date ?? null,
                'check_out_date'  => $resident->check_out_date ?? null,
                'status' => $resident->status ?? null,
            ];

            if ($request->expectsJson()) {
                return $this->success('Resident details fetched', $record);
            }

            return view('residents.show', compact('resident'));
        } catch (Throwable $e) {
            return $this->handleException($e, $request);
        }
    }

    // public function show(Request $request, $id)
    // {
    //     try {
    //         // Fetch resident along with related models in a single query
    //         $resident = Resident::with(['user', 'profile', 'bed.room.hostel'])->findOrFail($id);

    //         // Prepare controlled, structured payload
    //         $payload = [
    //             'id' => $resident->id,

    //             'personal' => [
    //                 'scholar_no' => $resident->scholar_no,
    //                 'name'       => $resident->user?->name,
    //                 'email'      => $resident->user?->email,
    //                 'gender'     => $resident->gender,
    //                 'number'     => $resident->profile?->number,
    //             ],

    //             'family' => [
    //                 'fathers_name' => $resident->profile?->fathers_name,
    //                 'mothers_name' => $resident->profile?->mothers_name,
    //                 'parent_no'    => $resident->profile?->parent_no,
    //                 'guardian_no'  => $resident->profile?->guardian_no,
    //             ],

    //             'stay' => [
    //                 'check_in_date'  => $resident->check_in_date,
    //                 'check_out_date' => $resident->check_out_date,
    //             ],

    //             'academic' => [
    //                 'university' => $resident->user?->university?->name ?? null,
    //                 'faculty'    => $resident->profile?->faculty?->name ?? null,
    //                 'department' => $resident->profile?->department?->name ?? null,
    //                 'course'     => $resident->profile?->course?->name ?? null,
    //             ],

    //             'hostel' => [
    //                 'building' => $resident->bed?->room?->hostel?->name ?? null,
    //                 'room'     => $resident->bed?->room?->number ?? null,
    //                 'bed'      => $resident->bed?->number ?? null,
    //             ],

    //             'status'      => $resident->status,
    //             'created_at'  => $resident->created_at?->toDateTimeString(),
    //             'updated_at'  => $resident->updated_at?->toDateTimeString(),
    //         ];

    //         // Return JSON response if requested
    //         if ($request->expectsJson()) {
    //             return response()->json([
    //                 'status'  => true,
    //                 'message' => 'Resident details fetched successfully',
    //                 'data'    => $payload,
    //             ]);
    //         }

    //         // Otherwise, return Blade view
    //         return view('residents.show', compact('payload'));
    //     } catch (ModelNotFoundException $e) {
    //         // Resident not found
    //         if ($request->expectsJson()) {
    //             return response()->json([
    //                 'status'  => false,
    //                 'message' => 'Resident not found',
    //                 'errors'  => ['exception' => $e->getMessage()],
    //             ], 404);
    //         }
    //         return redirect()->route('residents.index')->withErrors('Resident not found.');
    //     } catch (Throwable $e) {
    //         // Catch any other exception
    //         if ($request->expectsJson()) {
    //             return response()->json([
    //                 'status'  => false,
    //                 'message' => 'Something went wrong',
    //                 'errors'  => ['exception' => $e->getMessage()],
    //             ], 500);
    //         }
    //         return redirect()->route('residents.index')->withErrors('Something went wrong. Please try again.');
    //     }
    // }


    /**
     * Show the form for editing the resident
     */
    public function edit(Request $request, $id)
    {
        try {
            $resident = Resident::findOrFail($id);

            if ($request->expectsJson()) {
                return $this->error('Edit form not applicable for API', [], 405);
            }

            return view('residents.edit', compact('resident'));
        } catch (Throwable $e) {
            return $this->handleException($e, $request);
        }
    }

    /**
     * Update the specified resident
     */
    // public function update(Request $request, $id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $resident = Resident::findOrFail($id);

    //         $validated = $this->validateResident($request, $id);

    //         $resident->update($validated);

    //         DB::commit();

    //         if ($request->expectsJson()) {
    //             return $this->success('Resident updated successfully', $resident);
    //         }

    //         return redirect()
    //             ->route('residents.index')
    //             ->with('success', 'Resident updated successfully');
    //     } catch (Throwable $e) {
    //         DB::rollBack();
    //         return $this->handleException($e, $request);
    //     }
    // }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        // Log::info('res update', $request->all());
        try {
            $resident = Resident::with(['user', 'profile'])->findOrFail($id);

            $validated = $this->validateResident($request, $id);

            /* ---------------------------------
         | Update Resident (only allowed fields)
         |---------------------------------*/
            // Define the DB columns you want to allow for residents


            //             $allowedResidentFields = [
            //                 'scholar_no',
            //                 'gender',
            //                 'mobile',
            //                 'parent_no',
            //                 'guardian_no',
            //                 'check_in_date',
            //                 'check_out_date',
            //                 'fathers_name',
            //                 'mothers_name',
            //                 'bed_id',
            //                 'status'
            //             ];
            //             $toUpdate = [];
            // foreach ($allowedFields as $field) {
            //     $toUpdate[$field] = $request->input($field, $resident->$field);
            // }
            $toUpdate = [
                'scholar_no'    => $validated['scholar_no']    ?? $resident->scholar_no,
                'gender'        => $validated['gender']        ?? $resident->gender,
                'mobile'        => $validated['mobile']        ?? $resident->mobile,
                'parent_no'     => $validated['parent_no']     ?? $resident->parent_no,
                'guardian_no'   => $validated['guardian_no']   ?? $resident->guardian_no,
                'fathers_name'  => $validated['fathers_name']  ?? $resident->fathers_name,
                'mothers_name'  => $validated['mothers_name']  ?? $resident->mothers_name,
                'check_in_date' => $validated['check_in_date'] ?? $resident->check_in_date,
                'check_out_date' => $validated['check_out_date'] ?? $resident->check_out_date,
                'bed_id'        => $validated['bed_id']        ?? $resident->bed_id,
                'status'        => $validated['status']        ?? $resident->status,
            ];

            $resident->update($toUpdate);


            // Log::info('res data to up' . json_encode($residentData));

            // if (!empty($residentData)) {
            //     $resident->update($residentData);
            // }

            /* ---------------------------------
         | Update User (name, email)
         |---------------------------------*/
            if ($resident->user) {
                // $userData = Arr::only($validated, ['name', 'email']);
                // ✅ Update related user fields (name, email)

                // if (!empty($userData)) {
                //     $resident->user->update($userData);
                // }

                $userData = [];
                if (isset($validated['name'])) {
                    $userData['name'] = $validated['name'];
                }
                if (isset($validated['email'])) {
                    $userData['email'] = $validated['email'];
                }
                if (isset($validated['mobile'])) {
                    $userData['mobile'] = $validated['mobile'];
                }
                if (!empty($userData) && $resident->user) {
                    $resident->user->update($userData);
                }
            }

            /* ---------------------------------
         | Update Profile (if exists)
         |---------------------------------*/
            if ($resident->profile) {

                // Define allowed fields for profile update
                $allowedProfileFields = [
                    'name',
                    'gender',
                    'dob',
                    'mobile',
                    'alternate_mobile',
                    'email',
                    'address_line1',
                    'address_line2',
                    'city',
                    'state',
                    'country',
                    'pincode',
                    'father_name',
                    'father_mobile',
                    'mother_name',
                    'mother_mobile',
                    'parent_mobile',
                    'guardian_name',
                    'guardian_mobile',
                    'guardian_relation',
                    'emergency_name',
                    'emergency_relation',
                    'emergency_mobile',
                    'aadhaar_number',
                    'aadhaar_document',
                    'image',
                    'signature',
                    'scholar_number',
                    'course',
                    'branch',
                    'semester',
                    'admission_year',
                    'is_hosteler',
                    'hostel_status',
                    'joining_date',
                    'leaving_date',
                    'blood_group',
                    'medical_conditions',
                    'remarks',
                    'others'
                ];

                // Prepare only allowed fields from validated data
                $profileData = [];
                foreach ($allowedProfileFields as $field) {
                    if (array_key_exists($field, $validated)) {
                        $profileData[$field] = $validated[$field];
                    }
                }

                // Update profile safely
                if (!empty($profileData)) {
                    $resident->profile->update($profileData);
                }

                // Optional: also update related user if fields exist
                $userData = [];
                if (isset($validated['name'])) {
                    $userData['name'] = $validated['name'];
                }
                if (isset($validated['email'])) {
                    $userData['email'] = $validated['email'];
                }
                if (isset($validated['mobile'])) {
                    $userData['mobile'] = $validated['mobile'];
                }
                if (!empty($userData) && $resident->user) {
                    $resident->user->update($userData);
                }
            }

            DB::commit();

            if ($request->expectsJson()) {
                return $this->success(
                    'Resident updated successfully',
                    $resident->fresh()
                );
            }

            return redirect()
                ->route('residents.index')
                ->with('success', 'Resident updated successfully');
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Resident update failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString(),]);
            return $this->error('Validation error', $e->errors(), 422);
        } catch (ModelNotFoundException $e) {
            Log::error('Resident update failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString(),]);

            DB::rollBack();
            return $this->error('Resident not found', [], 404);
        } catch (Throwable $e) {
            Log::error('Resident update failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString(),]);

            DB::rollBack();
            return $this->handleException($e, $request);
        }
    }

    // public function updateCheckInDate(Request $request, Resident $resident)
    // {
    //     Log::info('check In update', $request->all());
    //     try {

    //         $validated = $request->validate([
    //             'check_in_date' => 'required|date',
    //         ]);

    //         $resident->update([
    //             'check_in_date' => $validated['check_in_date']
    //         ]);

    //         return $this->success('Check-in date updated successfully');
    //     } catch (Throwable $e) {
    //         return $this->handleException($e, $request);
    //     }
    // }

    public function updateCheckInDate(Request $request, Resident $resident)
    {
        Log::info('check In update', $request->all());

        Log::info('resident data' . json_encode($resident));

        try {
            $validated = $request->validate([
                'check_in_date' => 'required|date',
            ]);

            DB::transaction(function () use ($resident, $validated) {
                // Update resident check-in date
                $resident->update([
                    'check_in_date' => $validated['check_in_date']
                ]);

                // // Prepare subscription data
                // $data = DB::table('residents')
                //     ->join('invoices', 'invoices.resident_id', '=', 'residents.id')
                //     ->join('invoice_items', 'invoice_items.invoice_id', '=', 'invoices.id')
                //     ->where('residents.id', $resident->id)
                //     ->where('residents.status', 'active')
                //     ->whereNotNull('residents.check_in_date')
                //     ->select([
                //         'residents.guest_id',
                //         'residents.id as resident_id',
                //         'invoice_items.item_type',
                //         'invoice_items.item_id',
                //         // 'invoice_items.unit_price',
                //         // 'invoice_items.total_amount',
                //         DB::raw('residents.check_in_date AS start_date'),
                //         DB::raw('DATE_SUB(DATE_ADD(residents.check_in_date, INTERVAL 3 MONTH), INTERVAL 1 DAY) AS end_date'),
                //         DB::raw("'active' AS status"),
                //         // DB::raw('(invoice_items.unit_price * invoice_items.quantity) AS total_amount')
                //     ])
                //     ->get()
                //     ->map(fn($item) => (array) $item)
                //     ->toArray();

                // Log::info('subscription data' . json_encode($data));
                // // Define items to ignore 
                // $ignoreItems = ['Chair', 'Table', 'Almirah', 'Mattress'];

                // // Insert only if data exists AND no subscription already present
                // if (!empty($data)) {
                //     foreach ($data as $subscription) {
                //         // Log::info('item data: ' . json_encode([
                //         //     'item_id'   => $subscription['item_id'],
                //         //     'item_type' => $subscription['item_type'],
                //         // ]));

                //         // $exists = DB::table('subscriptions')
                //         //     ->where('resident_id', $subscription['resident_id'])
                //         //     ->where('item_id', $subscription['item_id'])
                //         //     ->where('item_type', $subscription['item_type'])
                //         //     ->exists();

                //         // if (!$exists) {
                //         //     DB::table('subscriptions')->insert($subscription);
                //         // }

                //         // Insert or update subscriptions based on resident_id + item_id + item_type 
                //         foreach ($data as $subscription) {
                //             // Skip if item_type or item_id matches ignore list 
                //             if (in_array($subscription['item_type'], $ignoreItems, true)) {
                //                 Log::info("Skipping subscription for item: {$subscription['item_type']} (ID: {$subscription['item_id']})");
                //                 continue;
                //             }
                //             DB::table('subscriptions')->updateOrInsert([
                //                 'resident_id' => $subscription['resident_id'],
                //                 'item_id' => $subscription['item_id'],
                //                 'item_type' => $subscription['item_type'],
                //             ], $subscription);
                //         }
                //     }
                // }

                $data = DB::table('residents')
                    ->join('invoices', 'invoices.resident_id', '=', 'residents.id')
                    ->join('invoice_items', 'invoice_items.invoice_id', '=', 'invoices.id')
                    ->join('accessory', 'accessory.id', '=', 'invoice_items.item_id') // join accessory
                    ->where('residents.id', $resident->id)
                    ->where('residents.status', 'active')
                    ->whereNotNull('residents.check_in_date')
                    ->where('accessory.is_active', 1) // only active accessories
                    ->select([
                        'residents.guest_id',
                        'residents.id as resident_id',
                        'invoice_items.item_type',
                        'invoice_items.item_id',
                        DB::raw('residents.check_in_date AS start_date'),
                        DB::raw('DATE_SUB(DATE_ADD(residents.check_in_date, INTERVAL 3 MONTH), INTERVAL 1 DAY) AS end_date'),
                        DB::raw("'active' AS status"),
                        // 'invoice_items.unit_price',
                        // DB::raw('(invoice_items.unit_price * invoice_items.quantity) AS item_total_amount'),
                    ])
                    ->get()
                    ->map(fn($item) => (array) $item)
                    ->toArray();

                // Define items to ignore
                // $ignoreItems = ['Chair', 'Table', 'Almirah', 'Mattress'];
                $ignoreItems = ['17', '18', '19', '21'];

                if (!empty($data)) {
                    foreach ($data as $subscription) {
                        // Skip only if item_type is accessory AND item_id is in ignore list
                        if ($subscription['item_type'] === 'accessory' && in_array($subscription['item_id'], $ignoreItems, true)) {
                            Log::info("Skipping subscription for accessory item: {$subscription['item_id']}");
                            continue;
                        }

                        DB::table('subscriptions')->updateOrInsert(
                            [
                                'resident_id' => $subscription['resident_id'],
                                'item_id'     => $subscription['item_id'],
                                'item_type'   => $subscription['item_type'],
                            ],
                            $subscription
                        );
                    }
                }
            });

            return $this->success('Check-in date updated and subscriptions migrated successfully');
        } catch (Throwable $e) {
            return $this->handleException($e, $request);
        }
    }



    /**
     * Remove the specified resident
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $resident = Resident::findOrFail($id);
            // $resident->delete();

            DB::commit();

            if ($request->expectsJson()) {
                return $this->success('Resident deleted successfully');
            }

            return redirect()
                ->route('residents.index')
                ->with('success', 'Resident deleted successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            return $this->handleException($e, $request);
        }
    }
    // public function destroy(Request $request, $id)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $resident = Resident::with('user')->findOrFail($id);

    //         // Optional safety check
    //         if ($resident->status === 'active') {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Active resident cannot be deleted. Please deactivate first.'
    //             ], 422);
    //         }

    //         // Remove role safely
    //         if ($resident->user) {
    //             $resident->user->removeRole('resident');
    //         }

    //         // Soft delete ready (recommended)
    //         $resident->delete();

    //         // Optional: disable user instead of delete
    //         if ($resident->user) {
    //             $resident->user->update(['status' => 'inactive']);
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Resident deleted successfully'
    //         ]);
    //     } catch (Throwable $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Failed to delete resident',
    //             'errors' => ['exception' => $e->getMessage()]
    //         ], 500);
    //     }
    // }


    /**
     * Validation logic (Reusable)
     */

    // protected function validateResident(Request $request, $id = null)
    // {
    //     return $request->validate([
    //         'name'        => 'required|string|max:255',
    //         'email'       => 'required|email|unique:residents,email,' . $id,
    //         'number'      => 'required|string|max:15',
    //         'parent_no'   => 'nullable|string|max:15',
    //         'guardian_no' => 'nullable|string|max:15',
    //         'gender'      => 'required|in:male,female,other',
    //         'status'      => 'required|in:active,inactive',
    //         'check_in_date' => 'nullable|date',
    //     ]);
    // }

    protected function validateResident(Request $request, $id = null)
    {
        return $request->validate([
            'scholar_no'  => 'sometimes|required|string|max:50|unique:residents,scholar_no,' . $id,
            'name'        => 'sometimes|required|string|max:255',
            'email'       => 'sometimes|required|email|unique:users,email,' . ($id ? optional(Resident::find($id))->user_id : ''),
            'mobile'      => 'sometimes|required|string|max:15',
            'gender'      => 'sometimes|required|in:male,female,other',

            'fathers_name'   => 'sometimes|nullable|string|max:255',
            'mothers_name'   => 'sometimes|nullable|string|max:255',

            'parent_no'   => 'sometimes|nullable|string|max:15',
            'guardian_no' => 'sometimes|nullable|string|max:15',

            // 'check_in_date'  => 'sometimes|nullable|date',
            // 'check_out_date' => 'sometimes|nullable|date|after_or_equal:check_in_date',
            'check_in_date'  => 'sometimes|nullable|date_format:Y-m-d\TH:i',
            'check_out_date' => 'sometimes|nullable|date_format:Y-m-d\TH:i|after_or_equal:check_in_date',
            // 'check_in_date'  => 'sometimes|nullable|datetime',
            // 'check_out_date' => 'sometimes|nullable|datetime|after_or_equal:check_in_date',

            // 'check_in_date'  => 'sometimes|nullable|date_format:d M Y h:i A',
            // 'check_out_date' => 'sometimes|nullable|date_format:d M Y h:i A|after_or_equal:check_in_date',

            'bed_id'         => 'sometimes|nullable|integer|exists:beds,id',
            'status'         => 'sometimes|required|in:active,inactive',
        ]);
    }



    /**
     * Centralized exception handler
     */
    protected function handleException(Throwable $e, Request $request)
    {
        if ($e instanceof ValidationException) {
            return $request->expectsJson()
                ? $this->error('Validation error', $e->errors(), 422)
                : back()->withErrors($e->errors())->withInput();
        }

        if ($e instanceof ModelNotFoundException) {
            return $request->expectsJson()
                ? $this->error('Resident not found', [], 404)
                : abort(404);
        }

        // Database / Query errors
        if ($e instanceof \Illuminate\Database\QueryException) {
            return $request->expectsJson()
                ? $this->error('Database error occurred', [], 500)
                : back()->with('error', 'Database error occurred');
        }

        // Fallback
        return $request->expectsJson()
            ? $this->error('Something went wrong', [], 500)
            : back()->with('error', 'Something went wrong');
    }
}
