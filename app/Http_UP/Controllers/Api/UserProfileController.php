<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    // ProfileController.php

    // public function getUserProfile(Request $request)
    // {
    //     Log::info('Profile request headers', $request->headers->all());

    //     try {
    //         $userId = $request->header('auth-id');
    //         if (!$userId) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Auth-ID header missing',
    //                 'data' => null
    //             ], 401);
    //         }

    //         // Fetch user
    //         $user = User::with([
    //             'resident.bed.room.building', // optional relation
    //             'guest', // optional
    //             'creator' // optional
    //         ])->find($userId);

    //         if (!$user) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'User not found',
    //                 'data' => null
    //             ], 404);
    //         }

    //         // Roles and permissions (via Spatie trait)
    //         $roles = $user->getRoleNames(); // returns a collection
    //         $permissions = $user->getAllPermissions()->pluck('name');

    //         // Build profile data
    //         $profileData = [
    //             'id' => $user->id,
    //             'name' => $user->name ?? 'N/A',
    //             'email' => $user->email ?? 'N/A',
    //             'roles' => $roles,
    //             'permissions' => $permissions,
    //         ];

    //         // Include resident info if exists
    //         if ($user->resident) {
    //             $profileData['resident'] = [
    //                 'scholar_no' => $user->resident->scholar_no ?? null,
    //                 'number' => $user->resident->number ?? null,
    //                 'bed_id' => $user->resident->bed_id ?? null,
    //                 'room' => optional($user->resident->bed->room)->name,
    //                 'building' => optional($user->resident->bed->room->building)->name,
    //                 'guest_id' => $user->resident->guest_id ?? null
    //             ];
    //         }

    //         // Include guest info if exists
    //         if ($user->guest) {
    //             $profileData['guest'] = [
    //                 'guest_id' => $user->guest->id ?? null,
    //                 'contact_number' => $user->guest->contact_number ?? null
    //             ];
    //         }

    //         // Creator info
    //         if ($user->creator) {
    //             $profileData['created_by'] = [
    //                 'id' => $user->creator->id,
    //                 'name' => $user->creator->name
    //             ];
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Profile fetched successfully',
    //             'data' => $profileData
    //         ], 200);
    //     } catch (\Exception $e) {
    //         Log::error('Profile fetch failed', ['exception' => $e->getMessage()]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to fetch profile',
    //             'data' => null,
    //             'errors' => ['exception' => $e->getMessage()]
    //         ], 500);
    //     }
    // }

    public function getUserProfile(Request $request)
    {
        try {
            $userId = $request->header('auth-id');

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Auth-ID header missing',
                    'data' => null
                ], 401);
            }

            // Fetch user with minimal relations needed for profile
            $user = User::with([
                // 'resident:id,user_id,scholar_no,number,parent_no,guardian_no,fathers_name,mothers_name,gender',
                'resident.bed.room.building',
                // 'guest:id,name,email,gender,number,parent_no,guardian_no,status',
            ])->find($userId);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                    'data' => null
                ], 404);
            }

            // Roles and permissions via Spatie
            $roles = $user->getRoleNames();
            $permissions = $user->getAllPermissions()->pluck('name');

            // Build minimal profile data
            $profileData = [
                'id' => $user->id,
                'name' => $user->name ?? 'N/A',
                'email' => $user->email ?? 'N/A',
                'gender' => $user->gender ?? 'N/A',
                'roles' => $roles,
                'permissions' => $permissions,
            ];

            // Include resident info if exists
            if ($user->resident) {
                $resident = $user->resident;
                $profileData['resident'] = [
                    'scholar_no' => $resident->scholar_no,
                    'number' => $resident->number,
                    'fathers_name' => $resident->fathers_name,
                    'mothers_name' => $resident->mothers_name,
                    'guardian_no' => $resident->guardian_no,
                    'bed_number' => optional($resident->bed)->bed_number,
                    'room_number' => optional($resident->bed->room)->room_number,
                    'building_name' => optional($resident->bed->room->building)->name,
                    'building_code' => optional($resident->bed->room->building)->building_code,
                ];
            }

            // Include guest info if exists
            if (method_exists($user, 'guest') && $user->guest) {
                // if ($user->guest) {
                $profileData['guest'] = [
                    'name' => $user->guest->name,
                    'email' => $user->guest->email,
                    'gender' => $user->guest->gender,
                    'number' => $user->guest->number,
                    'parent_no' => $user->guest->parent_no,
                    'guardian_no' => $user->guest->guardian_no,
                    'status' => $user->guest->status,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile fetched successfully',
                'data' => $profileData
            ], 200);
        } catch (\Exception $e) {
            Log::error('Profile fetch failed', ['exception' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profile',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }
}
