<?php

namespace App\Http\Controllers\ApiV1;

use Throwable;
use App\Models\Building;
use App\Models\University;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HostelController extends Controller
{
    use ApiResponses;

    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except(['index', 'show']);
    }

    /* =====================================================
     * INDEX : List Faculties (WEB + AJAX + API)
     * ===================================================== */

    // public function index(Request $request)
    // {
    //     // Page load
    //     if (!$request->ajax()) {
    //         return view('faculties.index');
    //     }

    //     try {

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Accessible Universities (ONCE)
    //     |--------------------------------------------------------------------------
    //     */
    //         if (
    //             auth()->check() &&
    //             auth()->user()->university_id &&
    //             !auth()->user()->is_super_admin
    //         ) {
    //             // University Admin → Only their university
    //             $universities = \App\Models\University::query()
    //                 ->select('id', 'name')
    //                 ->where('id', auth()->user()->university_id)
    //                 ->where('status', 1)
    //                 ->get();
    //         } else {
    //             // Super Admin → All universities
    //             $universities = \App\Models\University::query()
    //                 ->select('id', 'name')
    //                 ->where('status', 1)
    //                 ->orderBy('name')
    //                 ->get();
    //         }

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Record Query
    //     |--------------------------------------------------------------------------
    //     */
    //         $query = Building::query()
    //             ->with('university:id,name')
    //             ->select('id', 'name', 'status', 'gender', 'building_code', 'floors', 'university_id', 'created_at');

    //         if (
    //             auth()->check() &&
    //             auth()->user()->university_id &&
    //             !auth()->user()->is_super_admin
    //         ) {
    //             $query->where('university_id', auth()->user()->university_id);
    //         }

    //         $recordSummary = [
    //             'total'    => (clone $query)->count(),
    //             'active'   => (clone $query)->where('status', 1)->count(),
    //             'inactive' => (clone $query)->where('status', 0)->count(),
    //         ];

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Counts
    //     |--------------------------------------------------------------------------
    //     */
    //         $recordsTotal = (clone $query)->count();

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Search
    //     |--------------------------------------------------------------------------
    //     */
    //         if ($search = $request->input('search.value')) {
    //             $query->where(function ($q) use ($search) {
    //                 $q->where('name', 'like', "%{$search}%")
    //                     ->orWhereHas(
    //                         'university',
    //                         fn($uq) =>
    //                         $uq->where('name', 'like', "%{$search}%")
    //                     );
    //             });
    //         }

    //         $recordsFiltered = (clone $query)->count();

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Ordering
    //     |--------------------------------------------------------------------------
    //     */
    //         $columns = [
    //             2 => 'name',
    //             3 => 'building_code',
    //             4 => 'floors',
    //             5 => 'gender',
    //             6 => 'status',
    //             7 => 'university',
    //             // 7 => 'created_at',
    //         ];

    //         $orderColumn = $columns[$request->input('order.0.column')] ?? 'id';
    //         $orderDir    = $request->input('order.0.dir', 'desc');

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Pagination
    //     |--------------------------------------------------------------------------
    //     */
    //         $faculties = $query
    //             ->orderBy($orderColumn, $orderDir)
    //             ->skip($request->start)
    //             ->take($request->length)
    //             ->get()
    //             ->map(fn($record) => [
    //                 'id'         => $record->id,
    //                 'name'       => $record->name,
    //                 'university' => optional($record->university)->name,
    //                 // 'status'     => (int) $record->status,
    //                 // 'status' => is_numeric($record->status) ? ((int)$record->status === 1 ? 'Active' : 'Inactive') : ucfirst($record->status), // handles "active"/"inactive"
    //                 'status' => is_numeric($record->status)
    //                     ? ((int)$record->status === 1 ? 1 : 0)
    //                     : (strtolower($record->status) === 'active' ? 1 : 0),
    //                 'type' => $record->gender,
    //                 'floors' => $record->floors,
    //                 'code' => $record->building_code,
    //                 'created_at' => $record->created_at->format('d-m-Y'),
    //             ]);

    //         /*
    //     |--------------------------------------------------------------------------
    //     | FINAL RESPONSE
    //     |--------------------------------------------------------------------------
    //     */
    //         return response()->json([
    //             // DataTables required
    //             'draw'            => (int) $request->draw,
    //             'recordsTotal'    => $recordsTotal,
    //             'recordsFiltered' => $recordsFiltered,
    //             'data'            => $faculties,

    //             // Extra payload (SAFE)
    //             'meta' => [
    //                 'universities' => $universities,
    //                 'summary'      => $recordSummary,
    //             ],
    //         ]);
    //     } catch (\Throwable $e) {

    //         Log::error('Record DataTable Error', ['exception' => $e]);

    //         return response()->json([
    //             'draw'            => (int) $request->draw,
    //             'recordsTotal'    => 0,
    //             'recordsFiltered' => 0,
    //             'data'            => [],
    //             'meta'            => ['universities' => []],
    //         ], 500);
    //     }
    // }

    public function index(Request $request)
    {
        if (! $request->ajax()) {
            return view('faculties.index');
        }

        try {

            $user         = auth()->user();
            $isRestricted = $user && $user->university_id && ! $user->is_super_admin;
            $universityId = $isRestricted ? $user->university_id : null;

            /*
        |--------------------------------------------------------------------------
        | Meta: Accessible Universities
        |--------------------------------------------------------------------------
        */
            $universities = University::query()
                ->select('id', 'name')
                ->where('status', 1)
                ->when($isRestricted, fn($q) => $q->where('id', $universityId))
                ->orderBy('name')
                ->get();


            /*
        |--------------------------------------------------------------------------
        | Base Query (JOIN for ordering)
        |--------------------------------------------------------------------------
        */
            $query = Building::query()
                ->leftJoin('universities', 'universities.id', '=', 'buildings.university_id')
                ->select([
                    'buildings.id',
                    'buildings.name',
                    // 'buildings.status',
                    'buildings.gender',
                    'buildings.building_code',
                    'buildings.floors',
                    'buildings.created_at',
                    'universities.name as university_name',
                    // ✅ NORMALIZED STATUS (SQL-level)
                    DB::raw("
                        CASE
                            WHEN buildings.status IN (1, '1', 'active', 'enabled', 'yes') THEN 1
                            ELSE 0
                        END as status_normalized
                    ")

                ])
                ->when(
                    $isRestricted,
                    fn($q) => $q->where('buildings.university_id', $universityId)
                );

            /*
        |--------------------------------------------------------------------------
        | Meta Summary (Clone-safe)
        |--------------------------------------------------------------------------
        */
            $recordSummary = [
                'total'    => (clone $query)->count(),
                'active'   => (clone $query)->whereRaw("
                CASE
                    WHEN buildings.status IN (1, '1', 'active', 'enabled', 'yes') THEN 1
                    ELSE 0
                END = 1
            ")->count(),
                'inactive' => (clone $query)->whereRaw("
                CASE
                    WHEN buildings.status IN (1, '1', 'active', 'enabled', 'yes') THEN 1
                    ELSE 0
                END = 0
            ")->count(),
            ];


            /*
        |--------------------------------------------------------------------------
        | RecordsTotal (Before Search)
        |--------------------------------------------------------------------------
        */
            $recordsTotal = (clone $query)->count();

            /*
        |--------------------------------------------------------------------------
        | Global Search
        |--------------------------------------------------------------------------
        */
            if ($search = trim($request->input('search.value'))) {
                $query->where(function ($q) use ($search) {
                    $q->where('buildings.name', 'like', "%{$search}%")
                        ->orWhere('buildings.building_code', 'like', "%{$search}%")
                        ->orWhere('buildings.gender', 'like', "%{$search}%")
                        ->orWhere('universities.name', 'like', "%{$search}%");

                    // 🔥 STATUS SEARCH
                    if (in_array(strtolower($search), ['active', 'inactive', '1', '0'])) {
                        $q->orWhereRaw("
                CASE
                    WHEN buildings.status IN (1, '1', 'active', 'enabled', 'yes') THEN 1
                    ELSE 0
                END = ?
            ", [in_array(strtolower($search), ['active', '1']) ? 1 : 0]);
                    }
                });
            }

            $recordsFiltered = (clone $query)->count();

            /*
        |--------------------------------------------------------------------------
        | ORDERING (🔥 THIS IS THE FIX)
        |--------------------------------------------------------------------------
        */
            $orderColumnIndex = $request->input('order.0.column');
            $orderDir         = $request->input('order.0.dir', 'desc');

            $columnKey = $request->input("columns.$orderColumnIndex.data");

            // 🔐 SAFE SQL COLUMN MAP
            $orderableColumns = [
                'name'       => 'buildings.name',
                'code'       => 'buildings.building_code',
                'floors'     => 'buildings.floors',
                'type'       => 'buildings.gender',
                // 'status'     => 'buildings.status',
                'status'     => 'status_normalized', // ✅ IMPORTANT
                'university' => 'universities.name',
                'created_at' => 'buildings.created_at',
            ];

            if (isset($orderableColumns[$columnKey])) {
                $query->orderBy($orderableColumns[$columnKey], $orderDir);
            } else {
                $query->orderBy('buildings.created_at', 'desc');
            }

            /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */
            $records = $query
                ->skip((int) $request->start)
                ->take((int) $request->length)
                ->get();

            /*
        |--------------------------------------------------------------------------
        | Response Mapping
        |--------------------------------------------------------------------------
        */
            $data = $records->map(fn($r) => [
                'id'         => $r->id,
                'name'       => $r->name,
                'code'       => $r->building_code,
                'floors'     => $r->floors,
                'type'       => $r->gender,
                // 'status'     => (int) $r->status,
                // ✅ ALWAYS 1 or 0
                'status'     => (int) $r->status_normalized,
                'university' => $r->university_name,
                'created_at' => optional($r->created_at)->format('d-m-Y'),
            ]);

            return response()->json([
                'draw'            => (int) $request->draw,
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data'            => $data,
                // Extra payload (SAFE)
                'meta' => [
                    'universities' => $universities,
                    'summary'      => $recordSummary,
                ],
            ]);
        } catch (\Throwable $e) {

            \Log::error('Record DataTable Error', [
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'draw'            => (int) $request->draw,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
                'meta'            => ['universities' => []],
            ], 500);
        }
    }


    /* =====================================================
     * STORE : Create Record
     * ===================================================== */
    public function store(Request $request)
    {
        Log::info('Determined Type', $request->all());

        $validated = $this->validateData($request);

        DB::beginTransaction();

        try {
            $type = $validated['gender'] ?? $validated['type'] ?? null;
            // Log::info('Determined Type', ['type' => $type]);

            // Base payload
            $data = [
                'university_id' => $validated['university_id'] ?? auth()->user()->university_id,
                'name'          => $validated['name'],
                'gender'        => $type,
                'status'        => $validated['status'] ?? true,
                'floors'        => $validated['floors'],
                'created_by'    => auth()->id(),
            ];

            // OPTIONAL: add code only if provided
            if (!empty($validated['code'])) {
                $data['building_code'] = strtoupper($validated['code']);
            }

            $record = Building::create($data);

            // $record = Building::create([
            //     'university_id' => $validated['status'] ?? uth()->user()->university_id,
            //     'name'       => $validated['name'],
            //     // 'code'       => $validated['code'],
            //     'gender'     => $type,
            //     'status'     => $validated['status'] ?? true,
            //     'floors' => $validated['floors'],
            //     'created_by' => auth()->id()
            // ]);

            DB::commit();

            return $request->expectsJson()
                ? $this->success('Record created successfully', $record, 201)
                : redirect()->back()->with('swal_success', 'Record created successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Record Store DB Error', ['exception' => $e]);
            return $this->handleWebOrApiError(
                $request,
                'Database error while creating record'
            );
        } catch (Throwable $e) {
            DB::rollBack();
            Log::critical('Record Store Error', ['exception' => $e]);
            return $this->handleWebOrApiError(
                $request,
                'Record creation failed'
            );
        }
    }

    /* =====================================================
     * SHOW : Record Details
     * ===================================================== */
    public function show(Request $request, $id)
    {
        try {
            // $hostel = Hostel::findOrFail($id);
            $record = Building::with([
                'university:id,name'
            ])->select(['id', 'university_id', 'name', 'building_code', 'gender', 'floors', 'status', 'created_at'])->findOrFail($id);

            // Controlled response structure
            $response = [
                'id'            => $record->id,
                'name'          => $record->name,
                'code'       => $record->building_code,
                // 'status'        => $record->status,
                'status' => is_numeric($record->status)
                    ? ((int)$record->status === 1 ? 1 : 0)
                    : (strtolower($record->status) === 'active' ? 1 : 0),


                'type' => $record->gender ?? null,
                'floors' => $record->floors ?? null,

                'created_at'    => $record->created_at,

                'university_id' => $record->university_id,
                // 'university'    => $record->university
                //     ? [
                //         'id'   => $record->university->id,
                //         'name' => $record->university->name
                //     ]
                //     : null
                'university_name'  => $record->university->name ?? null, // flat
            ];

            return $request->expectsJson()
                ? $this->success('Record fetched successfully', $response)
                : view('hostels.show', compact('record'));
        } catch (ModelNotFoundException $e) {

            return $this->handleWebOrApiError(
                $request,
                'Record not found',
                404
            );
        } catch (Throwable $e) {
            Log::error('Record Show Error', ['exception' => $e]);
            return $this->handleWebOrApiError(
                $request,
                'Unable to fetch record details'
            );
        }
    }

    /* =====================================================
     * UPDATE : Record Update
     * ===================================================== */
    public function update(Request $request, $id)
    {
        // Log::info('Update Hostel Called', $request->all());
        $validated = $this->validateData($request, $id);

        DB::beginTransaction();

        try {
            $record = Building::findOrFail($id);

            // Normalize: prefer gender, fallback to type 

            $type = $validated['gender'] ?? $validated['type'] ?? null;
            Log::info('Determined Type', ['type' => $type]);

            // $record->update([
            //     'university_id' => auth()->user()->university_id,
            //     'name'       => $validated['name'],
            //     // 'code'       => $validated['code'],
            //     'gender'     => $type,
            //     'status'     => $validated['status'],
            //     'floors' => $validated['floors'],
            //     // 'updated_by' => auth()->id()
            // ]);

            //             Laravel gives this for free:
            // $record->isDirty('name')
            // But since we update via array, we check before update:
            // $nameChanged = $record->name !== $validated['name'];

            // Detect name change
            $nameChanged = $record->name !== $validated['name'];

            $updateData = [
                'university_id' => auth()->user()->university_id,
                'name'          => $validated['name'],
                'gender'        => $type,
                'status'        => $validated['status'],
                'floors'        => $validated['floors'],
            ];

            // 🔐 Update code ONLY if explicitly sent
            // if (!empty($validated['code'])) {
            //     $updateData['building_code'] = strtoupper($validated['code']);
            // }

            /*
        |--------------------------------------------------------------------------
        | Code Handling (KEY LOGIC)
        |--------------------------------------------------------------------------
        | 1. If code is NULL → generate
        | 2. If name changed → regenerate
        | 3. Else → keep existing
        */
            if (empty($record->building_code) || $nameChanged) {
                // $updateData['building_code'] = $this->generateBuildingCode(
                //     $record->university_id,
                //     $type
                // );
                $updateData['building_code'] = null; // Will be auto-generated in Model booted()
                
            }

            $record->update($updateData);

            DB::commit();

            return $request->expectsJson()
                ? $this->success('Record updated successfully', $record)
                : redirect()->back()->with('swal_success', 'Record updated successfully');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return $this->handleWebOrApiError(
                $request,
                'Record not found',
                404
            );
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Record Update DB Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Database error while updating record'
            );
        } catch (Throwable $e) {
            DB::rollBack();
            Log::critical('Record Update Error', ['exception' => $e]);
            return $this->handleWebOrApiError(
                $request,
                'Record update failed'
            );
        }
    }

    /* =====================================================
     * DELETE : Record Delete
     * ===================================================== */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $record = Building::findOrFail($id);
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
            'university_id' => 'required|exists:universities,id',
            'name' => 'required|string|max:255',
            // 'code' => [
            //     'required',
            //     'string',
            //     'max:50',
            //     // Rule::unique('faculties', 'code')->ignore($id)
            //     Rule::unique('buildings', 'building_code')
            //         ->where(
            //             fn($q) =>
            //             $q->where('university_id', auth()->user()->university_id)
            //         )
            //         ->ignore($id)
            // ],
            'code'   => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('buildings', 'building_code')
                    ->when($id, fn($q) => $q->ignore($id))
                    ->whereNull('deleted_at'),
            ],

            // Accept either gender or type 
            'gender' => 'sometimes|required|in:male,female,coed,boys,girls,mixed',
            'type' => 'sometimes|required|in:boys,girls,mixed,male,female,coed',
            'floors' => 'required|integer|min:1',
            'status' => 'nullable|boolean'
        ]);
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

    protected function generateBuildingCode($universityId, $gender)
    {
        $universityCode = strtoupper(
            University::where('id', $universityId)->value('code')
        );

        $typeCode = match ($gender) {
            'male'   => 'BOYS',
            'female' => 'GIRLS',
            default  => 'MIXED',
        };

        return DB::transaction(function () use ($universityId, $gender, $universityCode, $typeCode) {

            $lastCode = Building::where('university_id', $universityId)
                ->where('gender', $gender)
                ->withTrashed()
                ->lockForUpdate()
                ->orderByDesc('id')
                ->value('building_code');

            $lastSeq = 0;

            if ($lastCode) {
                $parts = explode('-', $lastCode);
                $lastSeq = (int) end($parts);
            }

            return sprintf(
                '%s-%s-%03d',
                $universityCode,
                $typeCode,
                $lastSeq + 1
            );
        });
    }
}
