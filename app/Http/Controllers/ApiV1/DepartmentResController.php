<?php

namespace App\Http\Controllers\ApiV1;

use Throwable;
use App\Models\Faculty;
use App\Models\Department;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DepartmentResController extends Controller
{
    use ApiResponses;

    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except(['index', 'show']);
    }

    /* =====================================================
     * INDEX : List departments (WEB + AJAX + API)
     * ===================================================== */
    public function index(Request $request)
    {
        try {
            /* =====================================================
         | 1. Departments (FACULTY → UNIVERSITY SCOPED)
         ===================================================== */
            $departmentQuery = Department::query()
                ->with('faculty:id,name,university_id')
                ->select('id', 'name', 'status', 'faculty_id', 'created_at')
                ->latest();

            // 🔐 University admin → departments under their university
            if (
                auth()->check() &&
                auth()->user()->university_id &&
                !auth()->user()->is_super_admin
            ) {
                $departmentQuery->whereHas('faculty', function ($q) {
                    $q->where('university_id', auth()->user()->university_id);
                });
            }

            $departments = $departmentQuery->get();

            /* =====================================================
         | 2. Faculties (FOR DROPDOWN)
         ===================================================== */
            $facultyQuery = Faculty::query()
                ->select('id', 'name', 'university_id')
                ->where('status', 1)
                ->orderBy('name');

            // 🔐 University admin → faculties of their university only
            if (
                auth()->check() &&
                auth()->user()->university_id &&
                !auth()->user()->is_super_admin
            ) {
                $facultyQuery->where('university_id', auth()->user()->university_id);
            }

            $faculties = $facultyQuery->get();

            /* =====================================================
         | 3. API RESPONSE
         ===================================================== */
            if ($request->expectsJson()) {
                return $this->success(
                    $departments->isEmpty()
                        ? 'No department records found'
                        : 'Department list fetched successfully',
                    [
                        'departments' => $departments,
                        'faculties'   => $faculties
                    ]
                );
            }

            /* =====================================================
         | 4. WEB VIEW
         ===================================================== */
            return view('departments.index', compact('departments', 'faculties'));
        } catch (\Throwable $e) {
            Log::error('Department Index Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Unable to load departments',
                500
            );
        }
    }




    /* =====================================================
     * STORE : Create department
     * ===================================================== */
    public function store(Request $request)
    {
        Log::info('store request', $request->all());
        $validated = $this->validatedepartment($request);

        DB::beginTransaction();

        try {
            $department = Department::create([
                'faculty_id' => $validated['faculty_id'],
                'name'       => $validated['name'],
                // 'code'       => $validated['code'],
                'status'     => $validated['status'] ?? true,
                // 'created_by' => auth()->id()
            ]);

            DB::commit();

            return $request->expectsJson()
                ? $this->success('department created successfully', $department, 201)
                : redirect()->back()->with('swal_success', 'department created successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('department Store DB Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Database error while creating department'
            );
        } catch (Throwable $e) {
            DB::rollBack();
            Log::critical('department Store Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'department creation failed'
            );
        }
    }

    /* =====================================================
     * SHOW : department Details
     * ===================================================== */
    public function show(Request $request, $id)
    {
        try {
            // $department = department::findOrFail($id);
            $department = department::with([
                'faculty:id,name'
            ])->select(['id', 'faculty_id', 'name', 'status', 'created_at'])->findOrFail($id);

            // Controlled response structure
            $response = [
                'id'            => $department->id,
                'name'          => $department->name,
                'status'        => $department->status,
                'created_at'    => $department->created_at,

                'faculty_id' => $department->faculty_id,
                // 'faculty'    => $department->faculty
                //     ? [
                //         'id'   => $department->faculty->id,
                //         'name' => $department->faculty->name
                //     ]
                //     : null
                'faculty_name'  => $department->faculty->name ?? null, // flat
            ];

            return $request->expectsJson()
                ? $this->success('department fetched successfully', $response)
                : view('departments.show', compact('department'));
        } catch (ModelNotFoundException $e) {

            return $this->handleWebOrApiError(
                $request,
                'department not found',
                404
            );
        } catch (Throwable $e) {
            Log::error('department Show Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Unable to fetch department details'
            );
        }
    }

    /* =====================================================
     * UPDATE : department Update
     * ===================================================== */
    public function update(Request $request, $id)
    {
        $validated = $this->validatedepartment($request, $id);

        DB::beginTransaction();

        try {
            $department = department::findOrFail($id);

            $department->update([
                'faculty_id' => $validated['faculty_id'],
                'name'       => $validated['name'],
                // 'code'       => $validated['code'],
                'status'     => $validated['status'],
                // 'updated_by' => auth()->id()
            ]);

            DB::commit();

            return $request->expectsJson()
                ? $this->success('department updated successfully', $department)
                : redirect()->back()->with('swal_success', 'department updated successfully');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return $this->handleWebOrApiError(
                $request,
                'department not found',
                404
            );
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('department Update DB Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Database error while updating department'
            );
        } catch (Throwable $e) {
            DB::rollBack();
            Log::critical('department Update Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'department update failed'
            );
        }
    }

    /* =====================================================
     * DELETE : department Delete
     * ===================================================== */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $department = department::findOrFail($id);
            $department->delete();

            DB::commit();

            return $request->expectsJson()
                ? $this->success('department deleted successfully')
                : redirect()->back()->with('swal_success', 'department deleted successfully');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return $this->handleWebOrApiError(
                $request,
                'department not found',
                404
            );
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('department Delete DB Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Database error while deleting department'
            );
        } catch (Throwable $e) {
            DB::rollBack();
            Log::critical('department Delete Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'department deletion failed'
            );
        }
    }

    /* =====================================================
     * VALIDATION
     * ===================================================== */
    private function validatedepartment(Request $request, $id = null): array
    {
        return $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'name' => 'required|string|max:255',
            // 'code' => [
            //     'required',
            //     'string',
            //     'max:50',
            //     // Rule::unique('departments', 'code')->ignore($id)
            //     Rule::unique('departments')
            //         ->where(
            //             fn($q) =>
            //             $q->where('faculty_id', auth()->user()->faculty_id)
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
