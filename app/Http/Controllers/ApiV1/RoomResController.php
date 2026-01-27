<?php

namespace App\Http\Controllers\ApiV1;

use Throwable;
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
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoomResController extends Controller
{
    use ApiResponses;

    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except(['index', 'show']);
    }

    /* =====================================================
     * INDEX : List Records (WEB + AJAX + API)
     * ===================================================== */
    public function index(Request $request)
    {
        if (! $request->ajax()) {
            return view('rooms.index');
        }

        try {
            /*
            |--------------------------------------------------------------------------
            | Base Query (University-safe via scopeAccessible)
            |--------------------------------------------------------------------------
            */
            $query = Room::query()
                ->accessible()
                ->leftJoin('buildings', 'buildings.id', '=', 'rooms.building_id')
                ->leftJoin('universities', 'universities.id', '=', 'buildings.university_id')
                ->select([
                    'rooms.id',
                    'rooms.room_number',
                    'rooms.floor_no',
                    'rooms.room_type',
                    'rooms.capacity',
                    'rooms.status',
                    'rooms.created_at',

                    // Related display fields
                    'buildings.name as building_name',
                    'universities.name as university_name',

                    DB::raw("
                        CASE
                            WHEN rooms.status IN (1, '1', 'active', 'available', 'enabled', 'yes') THEN 1
                            ELSE 0
                        END as status_normalized
                    ")
                ]);

            /*
            |--------------------------------------------------------------------------
            | Summary (Clone-safe)
            |--------------------------------------------------------------------------
            */
            $buildings = Building::query()
                ->accessible()
                ->where('status', 1)
                ->orderBy('name')
                ->get(['id', 'name', 'floors']);

            $summary = [
                'total' => (clone $query)->count(),
                'active' => (clone $query)->whereRaw("
                    CASE
                        WHEN rooms.status IN (1, '1', 'active', 'enabled', 'yes') THEN 1
                        ELSE 0
                    END = 1
                ")->count(),
                'inactive' => (clone $query)->whereRaw("
                    CASE
                        WHEN rooms.status IN (1, '1', 'active', 'enabled', 'yes') THEN 1
                        ELSE 0
                    END = 0
                ")->count(),
            ];

            /*
            |--------------------------------------------------------------------------
            | Records Total
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
                    $q->where('rooms.room_number', 'like', "%{$search}%")
                        ->orWhere('rooms.room_type', 'like', "%{$search}%")
                        ->orWhere('rooms.floor_no', 'like', "%{$search}%")
                        ->orWhere('buildings.name', 'like', "%{$search}%")
                        ->orWhere('universities.name', 'like', "%{$search}%");

                    if (in_array(strtolower($search), ['active', 'inactive', '1', '0'])) {
                        $q->orWhereRaw("
                            CASE
                                WHEN rooms.status IN (1, '1', 'active', 'enabled', 'yes') THEN 1
                                ELSE 0
                            END = ?
                        ", [in_array(strtolower($search), ['active', '1']) ? 1 : 0]);
                    }
                });
            }

            $recordsFiltered = (clone $query)->count();

            /*
            |--------------------------------------------------------------------------
            | Ordering
            |--------------------------------------------------------------------------
            */
            $orderColumnIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir', 'desc');
            $columnKey = $request->input("columns.$orderColumnIndex.data");

            $orderable = [
                'room_number'   => 'rooms.room_number',
                'floor'     => 'rooms.floor_no',
                'type'      => 'rooms.room_type',
                'capacity'  => 'rooms.capacity',
                'status'    => 'status_normalized',
                'building'  => 'buildings.name',
                'university'  => 'universities.name',
                'created_at' => 'rooms.created_at',
            ];

            if (isset($orderable[$columnKey])) {
                $query->orderBy($orderable[$columnKey], $orderDir);
            } else {
                $query->orderBy('rooms.created_at', 'desc');
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
                'id'        => $r->id,
                'room_number'   => $r->room_number,
                'floor'     => $r->floor_no,
                'type'      => ucfirst($r->room_type),
                'capacity'  => $r->capacity,
                'status'    => (int) $r->status_normalized,
                'building'  => $r->building_name,
                'university'  => $r->university_name,
                'created_at' => optional($r->created_at)->format('d-m-Y'),
            ]);

            return response()->json([
                'draw'            => (int) $request->draw,
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data'            => $data,
                'meta'            => [
                    'summary' => $summary,
                    'buildings' => $buildings,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Room DataTable Error', ['exception' => $e->getMessage()]);

            return response()->json([
                'draw' => (int) $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ], 500);
        }
    }

    // $building = Building::accessible()
    // ->where('id', $request->building_id)
    // ->firstOrFail();
    /* =====================================================
     * STORE : Create Record
     * ===================================================== */
    public function store(Request $request)
    {
        Log::info('Determined Type', ['type' => $request->all()]);
        $validated = $this->validateData($request);

        DB::beginTransaction();

        try {

            $building = Building::accessible()
                ->where('id', $validated['building_id'])
                ->firstOrFail();

            if ($validated['floor_no'] > $building->floors) {
                abort(422, "Floor exceeds building floor limit ({$building->floors})");
            }

            // $type = $validated['gender'] ?? $validated['type'] ?? null;
            Log::info('Determined Type', ['type' => $building]);

            // Base payload
            $data = [
                'building_id' => $building->id,
                'floor_no'    => $validated['floor_no'],
                'room_number'     => strtoupper($validated['room_number']),
                'room_type'   => $validated['room_type'],
                'capacity'    => $validated['capacity'],
                'status'      => $validated['status'] ?? true,
                'created_by'  => auth()->id(),
            ];

            Log::info('Creating Room with Data', $data);

            // OPTIONAL: add code only if provided
            // if (!empty($validated['code'])) {
            //     $data['building_code'] = strtoupper($validated['code']);
            // }

            $record = Room::create($data);

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
            $record = Room::query()
                ->accessible() // 🔐 university + building level access
                ->with([
                    'building:id,name,gender,floors,university_id',
                    'building.university:id,name'
                ])
                ->where('rooms.id', $id)
                ->firstOrFail();

            // Controlled response structure
            // Optional: normalize status for frontend
            $response = [
                'id'          => $record->id,
                'room_number' => $record->room_number,
                'floor_no'    => $record->floor_no,
                'room_type'   => $record->room_type,
                'capacity'    => $record->capacity,
                // 'status'    => $record->status,
                'status' => $this->normalizeStatusForUI($record->status),
                // 'status'      => in_array(
                //     strtolower((string) $record->status),
                //     ['1', 'active', 'available', 'enabled', 'yes']
                // ) ? 1 : 0,
                'created_at'  => optional($record->created_at)->format('d-m-Y'),

                // Relations
                'building' => [
                    'id'     => $record->building->id,
                    'name'   => $record->building->name,
                    'gender' => $record->building->gender,
                    'floors' => $record->building->floors,
                ],

                // 'university' => [
                //     'id'   => $record->building->university->id,
                //     'name' => $record->building->university->name,
                // ],
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
        Log::info('Update Hostel Called', $request->all());
        $validated = $this->validateData($request, $id);

        DB::beginTransaction();

        try {
            // $record = Building::findOrFail($id);
            // 🔐 Fetch room with access control
            $record = Room::query()->accessible()->where('id', $id)->firstOrFail();


            // // Normalize: prefer gender, fallback to type 
            // $type = $validated['gender'] ?? $validated['type'] ?? null;
            // Log::info('Determined Type', ['type' => $type]);
            /*
        |--------------------------------------------------
        | Business Rule Guards (Future-proof)
        |--------------------------------------------------
        */

            // Example: Prevent activating room under maintenance
            if (
                $record->status === Room::STATUS_MAINTENANCE &&
                $validated['status'] === Room::STATUS_ACTIVE
            ) {
                throw new \DomainException(
                    'Room under maintenance cannot be activated directly'
                );
            }

            // Example: Reserved room cannot be inactivated silently
            if (
                $record->status === Room::STATUS_RESERVED &&
                $validated['status'] === Room::STATUS_INACTIVE
            ) {
                throw new \DomainException(
                    'Reserved room must be released before deactivation'
                );
            }

            $updateData = [
                'room_number' => $validated['room_number'],
                'floor_no'    => $validated['floor_no'],
                'room_type'   => $validated['room_type'],
                'capacity'    => $validated['capacity'],
                'status'      => $validated['status'],
                'updated_by'  => auth()->id(),
            ];

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
