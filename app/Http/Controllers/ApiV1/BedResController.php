<?php

namespace App\Http\Controllers\ApiV1;

use Throwable;
use App\Models\Bed;
use App\Models\Room;
use App\Models\Building;
use App\Models\University;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BedResController extends Controller
{
    use ApiResponses;

    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except(['index', 'show']);
    }

    /* =====================================================
     * INDEX : List Records (WEB + AJAX + API)
     * ===================================================== */
    // public function index(Request $request)
    // {
    //     if (! $request->ajax()) {
    //         return view('backend.admin.beds');
    //     }

    //     try {
    //         /*
    //         |--------------------------------------------------------------------------
    //         | Base Query (University-safe via scopeAccessible)
    //         |--------------------------------------------------------------------------
    //         */
    //         $query = Bed::query()
    //             ->accessible()
    //             ->leftJoin('rooms', 'rooms.id', '=', 'beds.room_id')
    //             ->leftJoin('buildings', 'buildings.id', '=', 'rooms.building_id')
    //             ->leftJoin('universities', 'universities.id', '=', 'buildings.university_id')
    //             ->select([
    //                 'beds.id',
    //                 'beds.bed_number',
    //                 'beds.bed_type',
    //                 'beds.status',
    //                 'beds.created_at',

    //                 // Related display fields
    //                 'rooms.room_number',
    //                 'rooms.floor_no',
    //                 'buildings.name as building_name',
    //                 'universities.name as university_name',

    //                 // DB::raw("
    //                 //     CASE
    //                 //         WHEN rooms.status IN (1, '1', 'active', 'available', 'enabled', 'yes') THEN 1
    //                 //         ELSE 0
    //                 //     END as status_normalized
    //                 // ")
    //             ]);

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Summary (Clone-safe)
    //         |--------------------------------------------------------------------------
    //         */
    //         $rooms = Room::query()
    //             ->accessible()
    //             ->where('status', 'active')
    //             ->orWhere('status', 'available')
    //             ->orderBy('room_number', 'asc')
    //             ->get(['id', 'room_number', 'capacity']);

    //         $summary = [
    //             'total' => (clone $query)->count(),
    //             'active' => (clone $query)->whereRaw("
    //                 CASE
    //                     WHEN rooms.status IN (1, '1', 'active', 'enabled', 'yes') THEN 1
    //                     ELSE 0
    //                 END = 1
    //             ")->count(),
    //             'inactive' => (clone $query)->whereRaw("
    //                 CASE
    //                     WHEN rooms.status IN (1, '1', 'active', 'enabled', 'yes') THEN 1
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
    //                 $q->where('beds.bed_number', 'like', "%{$search}%")
    //                     ->orWhere('beds.bed_type', 'like', "%{$search}%")
    //                     ->orWhere('rooms.room_number', 'like', "%{$search}%")
    //                     ->orWhere('rooms.floor_no', 'like', "%{$search}%")
    //                     ->orWhere('buildings.name', 'like', "%{$search}%")
    //                     ->orWhere('universities.name', 'like', "%{$search}%");

    //                 // if (in_array(strtolower($search), ['active', 'inactive', '1', '0'])) {
    //                 //     $q->orWhereRaw("
    //                 //         CASE
    //                 //             WHEN rooms.status IN (1, '1', 'active', 'enabled', 'yes') THEN 1
    //                 //             ELSE 0
    //                 //         END = ?
    //                 //     ", [in_array(strtolower($search), ['active', '1']) ? 1 : 0]);
    //                 // }
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
    //             'bed_number'   => 'beds.bed_number',
    //             'type'      => 'beds.bed_type',
    //             'status'    => 'status',
    //             // 'status'    => 'status_normalized',
    //             'room_number'   => 'rooms.room_number',
    //             'floor_no'     => 'rooms.floor_no',
    //             'building'  => 'buildings.name',
    //             'university'  => 'universities.name',
    //             'created_at' => 'rooms.created_at',
    //         ];

    //         if (isset($orderable[$columnKey])) {
    //             $query->orderBy($orderable[$columnKey], $orderDir);
    //         } else {
    //             $query->orderBy('rooms.created_at', 'desc');
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
    //         $data = $records->map(fn($r) => [
    //             'id'        => $r->id,
    //             'bed_number'   => $r->bed_number,
    //             'room_number'   => $r->room_number,
    //             'floor_no'     => $r->floor_no,
    //             'type'      => ucfirst($r->bed_type),
    //             'status'    => $r->status,
    //             // 'status'    => (int) $r->status_normalized,
    //             'building'  => $r->building_name,
    //             'university'  => $r->university_name,
    //             'created_at' => optional($r->created_at)->format('d-m-Y'),
    //         ]);

    //         return response()->json([
    //             'draw'            => (int) $request->draw,
    //             'recordsTotal'    => $recordsTotal,
    //             'recordsFiltered' => $recordsFiltered,
    //             'data'            => $data,
    //             'meta'            => [
    //                 'summary' => $summary,
    //                 'rooms' => $rooms,
    //             ],
    //         ]);
    //     } catch (\Throwable $e) {
    //         Log::error('Room DataTable Error', ['exception' => $e->getMessage()]);

    //         return response()->json([
    //             'draw' => (int) $request->draw,
    //             'recordsTotal' => 0,
    //             'recordsFiltered' => 0,
    //             'data' => [],
    //         ], 500);
    //     }
    // }

    /* =====================================================
     * INDEX : Datatable + Meta
     * ===================================================== */
    // public function index(Request $request)
    // {
    //     if (! $request->ajax()) {
    //         return view('beds.index');
    //     }

    //     try {

    //         /* ---------------------------------------------
    //      | BASE QUERY (JOINED FOR ORDERING)
    //      --------------------------------------------- */
    //         $query = Bed::query()
    //             ->accessible()
    //             ->leftJoin('rooms', 'rooms.id', '=', 'beds.room_id')
    //             ->leftJoin('buildings', 'buildings.id', '=', 'rooms.building_id')
    //             ->leftJoin('universities', 'universities.id', '=', 'buildings.university_id')
    //             ->select([
    //                 'beds.*',
    //                 'rooms.room_number',
    //                 'rooms.floor_no',
    //                 'buildings.name as hostel_name',
    //                 'universities.name as university_name',
    //             ]);

    //         /* ---------------------------------------------
    //      | SUMMARY
    //      --------------------------------------------- */
    //         $summary = [
    //             'total'    => (clone $query)->count(),
    //             'active'   => (clone $query)->whereIn('beds.status', ['active', 'available'])->count(),
    //             'inactive' => (clone $query)->whereNotIn('beds.status', ['active', 'available'])->count(),
    //         ];

    //         /* ---------------------------------------------
    //      | SEARCH
    //      --------------------------------------------- */
    //         if ($search = trim($request->input('search.value'))) {
    //             $query->where(function ($q) use ($search) {
    //                 $q->where('beds.bed_number', 'like', "%{$search}%")
    //                     ->orWhere('rooms.room_number', 'like', "%{$search}%")
    //                     ->orWhere('buildings.name', 'like', "%{$search}%")
    //                     ->orWhere('universities.name', 'like', "%{$search}%");
    //             });
    //         }

    //         $recordsTotal    = Bed::accessible()->count();
    //         $recordsFiltered = (clone $query)->count();

    //         /* ---------------------------------------------
    //      | ORDERING (DATATABLE SAFE)
    //      --------------------------------------------- */
    //         $orderIndex = $request->input('order.0.column');
    //         $orderDir   = $request->input('order.0.dir', 'desc');
    //         $columnKey  = $request->input("columns.$orderIndex.data");

    //         $orderMap = [
    //             'bed_number' => 'beds.bed_number',
    //             'type'       => 'beds.bed_type',
    //             'status'     => 'beds.status',
    //             'room_number' => 'rooms.room_number',
    //             'floor_no'   => 'rooms.floor_no',
    //             'hostel'     => 'buildings.name',
    //             'university' => 'universities.name',
    //             'created_at' => 'beds.created_at',
    //         ];

    //         if (isset($orderMap[$columnKey])) {
    //             $query->orderBy($orderMap[$columnKey], $orderDir);
    //         } else {
    //             $query->orderBy('beds.created_at', 'desc');
    //         }

    //         /* ---------------------------------------------
    //      | PAGINATION
    //      --------------------------------------------- */
    //         $beds = $query
    //             ->skip($request->start)
    //             ->take($request->length)
    //             ->get();

    //         /* ---------------------------------------------
    //      | META (HOSTELS + ROOMS)
    //      --------------------------------------------- */
    //         $buildings = Building::query()
    //             ->accessible()
    //             ->with(['rooms' => function ($q) {
    //                 $q->whereIn('status', ['active', 'available'])
    //                     ->select('id', 'room_number', 'floor_no', 'building_id');
    //             }])
    //             ->select('id', 'name', 'floors')
    //             ->get();

    //         /* ---------------------------------------------
    //      | RESPONSE
    //      --------------------------------------------- */
    //         return response()->json([
    //             'draw'            => (int) $request->draw,
    //             'recordsTotal'    => $recordsTotal,
    //             'recordsFiltered' => $recordsFiltered,
    //             'data' => $beds->map(fn($b) => [
    //                 'id'         => $b->id,
    //                 'bed_number' => $b->bed_number,
    //                 'type'       => $b->bed_type,
    //                 'room_number' => $b->room_number ?? '-',
    //                 'floor_no'   => $b->floor_no ?? '-',
    //                 'hostel'     => $b->hostel_name ?? '-',
    //                 'university' => $b->university_name ?? '-',
    //                 'status'     => $b->status,
    //             ]),
    //             'meta' => [
    //                 'summary' => $summary,
    //                 'hostels' => $buildings,
    //             ]
    //         ]);
    //     } catch (\Throwable $e) {

    //         Log::error('Bed DataTable Error', [
    //             'exception' => $e->getMessage()
    //         ]);

    //         return response()->json([
    //             'draw' => (int) $request->draw,
    //             'recordsTotal' => 0,
    //             'recordsFiltered' => 0,
    //             'data' => [],
    //             'meta' => []
    //         ], 500);
    //     }
    // }


    public function index(Request $request)
    {
        if (! $request->ajax()) {
            return view('beds.index');
        }

        try {

            /* ---------------------------------------------
             | Base Query (MODEL ONLY)
             --------------------------------------------- */
            $query = Bed::query()
                ->accessible()
                ->with([
                    'room:id,room_number,floor_no,building_id',
                    'room.building:id,name,university_id',
                    'room.building.university:id,name'
                ]);

            /* ---------------------------------------------
             | Summary
             --------------------------------------------- */
            $summary = [
                'total'    => (clone $query)->count(),
                'active'   => (clone $query)->whereIn('status', ['active', 'available'])->count(),
                'inactive' => (clone $query)->whereNotIn('status', ['active', 'available'])->count(),
            ];

            /* ---------------------------------------------
             | Search
             --------------------------------------------- */
            if ($search = trim($request->input('search.value'))) {
                $query->where(function ($q) use ($search) {
                    $q->where('bed_number', 'like', "%{$search}%")
                        ->orWhereHas(
                            'room',
                            fn($r) =>
                            $r->where('room_number', 'like', "%{$search}%")
                        )
                        ->orWhereHas(
                            'room.building',
                            fn($b) =>
                            $b->where('name', 'like', "%{$search}%")
                        );
                });
            }


            $recordsTotal    = Bed::accessible()->count();
            $recordsFiltered = (clone $query)->count();

            //ORDERING (SUBQUERY BASED)

            $orderIndex = $request->input('order.0.column');
            $orderDir   = $request->input('order.0.dir', 'desc');
            $columnKey  = $request->input("columns.$orderIndex.data");

            match ($columnKey) {
                'bed_number' =>
                $query->orderBy('bed_number', $orderDir),

                'type' =>
                $query->orderBy('bed_type', $orderDir),

                'status' =>
                $query->orderBy('status', $orderDir),

                'room_number' =>
                $query->orderBy(
                    Room::select('room_number')
                        ->whereColumn('rooms.id', 'beds.room_id'),
                    $orderDir
                ),

                'floor_no' =>
                $query->orderBy(
                    Room::select('floor_no')
                        ->whereColumn('rooms.id', 'beds.room_id'),
                    $orderDir
                ),

                'hostel' =>
                $query->orderBy(
                    Building::select('name')
                        ->whereColumn('buildings.id', 'rooms.building_id')
                        ->join('rooms', 'rooms.building_id', '=', 'buildings.id')
                        ->whereColumn('rooms.id', 'beds.room_id'),
                    $orderDir
                ),

                'university' =>
                $query->orderBy(
                    University::select('universities.name')
                        ->join('buildings', 'buildings.university_id', '=', 'universities.id')
                        ->join('rooms', 'rooms.building_id', '=', 'buildings.id')
                        ->whereColumn('rooms.id', 'beds.room_id'),
                    $orderDir
                ),

                default =>
                $query->orderBy('created_at', 'desc')
            };
            /* ---------------------------------------------
             | Pagination
             --------------------------------------------- */
            $beds = $query
                ->latest('id')
                ->skip($request->start)
                ->take($request->length)
                ->get();

            /* ---------------------------------------------
             | Meta: Accessible Hostels + Rooms
             --------------------------------------------- */
            $buildings = Building::query()
                ->accessible()
                ->with(['rooms' => function ($q) {
                    $q->whereIn('status', ['active', 'available'])
                        ->select('id', 'room_number', 'floor_no', 'building_id');
                }])
                ->select('id', 'name', 'floors')
                ->get();

            /* ---------------------------------------------
             | Response
             --------------------------------------------- */
            return response()->json([
                'draw'            => (int) $request->draw,
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $beds->map(fn($b) => [
                    'id'         => $b->id,
                    'bed_number' => $b->bed_number,
                    'type'       => $b->bed_type,
                    'room_number'       => $b->room->room_number ?? '-',
                    'floor_no'      => $b->room->floor_no ?? '-',
                    'hostel'   => $b->room->building->name ?? '-',
                    'university' => $b->room->building->university->name ?? '-',
                    // 'status'     => in_array($b->status, ['active','available']) ? 1 : 0,
                    'status'     => $b->status,
                ]),
                'meta' => [
                    'summary'   => $summary,
                    'hostels' => $buildings,
                ]
            ]);
        } catch (\Throwable $e) {

            Log::error('Bed DataTable Error', ['exception' => $e->getMessage()]);

            return response()->json([
                'draw' => (int) $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'meta' => []
            ], 500);
        }
    }

    /* =====================================================
     * STORE : Create Record
     * ===================================================== */
    // public function store(Request $request)
    // {
    //     Log::info('Determined Type', ['type' => $request->all()]);
    //     $validated = $this->validateData($request);

    //     DB::beginTransaction();

    //     try {

    //         $building = Building::accessible()
    //             ->where('id', $validated['building_id'])
    //             ->firstOrFail();

    //         if ($validated['floor_no'] > $building->floors) {
    //             abort(422, "Floor exceeds building floor limit ({$building->floors})");
    //         }

    //         // $type = $validated['gender'] ?? $validated['type'] ?? null;
    //         Log::info('Determined Type', ['type' => $building]);

    //         // Base payload
    //         $data = [
    //             'building_id' => $building->id,
    //             'floor_no'    => $validated['floor_no'],
    //             'room_number'     => strtoupper($validated['room_number']),
    //             'room_type'   => $validated['room_type'],
    //             'capacity'    => $validated['capacity'],
    //             'status'      => $validated['status'] ?? true,
    //             'created_by'  => auth()->id(),
    //         ];

    //         Log::info('Creating Room with Data', $data);

    //         // OPTIONAL: add code only if provided
    //         // if (!empty($validated['code'])) {
    //         //     $data['building_code'] = strtoupper($validated['code']);
    //         // }

    //         $record = Room::create($data);

    //         DB::commit();

    //         return $request->expectsJson()
    //             ? $this->success('Record created successfully', $record, 201)
    //             : redirect()->back()->with('swal_success', 'Record created successfully');
    //     } catch (QueryException $e) {
    //         DB::rollBack();
    //         Log::error('Record Store DB Error', ['exception' => $e]);
    //         return $this->handleWebOrApiError(
    //             $request,
    //             'Database error while creating record'
    //         );
    //     } catch (Throwable $e) {
    //         DB::rollBack();
    //         Log::critical('Record Store Error', ['exception' => $e]);
    //         return $this->handleWebOrApiError(
    //             $request,
    //             'Record creation failed'
    //         );
    //     }
    // }
    // public function store(Request $request)
    // {
    //     $user = auth()->user();

    //     Log::info('Create Bed Called', [
    //         'user_id' => $user?->id,
    //         'data'    => $request->all()
    //     ]);
    //     /* ---------------------------------
    //  | Validation
    //  |---------------------------------*/
    //     $validated = $request->validate([
    //         'room_id'     => ['required', 'exists:rooms,id'],
    //         'bed_number'  => ['required', 'string', 'max:50'],
    //         'bed_type'    => ['nullable', 'string', 'max:50'],
    //         // 'status'      => ['required', 'in:active,available,inactive,maintenance,blocked, reserved,discommissioned'],
    //         'status'   => ['required', Rule::in(Bed::STATUSES)],
    //     ]);

    //     /* ---------------------------------
    //  | Load Room with Relations
    //  |---------------------------------*/
    //     $room = Room::with('building')
    //         ->accessible() // your scope
    //         ->findOrFail($validated['room_id']);

    //     /* ---------------------------------
    //  | Capacity Check (CRITICAL)
    //  |---------------------------------*/
    //     $existingBeds = $room->beds()->count();

    //     if ($room->capacity && $existingBeds >= $room->capacity) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'Room capacity exceeded. Cannot add more beds.'
    //         ], 422);
    //     }

    //     /* ---------------------------------
    //  | Duplicate Bed Protection
    //  |---------------------------------*/
    //     $duplicate = Bed::where('room_id', $room->id)
    //         ->where('bed_number', $validated['bed_number'])
    //         ->exists();

    //     if ($duplicate) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'Bed number already exists in this room.'
    //         ], 422);
    //     }

    //     /* ---------------------------------
    //  | Status Normalization
    //  |---------------------------------*/
    //     $normalizedStatus = match ($validated['status']) {
    //         'active'       => 'active',
    //         'blocked'      => 'blocked',
    //         'maintenance'  => 'maintenance',
    //         default        => 'inactive',
    //     };

    //     /* ---------------------------------
    //  | Create Bed (Model-only)
    //  |---------------------------------*/
    //     $bed = Bed::create([
    //         'room_id'    => $room->id,
    //         'bed_number' => strtoupper(trim($validated['bed_number'])),
    //         'bed_type'   => $validated['bed_type'],
    //         'status'     => $normalizedStatus,
    //     ]);

    //     /* ---------------------------------
    //  | Success Response
    //  |---------------------------------*/
    //     return response()->json([
    //         'status'  => true,
    //         'message' => 'Bed created successfully',
    //         'data'    => [
    //             'id' => $bed->id
    //         ]
    //     ], 201);
    // }

    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'room_id'    => ['required', 'exists:rooms,id'],
                'bed_number' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('beds')->where(
                        fn($q) =>
                        $q->where('room_id', $request->room_id)
                    ),
                ],
                'bed_type' => 'nullable|string|max:50',
                'status'   => ['required', Rule::in(Bed::STATUSES)],
            ]);

            $room = Room::accessible()->find($validated['room_id']);

            if (!$room) {
                return $this->error(
                    'Unauthorized room access',
                    [],
                    403
                );
            }

            /* --------------------------------------------
         | BUSINESS RULES (422 → FIELD ERROR)
         -------------------------------------------- */
            if ($validated['status'] === Bed::STATUS_OCCUPIED) {
                return $this->error(
                    'Validation failed',
                    [
                        'status' => ['Bed cannot be created as occupied.']
                    ],
                    422
                );
            }

            $bed = Bed::create([
                'room_id'    => $room->id,
                'bed_number' => strtoupper(trim($validated['bed_number'])),
                'bed_type'   => $validated['bed_type'],
                'status'     => $validated['status'],
            ]);

            return $this->success(
                'Bed created successfully',
                ['id' => $bed->id],
                201
            );
        } catch (ValidationException $e) {

            return $this->error(
                'Validation failed',
                $e->errors(),
                422
            );
        } catch (\Throwable $e) {

            Log::error('Bed creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->error(
                'Something went wrong while creating bed',
                [],
                500
            );
        }
    }



    /* =====================================================
     * SHOW : Record Details
     * ===================================================== */
    // public function show(Request $request, $id)
    // {
    //     try {
    //         // $hostel = Hostel::findOrFail($id);
    //         $record = Room::query()
    //             ->accessible() // 🔐 university + building level access
    //             ->with([
    //                 'building:id,name,gender,floors,university_id',
    //                 'building.university:id,name'
    //             ])
    //             ->where('rooms.id', $id)
    //             ->firstOrFail();

    //         // Controlled response structure
    //         // Optional: normalize status for frontend
    //         $response = [
    //             'id'          => $record->id,
    //             'room_number' => $record->room_number,
    //             'floor_no'    => $record->floor_no,
    //             'room_type'   => $record->room_type,
    //             'capacity'    => $record->capacity,
    //             // 'status'    => $record->status,
    //             'status' => $this->normalizeStatusForUI($record->status),
    //             // 'status'      => in_array(
    //             //     strtolower((string) $record->status),
    //             //     ['1', 'active', 'available', 'enabled', 'yes']
    //             // ) ? 1 : 0,
    //             'created_at'  => optional($record->created_at)->format('d-m-Y'),

    //             // Relations
    //             'building' => [
    //                 'id'     => $record->building->id,
    //                 'name'   => $record->building->name,
    //                 'gender' => $record->building->gender,
    //                 'floors' => $record->building->floors,
    //             ],

    //             // 'university' => [
    //             //     'id'   => $record->building->university->id,
    //             //     'name' => $record->building->university->name,
    //             // ],
    //         ];

    //         return $request->expectsJson()
    //             ? $this->success('Record fetched successfully', $response)
    //             : view('hostels.show', compact('record'));
    //     } catch (ModelNotFoundException $e) {

    //         return $this->handleWebOrApiError(
    //             $request,
    //             'Record not found',
    //             404
    //         );
    //     } catch (Throwable $e) {
    //         Log::error('Record Show Error', ['exception' => $e]);
    //         return $this->handleWebOrApiError(
    //             $request,
    //             'Unable to fetch record details'
    //         );
    //     }
    // }

    public function show(Bed $bed)
    {
        $record = $bed->load([
            'room:id,room_number,floor_no,building_id',
            'room.building:id,name,floors'
        ]);

        $room     = $record->room;
        $building = $room?->building;

        return response()->json([
            'status'  => true,
            'message' => 'Record fetched successfully',

            'data' => [
                /* -----------------------------
             | Core Bed Info
             |-----------------------------*/
                'id'         => $record->id,
                'bed_number' => $record->bed_number,
                'bed_type' => $bed->bed_type,
                'floor_no' => $bed->room->floor_no,
                'room_number' => $bed->room->room_number,
                'hostel' => $bed->room->building->name,
                'hostel_id' => $bed->room->building->id,
                'room_id' => $bed->room->id,
                //             'floor_no'    => $record->floor_no,
                //             'room_type'   => $record->room_type,
                //             'capacity'    => $record->capacity,

                /* -----------------------------
             | Normalized Status (UI-safe)
             |-----------------------------*/
                'status' => match (strtolower((string) $bed->status)) {
                    'active', 'available'   => 'available',
                    'reserved', 'blocked'   => 'blocked',
                    'maintenance'           => 'maintenance',
                    'discommissioned'       => 'discommissioned',
                    default                 => 'inactive',
                },

                /* -----------------------------
             | Hierarchy (IDs + labels)
             |-----------------------------*/
                // 'hierarchy' => [
                //     'hostel' => $building ? [
                //         'id'     => $building->id,
                //         'name'   => $building->name,
                //         'floors' => $building->floors,
                //     ] : null,

                //     'room' => $room ? [
                //         'id'          => $room->id,
                //         'room_number' => $room->room_number,
                //         'floor_no'    => $room->floor_no,
                //     ] : null,
                // ],
            ]
        ]);
    }

    /* =====================================================
     * UPDATE : Record Update
     * ===================================================== */
    // public function update(Request $request, $id)
    // {
    //     Log::info('Update Hostel Called', $request->all());
    //     $validated = $this->validateData($request, $id);

    //     DB::beginTransaction();

    //     try {
    //         // $record = Building::findOrFail($id);
    //         // 🔐 Fetch room with access control
    //         $record = Room::query()->accessible()->where('id', $id)->firstOrFail();


    //         // // Normalize: prefer gender, fallback to type 
    //         // $type = $validated['gender'] ?? $validated['type'] ?? null;
    //         // Log::info('Determined Type', ['type' => $type]);
    //         /*
    //     |--------------------------------------------------
    //     | Business Rule Guards (Future-proof)
    //     |--------------------------------------------------
    //     */

    //         // Example: Prevent activating room under maintenance
    //         if (
    //             $record->status === Room::STATUS_MAINTENANCE &&
    //             $validated['status'] === Room::STATUS_ACTIVE
    //         ) {
    //             throw new \DomainException(
    //                 'Room under maintenance cannot be activated directly'
    //             );
    //         }

    //         // Example: Reserved room cannot be inactivated silently
    //         if (
    //             $record->status === Room::STATUS_RESERVED &&
    //             $validated['status'] === Room::STATUS_INACTIVE
    //         ) {
    //             throw new \DomainException(
    //                 'Reserved room must be released before deactivation'
    //             );
    //         }

    //         $updateData = [
    //             'room_number' => $validated['room_number'],
    //             'floor_no'    => $validated['floor_no'],
    //             'room_type'   => $validated['room_type'],
    //             'capacity'    => $validated['capacity'],
    //             'status'      => $validated['status'],
    //             'updated_by'  => auth()->id(),
    //         ];

    //         $record->update($updateData);

    //         DB::commit();

    //         return $request->expectsJson()
    //             ? $this->success('Record updated successfully', $record)
    //             : redirect()->back()->with('swal_success', 'Record updated successfully');
    //     } catch (ModelNotFoundException $e) {
    //         DB::rollBack();

    //         return $this->handleWebOrApiError(
    //             $request,
    //             'Record not found',
    //             404
    //         );
    //     } catch (QueryException $e) {
    //         DB::rollBack();
    //         Log::error('Record Update DB Error', ['exception' => $e]);

    //         return $this->handleWebOrApiError(
    //             $request,
    //             'Database error while updating record'
    //         );
    //     } catch (Throwable $e) {
    //         DB::rollBack();
    //         Log::critical('Record Update Error', ['exception' => $e]);
    //         return $this->handleWebOrApiError(
    //             $request,
    //             'Record update failed'
    //         );
    //     }
    // }

    public function update(Request $request, Bed $bed)
    {
        // Validation + Authorization

        try {
            $validated = $request->validate([
                'room_id'    => ['required', 'exists:rooms,id'],
                'bed_number' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('beds')
                        ->where(fn($q) => $q->where('room_id', $request->room_id))
                        ->ignore($bed->id),
                ],
                'bed_type' => 'nullable|string|max:50',
                'status'   => ['required', Rule::in(Bed::STATUSES)],
            ]);

            $room = Room::accessible()->find($validated['room_id']);

            if (!$room) {
                return $this->error('Unauthorized room access', [], 403);
            }



            DB::beginTransaction();

            // Fetch current state

            $currentStatus = $bed->status;
            $newStatus     = $validated['status'];

            // Status Transition Protection


            // ❌ Occupied beds cannot be manually changed
            if ($currentStatus === Bed::STATUS_OCCUPIED && $newStatus !== $currentStatus) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Occupied beds cannot be modified manually.',
                ], 422);
            }

            // ❌ Maintenance → Occupied is not allowed
            if (
                $currentStatus === Bed::STATUS_MAINTENANCE &&
                $newStatus === Bed::STATUS_OCCUPIED
            ) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Bed must be made available before assigning.',
                ], 422);
            }

            // Update Safely

            $updateData = [
                'room_id'    => $validated['room_id'],
                'bed_number' => strtoupper(trim($validated['bed_number'])),
                'bed_type'   => $validated['bed_type'],
                'status'     => $newStatus,
            ];
            $bed->update($updateData);

            DB::commit();

            // Response

            // return response()->json([
            //     'status'  => true,
            //     'message' => 'Bed updated successfully',
            //     'data'    => [
            //         'id'         => $bed->id,
            //         'bed_number' => $bed->bed_number,
            //         'status'     => $bed->status,
            //     ]
            // ]);
            return $this->success(
                'Bed updated successfully'
            );
        } catch (ValidationException $e) {

            return $this->error(
                'Validation failed',
                $e->errors(),
                422
            );
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Room not found or inaccessible.',
            ], 404);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::critical('Room Fetch Error', ['exception' => $e]);

            return response()->json([
                'status'  => false,
                'message' => 'Error fetching room details.',
            ], 500);
        }
    }

    /* =====================================================
     * DELETE : Record Delete
     * ===================================================== */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $record = Room::findOrFail($id);
            $record->delete();

            DB::commit();

            return $request->expectsJson()
                ? $this->success('Record deleted successfully')
                : redirect()->back()->with('swal_success', 'Record deleted successfully');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return $this->handleWebOrApiError(
                $request,
                'Record not found',
                404
            );
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Record Delete DB Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Database error while deleting record'
            );
        } catch (Throwable $e) {
            DB::rollBack();
            Log::critical('Record Delete Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Record deletion failed'
            );
        }
    }

    /* =====================================================
     * VALIDATION
     * ===================================================== */
    private function validateData(Request $request, $id = null): array
    {
        return $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'room_number' => 'required|string|max:255',
            'floor_no' => [
                'required',
                'integer',
                'min:1',
                function ($attr, $value, $fail) use ($request) {
                    $maxFloors = Building::where('id', $request->building_id)->value('floors');
                    if ($value > $maxFloors) {
                        $fail("Floor {$value} exceeds building floor limit ({$maxFloors}).");
                    }
                }
            ],
            'room_type' => 'required|string|max:255',
            // 'code' => [
            //     'required',
            //     'string',
            //     'max:50',
            //     // Rule::unique('faculties', 'code')->ignore($id)
            //     Rule::unique('faculties')
            //         ->where(
            //             fn($q) =>
            //             $q->where('university_id', auth()->user()->university_id)
            //         )
            //         ->ignore($id)
            // ],

            // Accept either gender or type 
            // 'gender' => 'sometimes|required|in:male,female,coed,boys,girls,mixed',
            'type' => 'sometimes|required|in:single,double,tripple',
            'capacity' => 'required|integer|min:1',
            // 'status' => 'nullable|boolean'
            'status' => 'sometimes|required|in:active,inactive,maintenance,reserved,available',
        ]);
    }

    protected function normalizeStatusForUI($status): string
    {
        return match (strtolower((string) $status)) {
            'active', 'available' => 'active',
            'reserved'            => 'reserved',
            'maintenance'         => 'maintenance',
            'inactive', 'disabled' => 'inactive',
            default               => 'inactive',
        };
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
}
