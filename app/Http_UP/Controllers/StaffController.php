<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\Permission\Models\Role;
use App\Helpers\Helper;

class StaffController extends Controller
{
    public function createStaff(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'buildings' => 'required|array|min:1',
                'buildings.*' => 'exists:buildings,id',
                'role' => 'required|string|in:warden,security,mess_manager,gym_manager,hod,accountant,admission',
            ]);

            // $building_ids = is_array($request->buildings) ? $request->buildings[0] : $request->buildings;
            $building_ids = $request->buildings; // Array will be 

            $staff = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                // 'building_id' => $validated['buildings'], // ✅ array will be saved as JSON
                // Ensure this is a single value, not an array
                'building_id' => $building_ids,
                'university_id'=>Helper::get_auth_admin_user($request)->university_id,
            ]);

            $staff->assignRole($validated['role']);


            return response()->json([
                'success' => true,
                'message' => 'Staff created successfully',
                'data' => $staff,
                'errors' => null,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::info($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'data' => null,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }

    public function createHod(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'department_id' => 'required|exists:departments,id',
                'status' => 'sometimes|boolean',
            ]);

            $hod = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'department_id' => $validated['department_id'],                 
                'status' => $validated['status'] ?? 1, // Default to active if not provided
                'university_id'=>Helper::get_auth_admin_user($request)->university_id,
            ]);

            $hod->assignRole(Role::firstOrCreate(['name' => 'hod']));
            return response()->json([
                'success' => true,
                'message' => 'HOD created successfully',
                'data' => $hod,
                'errors' => null,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log::info($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'data' => null,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }   

    public function getStaffDetails(Request $request, $id)
    {
        try {
            $users = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['warden', 'security']);
            })
            ->with('roles:id,name')
            ->where('id',$id)
            ->select('id', 'name', 'email','building_id')
            ->first();

            // Map each user to include building names
            $buildingIds = is_array($users->building_id) 
                ? $users->building_id 
                : (empty($users->building_id) ? [] : [$users->building_id]);
            Log::info($buildingIds);

            // Fetch building names
            $buildings = \App\Models\Building::whereIn('id', $buildingIds)->pluck('name');

            // Attach building names to user (as array of strings)
                $users->buildings = $buildings->map(fn($name) => (string) $name)->toArray();

            
            if (!$users) {   // 👈 instead of ->isEmpty()
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                    'data' => null,
                    'errors' => null,
                ], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'Staff retrieved successfully',
                'data' => $users,
                'errors' => null,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching staff details: ' . $e->getMessage(), [
                'id' => $id,
                'request' => $request->all(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching staff',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }


    public function getStaff()
    {
        try {
            $users = User::whereHas('roles', function ($query) {
                $query->whereNotIn('name', ['super_admin', 'admin', 'resident']);
            })->with('roles:id,name')->select('id', 'name', 'email')->get();

            if ($users->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No staff found',
                    'data' => null,
                    'errors' => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Staff retrieved successfully',
                'data' => $users,
                'errors' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching staff',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }

    public function getAllStaff(Request $request)
    {
        try {
            $user=Helper::get_auth_admin_user($request);
            $staff = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['warden', 'security']);
            })
            ->with([
                'roles:id,name',
                // 'building:id,name'
            ])
            // ->select('id', 'name', 'email', 'building_id')
            ->where('university_id',$user->university_id)   
            ->get();
   
            // Map each user to include building names
            $staff->each(function ($user) {
                // Always normalize building_id to an array
                $buildingIds = is_array($user->building_id) 
                    ? $user->building_id 
                    : (empty($user->building_id) ? [] : [$user->building_id]);

                // Fetch building names
                $buildings = \App\Models\Building::whereIn('id', $buildingIds)->pluck('name');

                // Attach building names to user (as array of strings)
                $user->buildings = $buildings->map(fn($name) => (string) $name)->toArray();
            });
            // Log::info($staff);
            
            if ($staff->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No staff found for specified roles',
                    'data' => null,
                    'errors' => null,
                ], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'All staff retrieved successfully',
                'data' => $staff,
                'errors' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching all staff',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }

    public function updateStaff(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string',
                'email' => [
                    'sometimes',
                    'required',
                    'email',
                    Rule::unique('users')->ignore($id),
                ],
                'buildings' => 'required|array|min:1',
                'buildings.*' => 'exists:buildings,id',
                'role' => 'sometimes|required|string|in:warden,security,mess_manager,gym_manager',
            ]);

            $staff = User::findOrFail($id);

            if (isset($validated['name'])) $staff->name = $validated['name'];
            if (isset($validated['email'])) $staff->email = $validated['email'];
            if (isset($validated['password'])) $staff->password = Hash::make($validated['password']);
            if (isset($validated['buildings'])) $staff->building_id = $validated['buildings'];

            $staff->save();

            if (isset($validated['role'])) {
                $staff->syncRoles([$validated['role']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Staff updated successfully',
                'data' => $staff,
                'errors' => null,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Staff not found',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'data' => null,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }

    public function getAllHods()
    {
        try {
            $hods = User::whereHas('roles', function ($query) {
                $query->where('name', 'hod');
            })
            ->with(['roles:id,name', 'department:id,name'])
            ->where('university_id', Helper::get_auth_admin_user(request())->university_id)
            ->whereNotNull('department_id')->get();
            // Log::info("HODs fetched: " . $hods);
            if ($hods->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No HODs found',
                    'data' => null,
                    'errors' => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'HODs retrieved successfully',
                'data' => $hods,
                'errors' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching HODs',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }   

    public function getHodDetails($id)
    {
        try {
            $hod = User::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'HOD details retrieved successfully',
                'data' => $hod,
                'errors' => null,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'HOD not found',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching HOD details',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }

    public function updateHod(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string',
                'email' => [
                    'sometimes',
                    'required',
                    'email',
                    Rule::unique('users')->ignore($id),
                ],
                'password' => 'sometimes|nullable|string|min:6',
                'department_id' => 'sometimes|required|exists:departments,id',
                'status' => 'sometimes|required|boolean',
            ]);

            $hod = User::findOrFail($id);

            if (isset($validated['name'])) $hod->name = $validated['name'];
            if (isset($validated['email'])) $hod->email = $validated['email'];
            if (isset($validated['department_id'])) $hod->department_id = $validated['department_id'];
            if (isset($validated['status'])) $hod->status = $validated['status'];

            $hod->save();

            return response()->json([
                'success' => true,
                'message' => 'HOD updated successfully',
                'data' => $hod,
                'errors' => null,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'HOD not found',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'data' => null,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }   

    public function getAllAdmin(Request $request)
    {
        try {
            $user=Helper::get_auth_admin_user($request);
            $admin = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['accountant','admission','gym_manager','mess_manager']);
            })
            ->with([
                'roles:id,name',
            ])
            ->where('university_id',$user->university_id)
            ->select('id', 'name', 'email','status')
            ->get();
            
            Log::info($admin);

            if ($admin->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No admin found for specified roles',
                    'data' => null,
                    'errors' => null,
                ], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'All admin retrieved successfully',
                'data' => $admin,
                'errors' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching all admin',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }

    public function createAdmin(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'role' => 'required|string|in:accountant,admission,admin,super_admin,gym_manager,mess_manager',
                'status' => 'sometimes|in:0,1', // 0 for inactive, 1 for active
            ]);

            $admin = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'university_id'=>Helper::get_auth_admin_user($request)->university_id,
                'password' => Hash::make($validated['password']),
                'status' => $validated['status'] ?? 1, // Default to active if not provided
                'created_by'=>$request->header('auth-id'),
            ]);

            $admin->assignRole($validated['role']);
            // Log::info("Create Admin Response: " . $admin);

            return response()->json([
                'success' => true,
                'message' => 'Admin created successfully',
                'data' => $admin,
                'errors' => null,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'data' => null,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }   

    public function getAdminDetails($id)
    {
        try {
            $admin = User::where('id', $id)->whereHas('roles', function ($query) {
                $query->whereIn('name', ['accountant','admission','admin','super_admin','gym_manager','mess_manager']);
                })
                ->with(['roles:id,name'])
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'message' => 'Admin details retrieved successfully',
                'data' => $admin,
                'errors' => null,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Admin not found',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching admin details',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }

    public function updateAdmin(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string',
                'email' => [
                    'sometimes',
                    'required',
                    'email',
                    Rule::unique('users')->ignore($id),
                ],
                'role' => 'sometimes|required|string|in:accountant,admission,admin,super_admin,gym_manager,mess_manager',
                'status' => 'sometimes|in:0,1', // 0 for inactive, 1 for active
            ]);

            $admin = User::findOrFail($id);

            if (isset($validated['name'])) $admin->name = $validated['name'];
            if (isset($validated['email'])) $admin->email = $validated['email'];
            if (isset($validated['status'])) $admin->status = $validated['status'];

            $admin->save();

            if (isset($validated['role'])) {
                $admin->syncRoles([$validated['role']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Admin updated successfully',
                'data' => $admin,
                'errors' => null,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Admin not found',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'data' => null,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }

}
