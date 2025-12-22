<?php

namespace App\Http\Controllers\ApiV1;

use App\Models\Faculty;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class FacultiesRes Controller extends Controller
{

    use ApiResponses;

    /**
     * Apply Sanctum + Web middleware
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except(['index']);
    }

    /**
     * Display listing (Web + AJAX)
     */
    public function index(Request $request)
    {
        try {
            $faculties = Faculty::latest()->get();

            if ($request->expectsJson()) {
                return $faculties->isEmpty()
                    ? $this->success('No faculty records found', [])
                    : $this->success('Faculty list fetched', $faculties);
            }

            return view('faculties.index', compact('faculties'));
        } catch (\Throwable $e) {
            Log::error('Faculty Index Error', ['error' => $e]);

            return $request->expectsJson()
                ? $this->error('Unable to fetch faculty list', [], 500)
                : back()->withErrors('Unable to fetch faculty list');
        }
    }


    /**
     * Store new faculty
     */
    public function store(Request $request)
    {
        $validated = $this->validateFaculty($request);

        DB::beginTransaction();

        try {
            $faculty = Faculty::create([
                'name'       => $validated['name'],
                'code'       => $validated['code'],
                'status'     => $validated['status'] ?? true,
                'created_by' => auth()->id()
            ]);

            DB::commit();

            return $request->expectsJson()
                ? $this->success('Faculty created successfully', $faculty, 201)
                : redirect()->back()->with('success', 'Faculty created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Faculty Store Error', ['error' => $e]);

            return $request->expectsJson()
                ? $this->error('Faculty creation failed')
                : back()->withErrors('Faculty creation failed');
        }
    }

    /**
     * Show faculty (API / Modal)
     */
    public function show(Request $request, $id)
    {
        try {
            $faculty = Faculty::findOrFail($id);

            return $request->expectsJson()
                ? $this->success('Faculty details fetched', $faculty)
                : view('faculties.show', compact('faculty'));
        } catch (ModelNotFoundException $e) {

            return $request->expectsJson()
                ? $this->error('Faculty not found', [], 404)
                : abort(404);
        } catch (\Throwable $e) {
            Log::error('Faculty Show Error', ['error' => $e]);

            return $request->expectsJson()
                ? $this->error('Failed to fetch faculty', [], 500)
                : back()->withErrors('Failed to fetch faculty');
        }
    }


    /**
     * Update faculty
     */
    public function update(Request $request, Faculty $faculty)
    {
        $validated = $this->validateFaculty($request, $faculty->id);

        DB::beginTransaction();

        try {
            $faculty->update([
                'name'       => $validated['name'],
                'code'       => $validated['code'],
                'status'     => $validated['status'],
                'updated_by' => auth()->id()
            ]);

            DB::commit();

            return $request->expectsJson()
                ? $this->success('Faculty updated successfully', $faculty)
                : redirect()->back()->with('success', 'Faculty updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Faculty Update Error', ['error' => $e]);

            return $request->expectsJson()
                ? $this->error('Faculty update failed')
                : back()->withErrors('Faculty update failed');
        }
    }

    /**
     * Delete faculty
     */
    public function destroy(Request $request, Faculty $faculty)
    {
        DB::beginTransaction();

        try {
            $faculty->delete();
            DB::commit();

            return $request->expectsJson()
                ? $this->success('Faculty deleted successfully')
                : redirect()->back()->with('success', 'Faculty deleted successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Faculty Delete Error', ['error' => $e]);

            return $request->expectsJson()
                ? $this->error('Faculty deletion failed')
                : back()->withErrors('Faculty deletion failed');
        }
    }

    /**
     * Central Validation Method
     */
    private function validateFaculty(Request $request, $id = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('faculties', 'code')->ignore($id)
            ],
            'status' => [
                'nullable',
                'boolean'
            ]
        ]);
    }
}


// error: function (xhr) {
//     if (xhr.status === 422) {
//         let errors = xhr.responseJSON.errors;
//         Object.keys(errors).forEach(field => {
//             $(`#${field}`).addClass('is-invalid');
//             $(`#${field}_error`).text(errors[field][0]);
//         });
//     } else {
//         toastr.error(xhr.responseJSON.message);
//     }
// }
