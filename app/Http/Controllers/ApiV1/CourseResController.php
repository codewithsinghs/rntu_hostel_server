<?php

namespace App\Http\Controllers\ApiV1;

use Throwable;
use App\Models\Course;
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

class CourseResController extends Controller
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
        | 1. COURSES (DEPARTMENT → FACULTY → UNIVERSITY SCOPED)
        ===================================================== */
            $courseQuery = Course::query()
                ->with([
                    'department:id,name,faculty_id',
                    'department.faculty:id,name,university_id'
                ])
                ->select('id', 'name', 'status', 'department_id', 'created_at')
                ->latest();

            // 🔐 University admin → only their university courses
            if (
                auth()->check() &&
                auth()->user()->university_id &&
                !auth()->user()->is_super_admin
            ) {
                $courseQuery->whereHas('department.faculty', function ($q) {
                    $q->where('university_id', auth()->user()->university_id);
                });
            }

            $courses = $courseQuery->get();

            /* =====================================================
        | 2. FACULTIES (FOR DROPDOWN)
        ===================================================== */
            $facultyQuery = Faculty::query()
                ->select('id', 'name', 'university_id')
                ->where('status', 1)
                ->orderBy('name');

            if (
                auth()->check() &&
                auth()->user()->university_id &&
                !auth()->user()->is_super_admin
            ) {
                $facultyQuery->where('university_id', auth()->user()->university_id);
            }

            $faculties = $facultyQuery->get();

            // University Scoping – optional but recommended
            // $departmentAllowed = Department::where('id', $validated['department_id'])
            //     ->whereHas(
            //         'faculty',
            //         fn($q) =>
            //         $q->where('university_id', auth()->user()->university_id)
            //     )
            //     ->exists();

            // abort_if(!$departmentAllowed, 403, 'Unauthorized department');


            /* =====================================================
        | 3. DEPARTMENTS (FOR DROPDOWN)
        ===================================================== */
            $departmentQuery = Department::query()
                ->select('id', 'name', 'faculty_id')
                ->where('status', 1)
                ->orderBy('name');

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
        | 4. API RESPONSE
        ===================================================== */
            if ($request->expectsJson()) {
                return $this->success(
                    $courses->isEmpty()
                        ? 'No course records found'
                        : 'Course list fetched successfully',
                    [
                        'courses'     => $courses,
                        'faculties'   => $faculties,
                        'departments' => $departments
                    ]
                );
            }

            /* =====================================================
        | 5. WEB VIEW
        ===================================================== */
            return view('courses.index', compact(
                'courses',
                'faculties',
                'departments'
            ));
        } catch (\Throwable $e) {
            Log::error('Course Index Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Unable to load courses',
                500
            );
        }
    }

    /* =====================================================
     * STORE : Create department
     * ===================================================== */
    public function store(Request $request)
    {
        Log::info('Course Store Request', $request->all());

        $validated = $this->validateCourse($request);

        DB::beginTransaction();

        try {
            /* =====================================================
        | CREATE COURSE
        ===================================================== */
            $course = Course::create([
                'department_id' => $validated['department_id'],
                'name'          => $validated['name'],
                // 'code'          => $validated['code'] ?? null,
                'status'        => $validated['status'] ?? true,
                // 'created_by' => auth()->id()
            ]);

            DB::commit();

            /* =====================================================
        | RESPONSE
        ===================================================== */
            return $request->expectsJson()
                ? $this->success('Course created successfully', [
                    'id'            => $course->id,
                    'name'          => $course->name,
                    'department_id' => $course->department_id,
                    'status'        => $course->status
                ], 201)
                : redirect()->back()->with('swal_success', 'Course created successfully');
        } catch (QueryException $e) {

            DB::rollBack();
            Log::error('Course Store DB Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Database error while creating course'
            );
        } catch (\Throwable $e) {

            DB::rollBack();
            Log::critical('Course Store Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Course creation failed'
            );
        }
    }


    /* =====================================================
     * SHOW : course Details
     * ===================================================== */
    public function show(Request $request, $id)
    {
        try {
            /* =====================================================
        | COURSE WITH DEPARTMENT & FACULTY
        ===================================================== */
            $course = Course::with([
                'department:id,name,faculty_id',
                'department.faculty:id,name'
            ])
                ->select([
                    'id',
                    'department_id',
                    'name',
                    // 'code',
                    'status',
                    'created_at'
                ])
                ->findOrFail($id);

            /* =====================================================
        | CONTROLLED / FLAT RESPONSE (JS FRIENDLY)
        ===================================================== */
            $response = [
                'id'             => $course->id,
                'name'           => $course->name,
                // 'code'           => $course->code,
                'status'         => $course->status,
                'created_at'     => $course->created_at,

                'department_id'  => $course->department_id,
                'department_name' => $course->department->name ?? null,

                'faculty_id'     => $course->department->faculty_id ?? null,
                'faculty_name'   => $course->department->faculty->name ?? null,
            ];

            return $request->expectsJson()
                ? $this->success('Course fetched successfully', $response)
                : view('courses.show', compact('course'));
        } catch (ModelNotFoundException $e) {

            return $this->handleWebOrApiError(
                $request,
                'Course not found',
                404
            );
        } catch (\Throwable $e) {

            Log::error('Course Show Error', [
                'course_id' => $id,
                'exception' => $e
            ]);

            return $this->handleWebOrApiError(
                $request,
                'Unable to fetch course details'
            );
        }
    }


    /* =====================================================
     * UPDATE : course Update
     * ===================================================== */
    public function update(Request $request, $id)
    {
        // ✅ Validate course data
        $validated = $this->validateCourse($request, $id);

        DB::beginTransaction();

        try {
            $course = Course::findOrFail($id);

            $course->update([
                'department_id' => $validated['department_id'],
                'name'          => $validated['name'],
                // 'code'          => $validated['code'] ?? null,
                'status'        => $validated['status'],
                // 'updated_by' => auth()->id(),
            ]);

            DB::commit();

            return $request->expectsJson()
                ? $this->success('Course updated successfully', $course)
                : redirect()->back()->with('swal_success', 'Course updated successfully');
        } catch (ModelNotFoundException $e) {

            DB::rollBack();

            return $this->handleWebOrApiError(
                $request,
                'Course not found',
                404
            );
        } catch (QueryException $e) {

            DB::rollBack();
            Log::error('Course Update DB Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Database error while updating course'
            );
        } catch (\Throwable $e) {

            DB::rollBack();
            Log::critical('Course Update Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Course update failed'
            );
        }
    }


    /* =====================================================
     * DELETE : course Delete
     * ===================================================== */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $course = Course::findOrFail($id);
            $course->delete();

            DB::commit();

            return $request->expectsJson()
                ? $this->success('Course deleted successfully')
                : redirect()->back()->with('swal_success', 'Course deleted successfully');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return $this->handleWebOrApiError(
                $request,
                'course not found',
                404
            );
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Course Delete DB Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'Database error while deleting course'
            );
        } catch (Throwable $e) {
            DB::rollBack();
            Log::critical('Course Delete Error', ['exception' => $e]);

            return $this->handleWebOrApiError(
                $request,
                'course deletion failed'
            );
        }
    }

    /* =====================================================
     * VALIDATION
     * ===================================================== */
    private function validateCourse(Request $request, $id = null): array
    {
        return $request->validate([
            // 'faculty_id' => ['required', 'exists:faculties,id'], // for dropdown logic only
            'department_id' => ['required', 'exists:departments,id'],
            // 'name' => 'required|string|max:255',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('courses', 'name')
                    ->where('department_id', $request->department_id)
                    ->ignore($id)
            ],
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
