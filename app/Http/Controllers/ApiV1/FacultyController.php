<?php

namespace App\Http\Controllers\ApiV1;

use Throwable;
use App\Models\Faculty;
use App\Models\University;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FacultyController extends Controller
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
    //     try {
    //         // $faculties = Faculty::orderBy('id', 'desc')->get();
    //         // $faculties = Faculty::with('university:id,name')->latest()->get();

    //         $query = Faculty::with('university:id,name')
    //             ->select('id', 'name', 'status', 'university_id', 'created_at')
    //             ->latest();

    //         // 🔐 University-based restriction (future-ready)
    //         if (
    //             auth()->check() &&
    //             auth()->user()->university_id &&
    //             !auth()->user()->is_super_admin
    //         ) {
    //             $query->where('university_id', auth()->user()->university_id);
    //         }

    //         $faculties = $query->get();

    //         // 🔑 Fetch universities ONLY ONCE (minimal fields)
    //         // $universities = \App\Models\University::query()
    //         //     ->select('id', 'name')
    //         //     ->where('status', 1)
    //         //     ->orderBy('name')
    //         //     ->get();

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Universities: ROLE BASED
    //     |--------------------------------------------------------------------------
    //     */
    //         // Log::info('Determining'. auth()->user()->university_id);
    //         if (
    //             auth()->check() &&
    //             auth()->user()->university_id &&
    //             !auth()->user()->is_super_admin
    //         ) {
    //             // Log::info('Fetching universities for admin user');
    //             // Admin → ONLY their university
    //             $universities = \App\Models\University::query()
    //                 ->select('id', 'name')
    //                 ->where('id', auth()->user()->university_id)
    //                 ->where('status', 1)
    //                 ->get();
    //         } else {
    //             // Log::info('Fetching universities for user');
    //             // Super admin → ALL universities
    //             $universities = \App\Models\University::query()
    //                 ->select('id', 'name')
    //                 ->where('status', 1)
    //                 ->orderBy('name')
    //                 ->get();
    //         }

    //         // if ($request->expectsJson()) {
    //         //     return $faculties->isEmpty()
    //         //         ? $this->success('No faculty records found', [])
    //         //         : $this->success('Faculty list fetched successfully', $faculties);
    //         // }

    //         if ($request->expectsJson()) {

    //             return $this->success(
    //                 $faculties->isEmpty()
    //                     ? 'No faculty records found'
    //                     : 'Faculty list fetched successfully',
    //                 [
    //                     'faculties'    => $faculties,
    //                     'universities' => $universities
    //                 ]
    //             );
    //         }

    //         return view('faculties.index', compact('faculties'));
    //     } catch (Throwable $e) {
    //         Log::error('Faculty Index Error', ['exception' => $e]);

    //         return $this->handleWebOrApiError(
    //             $request,
    //             'Unable to load faculties',
    //             500
    //         );
    //     }
    // }

    public function index(Request $request)
    {
        // Page load
        if (!$request->ajax()) {
            return view('faculties.index');
        }

        try {

            /*
        |--------------------------------------------------------------------------
        | Accessible Universities (ONCE)
        |--------------------------------------------------------------------------
        */
            if (
                auth()->check() &&
                auth()->user()->university_id &&
                !auth()->user()->is_super_admin
            ) {
                // University Admin → Only their university
                $universities = \App\Models\University::query()
                    ->select('id', 'name')
                    ->where('id', auth()->user()->university_id)
                    ->where('status', 1)
                    ->get();
            } else {
                // Super Admin → All universities
                $universities = \App\Models\University::query()
                    ->select('id', 'name')
                    ->where('status', 1)
                    ->orderBy('name')
                    ->get();
            }

            /*
        |--------------------------------------------------------------------------
        | Faculty Query
        |--------------------------------------------------------------------------
        */
            $query = Faculty::query()
                ->with('university:id,name')
                ->select('id', 'name', 'status', 'university_id', 'created_at');

            if (
                auth()->check() &&
                auth()->user()->university_id &&
                !auth()->user()->is_super_admin
            ) {
                $query->where('university_id', auth()->user()->university_id);
            }

            $facultySummary = [
                'total'    => (clone $query)->count(),
                'active'   => (clone $query)->where('status', 1)->count(),
                'inactive' => (clone $query)->where('status', 0)->count(),
            ];

            /*
        |--------------------------------------------------------------------------
        | Counts
        |--------------------------------------------------------------------------
        */
            $recordsTotal = (clone $query)->count();

            /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas(
                            'university',
                            fn($uq) =>
                            $uq->where('name', 'like', "%{$search}%")
                        );
                });
            }

            $recordsFiltered = (clone $query)->count();

            /*
        |--------------------------------------------------------------------------
        | Ordering
        |--------------------------------------------------------------------------
        */
            $columns = [
                0 => 'id',
                1 => 'name',
                2 => 'status',
                3 => 'created_at',
            ];

            $orderColumn = $columns[$request->input('order.0.column')] ?? 'id';
            $orderDir    = $request->input('order.0.dir', 'desc');

            /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */
            $faculties = $query
                ->orderBy($orderColumn, $orderDir)
                ->skip($request->start)
                ->take($request->length)
                ->get()
                ->map(fn($faculty) => [
                    'id'         => $faculty->id,
                    'name'       => $faculty->name,
                    'university' => optional($faculty->university)->name,
                    'status'     => (int) $faculty->status,
                    'created_at' => $faculty->created_at->format('d-m-Y'),
                ]);

            /*
        |--------------------------------------------------------------------------
        | FINAL RESPONSE
        |--------------------------------------------------------------------------
        */
            return response()->json([
                // DataTables required
                'draw'            => (int) $request->draw,
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data'            => $faculties,

                // Extra payload (SAFE)
                'meta' => [
                    'universities' => $universities,
                    'summary'      => $facultySummary,
                ],
            ]);
        } catch (\Throwable $e) {

            Log::error('Faculty DataTable Error', ['exception' => $e]);

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
     * STORE : Create Faculty
     * ===================================================== */
    public function store(Request $request)
    {
        $validated = $this->validateFaculty($request);

        DB::beginTransaction();

        try {
            $faculty = Faculty::create([
                'university_id' => auth()->user()->university_id,
                'name'       => $validated['name'],
                // 'code'       => $validated['code'],
                'status'     => $validated['status'] ?? true,
                // 'created_by' => auth()->id()
            ]);

            DB::commit();

            return $request->expectsJson()
                ? $this->success('Faculty created successfully', $faculty, 201)
                : redirect()->back()->with('swal_success', 'Faculty created successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Faculty Store DB Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Database error while creating faculty'
            );
        } catch (Throwable $e) {
            DB::rollBack();
            Log::critical('Faculty Store Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Faculty creation failed'
            );
        }
    }

    /* =====================================================
     * SHOW : Faculty Details
     * ===================================================== */
    public function show(Request $request, $id)
    {
        try {
            // $faculty = Faculty::findOrFail($id);
            $faculty = Faculty::with([
                'university:id,name'
            ])->select(['id', 'university_id', 'name', 'status', 'created_at'])->findOrFail($id);

            // Controlled response structure
            $response = [
                'id'            => $faculty->id,
                'name'          => $faculty->name,
                'status'        => $faculty->status,
                'created_at'    => $faculty->created_at,

                'university_id' => $faculty->university_id,
                // 'university'    => $faculty->university
                //     ? [
                //         'id'   => $faculty->university->id,
                //         'name' => $faculty->university->name
                //     ]
                //     : null
                'university_name'  => $faculty->university->name ?? null, // flat
            ];

            return $request->expectsJson()
                ? $this->success('Faculty fetched successfully', $response)
                : view('faculties.show', compact('faculty'));
        } catch (ModelNotFoundException $e) {

            return $this->handleWebOrApiError(
                $request,
                'Faculty not found',
                404
            );
        } catch (Throwable $e) {
            Log::error('Faculty Show Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Unable to fetch faculty details'
            );
        }
    }

    /* =====================================================
     * UPDATE : Faculty Update
     * ===================================================== */
    public function update(Request $request, $id)
    {
        $validated = $this->validateFaculty($request, $id);

        DB::beginTransaction();

        try {
            $faculty = Faculty::findOrFail($id);

            $faculty->update([
                'university_id' => auth()->user()->university_id,
                'name'       => $validated['name'],
                // 'code'       => $validated['code'],
                'status'     => $validated['status'],
                // 'updated_by' => auth()->id()
            ]);

            DB::commit();

            return $request->expectsJson()
                ? $this->success('Faculty updated successfully', $faculty)
                : redirect()->back()->with('swal_success', 'Faculty updated successfully');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return $this->handleWebOrApiError(
                $request,
                'Faculty not found',
                404
            );
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Faculty Update DB Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Database error while updating faculty'
            );
        } catch (Throwable $e) {
            DB::rollBack();
            Log::critical('Faculty Update Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Faculty update failed'
            );
        }
    }

    /* =====================================================
     * DELETE : Faculty Delete
     * ===================================================== */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $faculty = Faculty::findOrFail($id);
            $faculty->delete();

            DB::commit();

            return $request->expectsJson()
                ? $this->success('Faculty deleted successfully')
                : redirect()->back()->with('swal_success', 'Faculty deleted successfully');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return $this->handleWebOrApiError(
                $request,
                'Faculty not found',
                404
            );
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Faculty Delete DB Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Database error while deleting faculty'
            );
        } catch (Throwable $e) {
            DB::rollBack();
            Log::critical('Faculty Delete Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Faculty deletion failed'
            );
        }
    }

    /* =====================================================
     * VALIDATION
     * ===================================================== */
    private function validateFaculty(Request $request, $id = null): array
    {
        return $request->validate([
            'university_id' => 'required|exists:universities,id',
            'name' => 'required|string|max:255',
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
}
