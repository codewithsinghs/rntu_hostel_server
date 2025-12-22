<?php

namespace App\Http\Controllers;

use Exception;
use App\Helpers\Helper;
use App\Models\FeeHead;
use PHPUnit\TextUI\Help;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FeeHeadController extends Controller
{
    // Reusable response format
    private function apiResponse($success, $message, $data = null, $statusCode = 200, $errors = null)
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        if (!is_null($data)) $response['data'] = $data;
        else $response['data'] = null;

        if (!is_null($errors)) $response['errors'] = $errors;
        else $response['errors'] = null;

        return response()->json($response, $statusCode);
    }

    /**
     * Store a new fee head
     */
    public function store(Request $request)
    {
        $user = Helper::get_auth_admin_user($request);
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fee_heads', 'name')
                    ->where(function ($q) use ($user) {
                        return $q->where('university_id', $user->university_id);
                    }),
            ],
            'is_mandatory' => 'required|boolean',
            'is_one_time' => 'required|boolean',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(false, 'Validation Error.', null, 422, $validator->errors());
        }

        try {
            $feeHead = FeeHead::create([
                'name' => $request->name,
                'created_by' => $user->id,
                'is_mandatory' => $request->is_mandatory,
                'is_one_time' => $request->is_one_time,
                'status' => $request->status,
                'university_id' => $user->university_id,
            ]);

            return $this->apiResponse(true, 'Fee head created successfully.', $feeHead, 201);
        } catch (\Exception $e) {
            return $this->apiResponse(false, 'Failed to create fee head.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Update an existing fee head
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'is_mandatory' => 'required|boolean',
            'is_one_time' => 'required|boolean',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(false, 'Validation Error.', null, 422, $validator->errors());
        }

        try {
            $feeHead = FeeHead::findOrFail($id);
            $feeHead->name = $request->name;
            $feeHead->is_mandatory = $request->is_mandatory;
            $feeHead->is_one_time = $request->is_one_time;
            $feeHead->status = $request->status;
            $feeHead->save();

            return $this->apiResponse(true, 'Fee head updated successfully.', $feeHead);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->apiResponse(false, 'Fee head not found.', null, 404);
        } catch (\Exception $e) {
            return $this->apiResponse(false, 'Failed to update fee head.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get all fee heads
     */
    public function index(Request $request)
    {
        // Log::info("request". json_encode($request->all()));
        $user = Helper::get_auth_admin_user($request);
        // Log::info("request". json_encode($user));
        try {
            $feeHeads = FeeHead::with('university')->where('university_id', $user->university_id)->orderBy('created_at', 'desc')->get();
            return $this->apiResponse(true, 'Fee heads retrieved successfully.', $feeHeads);
        } catch (\Exception $e) {
            return $this->apiResponse(false, 'Failed to retrieve fee heads.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get a single fee head by ID
     */
    public function show($id)
    {
        try {
            $feeHead = FeeHead::findOrFail($id);
            return $this->apiResponse(true, 'Fee head retrieved successfully.', $feeHead);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->apiResponse(false, 'Fee head not found.', null, 404);
        } catch (\Exception $e) {
            return $this->apiResponse(false, 'Failed to retrieve fee head.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Delete a fee head
     */
    public function destroy($id)
    {
        try {
            $feeHead = FeeHead::findOrFail($id);
            $feeHead->delete();

            return $this->apiResponse(true, 'Fee head deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->apiResponse(false, 'Fee head not found.', null, 404);
        } catch (\Exception $e) {
            return $this->apiResponse(false, 'Failed to delete fee head.', null, 500, ['error' => $e->getMessage()]);
        }
    }
}
