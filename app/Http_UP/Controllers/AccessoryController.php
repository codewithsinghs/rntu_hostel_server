<?php

namespace App\Http\Controllers;

use Exception;
use App\Helpers\Helper;
use App\Models\Faculty;
use App\Models\Accessory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Validator;

class AccessoryController extends Controller
{
    private function apiResponse($success, $message, $data = null, $status = 200, $errors = null)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data ?? null,
            'errors' => $errors ?? null
        ], $status);
    }

    // ✅ Create or Update Accessory
    public function createOrUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'accessory_head_id' => 'required|exists:accessory_heads,id',
            'price' => 'required|numeric',
            'is_default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(false, 'Validation failed.', null, 422, $validator->errors());
        }

        $today = Carbon::today();

        try {
            $existing = Accessory::where('accessory_head_id', $request->accessory_head_id)
                ->where('is_active', true)
                ->first();

            if ($existing) {
                $fromDate = Carbon::parse($existing->from_date);
                $diffInDays = $fromDate->diffInDays($today, false);

                if ($diffInDays < 0) {
                    return $this->apiResponse(false, 'Cannot update accessory. Please wait at least 30 days from the last update.', null, 403, [
                        'days_remaining' => 0 - $diffInDays
                    ]);
                }

                $existing->update([
                    'to_date' => $today,
                    'is_active' => false,
                ]);
            }

            $newAccessory = Accessory::create([
                'accessory_head_id' => $request->accessory_head_id,
                'price' => $request->price,
                'is_default' => $request->is_default ?? false,
                'from_date' => $today,
                'is_active' => true,
                'created_by' => $request->header("auth-id") ?? null,
            ]);

            return $this->apiResponse(true, 'Accessory added/updated successfully.', $newAccessory, 201);
        } catch (Exception $e) {
            return $this->apiResponse(false, 'Something went wrong while creating/updating accessory.', null, 500, [
                'error' => $e->getMessage()
            ]);
        }
    }

    // ✅ Get All Accessories
    public function getAllAccessories(Request $request)
    {
        try {
            $user = Helper::get_auth_admin_user($request);
            $accessories = Accessory::with('accessoryHead')
                ->whereHas('accessoryHead', function ($query) use ($user) {
                    $query->where('university_id', $user->university_id);
                })
                ->get();
            // Log::info("Accessories fetched: " . json_encode($accessories));
            return $this->apiResponse(true, 'All accessories fetched successfully.', $accessories);
        } catch (Exception $e) {
            return $this->apiResponse(false, 'Failed to fetch accessories.', null, 500, [
                'error' => $e->getMessage()
            ]);
        }
    }

    // ✅ Get Active Accessories
    // public function getActiveAccessories(Request $request)
    // {
    //     Log::info("Fetching active accessories: ", $request->all());
    //     try {
    //         $user = Helper::get_auth_admin_user($request);
    //         Log::info("Fetching active accessories for University ID: ". json_encode($user));
    //         Log::info("Fetching active accessories for University ID: " . $user->university_id);
    //         $activeAccessories = Accessory::with('accessoryHead')
    //             ->where('is_active', true)
    //             ->whereHas('accessoryHead', function ($q) use ($user) {
    //                 $q->where('university_id', $user->university_id);
    //             })
    //             ->get();
    //             // Log::info("Active Accessories fetched: " . json_encode($activeAccessories));
    //         return $this->apiResponse(true, 'Active accessories fetched successfully.', $activeAccessories);
    //     } catch (Exception $e) {
    //         return $this->apiResponse(false, 'Failed to fetch active accessories.', null, 500, [
    //             'error' => $e->getMessage()
    //         ]);
    //     }
    // }
    public function getActiveAccessories(Request $request)
    {
        // Log::info("Fetching active accessories", $request->all());
        // Log::info("Headers", $request->headers->all());

        try {
            // Get the authenticated user via Sanctum
            // $user = $request->user(); // or auth()->user()
            // Try to resolve either User or Guest
            // $user = auth('sanctum')->user();
            // $user = auth('user')->user() ?? auth('guest')->user();
            // Try to get the authenticated user (via token/session)
            $user = $request->user();

            // If not available, fallback to auth-id header
            if (!$user) {
                $authId = $request->header('auth-id');
                if ($authId) {
                    $user = User::findOrFail($authId);
                }
            }

            // Log::info("Fetching auth user" . json_encode($user));
            if (!$user) {
                Log::warning("No authenticated user found");
                return $this->apiResponse(false, 'Unauthorized', null, 401);
            }

            // Log::info("Authenticated user", ['id' => $user->id, 'university_id' => $user->university_id]);

            $activeAccessories = Accessory::with('accessoryHead')
                ->where('is_active', true)
                ->whereHas('accessoryHead', function ($q) use ($user) {
                    $q->where('university_id', $user->university_id);
                })
                ->get();

            return $this->apiResponse(true, 'Active accessories fetched successfully.', $activeAccessories);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Validation failed", ['errors' => $e->errors()]);
            return $this->apiResponse(false, 'Validation error', null, 422, [
                'errors' => $e->errors()
            ]);
        } catch (\Exception $e) {
            Log::error("Unexpected error fetching accessories", ['error' => $e->getMessage()]);
            return $this->apiResponse(false, 'Failed to fetch active accessories.', null, 500, [
                'error' => $e->getMessage()
            ]);
        }
    }


    // ✅ Get Active Accessories
    public function getGuestActiveAccessories(Request $request)
    {
        try {
            $guest = Helper::get_auth_guest_user($request);
            $activeAccessories = Accessory::with('accessoryHead')
                ->where('is_active', true)
                ->whereHas('accessoryHead', function ($q) use ($guest) {
                    $q->where('university_id', $guest->faculty?->university_id);
                })
                ->get();
            // Log::info("Active Accessories fetched: " . json_encode($activeAccessories));
            return $this->apiResponse(true, 'Active accessories fetched successfully.', $activeAccessories);
        } catch (Exception $e) {
            return $this->apiResponse(false, 'Failed to fetch active accessories.', null, 500, [
                'error' => $e->getMessage()
            ]);
        }
    }


    // ✅ Get Active Accessories
    public function getPublicActiveAccessories($faculty_id)
    {
        try {
            $faculty = Faculty::findOrFail($faculty_id);

            $activeAccessories = Accessory::with('accessoryHead')
                ->where('is_active', true)
                ->whereHas('accessoryHead', function ($query) use ($faculty) {
                    $query->where('university_id', $faculty->university_id);
                })
                ->get();
            // Log::Info($activeAccessories);    
            return $this->apiResponse(true, 'Active accessories fetched successfully.', $activeAccessories);
        } catch (Exception $e) {
            return $this->apiResponse(false, 'Failed to fetch active accessories.', null, 500, [
                'error' => $e->getMessage()
            ]);
        }
    }


    // ✅ Get Accessory By ID
    public function getAccessoryById($id)
    {
        try {
            $accessory = Accessory::with('accessoryHead')->findOrFail($id);

            return $this->apiResponse(true, 'Accessory fetched successfully.', $accessory);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->apiResponse(false, 'Accessory not found.', null, 404);
        } catch (Exception $e) {
            return $this->apiResponse(false, 'Failed to fetch accessory.', null, 500, [
                'error' => $e->getMessage()
            ]);
        }
    }

    // ✅ Delete Accessory
    public function deleteAccessory($id)
    {
        try {
            $accessory = Accessory::findOrFail($id);
            $accessory->delete();

            return $this->apiResponse(true, 'Accessory deleted successfully.', null);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->apiResponse(false, 'Accessory not found.', null, 404);
        } catch (Exception $e) {
            return $this->apiResponse(false, 'Failed to delete accessory.', null, 500, [
                'error' => $e->getMessage()
            ]);
        }
    }


    // ✅ Get All Accessories
    public function ResidentAccessories($residentId)
    {
        try {
            $accessories = Accessory::where('resident_id', $residentId)->with('accessoryHead')->get();


            return $this->apiResponse(true, 'All accessories fetched successfully.', $accessories);
        } catch (Exception $e) {
            return $this->apiResponse(false, 'Failed to fetch accessories.', null, 500, [
                'error' => $e->getMessage()
            ]);
        }
    }
}
