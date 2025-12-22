<?php
// Server
namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Bed;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use App\Helpers\Helper;
use App\Models\BedAssignmentHistory;

class ResidentController extends Controller
{
    public function getAllResidents(Request $request)
    {
        try {
            $user = Helper::get_auth_admin_user($request);
            $residents = Resident::with(['user', 'bed.room.building', 'guest', 'creator', 'guest.faculty', 'guest.department', 'guest.course'])
                ->whereHas('user', function ($q) use ($user) {
                    $q->where('university_id', $user->university_id);
                })
                ->get();
                // Log::info('Residents: ' . $residents->first());
            // Log::info($residents->count() . ' residents fetched for university_id: ' . $user->university_id);
            return response()->json([
                'success' => true,
                'message' => 'All residents fetched successfully',
                'data' => $residents,
                'errors' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch residents',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }

    public function getResidentById($id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid resident ID.',
                    'data' => null,
                    'errors' => ['resident_id' => ['The resident ID must be numeric.']],
                ], 400);
            }

            // Eager load 'user', 'bed' (and nested 'bed.room'), 'guest', 'creator' relationships
            $resident = Resident::with(['user', 'bed.room', 'guest', 'creator'])->find($id);

            if (!$resident) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resident not found',
                    'data' => null,
                    'errors' => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Resident fetched successfully',
                'data' => $resident,
                'errors' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch resident',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }

    // public function getResidentProfile(Request $request)
    // {

    //     try {
    //         $resident = Helper::get_resident_details($request->header('auth-id'));
    //         //  Log::info('resident'. json_encode($resident));
    //         $resident = Resident::with(['user', 'bed.room.building', 'guest', 'creator'])
    //             ->where('id', $resident->id)
    //             ->first();

    //         if (!$resident) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Resident not found',
    //                 '' => null,
    //                 'errors' => null,
    //             ], 404);
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Resident profile fetched successfully',
    //             'data' => $resident,
    //             'errors' => null,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to fetch resident profile',
    //             'data' => null,
    //             'errors' => ['exception' => $e->getMessage()],
    //         ], 500);
    //     }
    // }

     public function getResidentProfile(Request $request)
    {
        try {
            // get resident from header
            $resident = Helper::get_resident_details($request->header('auth-id'));

            $resident = Resident::with([
                'user.roles',       // fetch roles
                'user.permissions', // fetch permissions
                'bed.room.building',
                'guest',
                'creator'
            ])
                ->where('id', $resident->id)
                ->first();

            if (!$resident) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resident not found',
                    'data'    => null,
                    'errors'  => null,
                ], 404);
            }

            // attach role & permission data from spatie
            $user = $resident->user;
            $roles = $user ? $user->getRoleNames() : [];
            $permissions = $user ? $user->getAllPermissions()->pluck('name') : [];

            return response()->json([
                'success' => true,
                'message' => 'Resident profile fetched successfully',
                'data'    => [
                    'resident'    => $resident,
                    'roles'       => $roles,
                    'permissions' => $permissions,
                ],
                'errors' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch resident profile',
                'data'    => null,
                'errors'  => ['exception' => $e->getMessage()],
            ], 500);
        }
    }


    public function assignBed(Request $request)
    {
        try {
            $validated = $request->validate([
                'resident_id' => 'required|integer|exists:residents,id',
                'bed_id' => 'required|integer|exists:beds,id',
                'date_of_joining' => 'required|date',
            ]);

            $resident = Resident::findOrFail($validated['resident_id']);
            $bed = Bed::findOrFail($validated['bed_id']);

            if (!is_null($resident->bed_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resident already has a bed assigned.',
                    'data' => ['current_bed_id' => $resident->bed_id],
                    'errors' => null,
                ], 400);
            }

            if ($bed->status === 'occupied') {
                return response()->json([
                    'success' => false,
                    'message' => 'This bed is already occupied.',
                    'data' => null,
                    'errors' => null,
                ], 400);
            }

            $resident->bed_id = $bed->id;
            $resident->check_in_date = $validated['date_of_joining'];
            $resident->status = 'active';
            $resident->save();

            $bed->status = 'occupied';
            $bed->save();

            BedAssignmentHistory::create([
                'bed_id' => $bed->id,
                'resident_id' => $resident->id,
                'assigned_at' => now(),
                'discharged_at' => null,
                'notes' => 'Bed assigned to resident',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bed assigned successfully.',
                'data' => [
                    'resident_id' => $resident->id,
                    'bed_id' => $bed->id,
                ],
                'errors' => null,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'data' => null,
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resident or bed not found.',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while assigning bed.',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }

    public function getUnassignedResidents()
    {
        // Log::info('Fetching unassigned residents');
        try {
            $residents = Resident::whereNull('bed_id')
                ->where('status', 'pending')
                ->with(['user:id,name', 'guest:id,gender,scholar_no'])
                ->get()
                ->map(function ($resident) {
                    return [
                        'id' => $resident->id,
                        'name' => optional($resident->user)->name,
                        'gender' => optional($resident->guest)->gender,
                        'scholar_number' => optional($resident->guest)->scholar_no,
                    ];
                });
            // Log::info('Unassigned residents fetched successfully', ['count' => $residents->count()]);
            return response()->json([
                'success' => true,
                'message' => 'Unassigned residents fetched successfully.',
                'data' => $residents,
                'errors' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch unassigned residents.',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }

    public function dashboard(Request $request)
    {
        // Log::info('dashboard', $request->all());
        try {
            $userId = auth()->id();
            // Log::info('userId'. json_encode($userId));
            // $resident = Resident::with([
            //     'user',
            // ])->where('user_id', $userId)->first();
            $resident = Resident::where('user_id', $userId)->first();
            // Log::info('resident' . json_encode($resident));
            // $user   = $resident->user;
            // Log::info('user' . json_encode($user));
            // $guest   = $resident->guest;
            // Log::info('guest' . json_encode($guest));

            // $course   = $resident->guest->course->name;
            // Log::info('course' . json_encode($course));

            $residentInfo = $resident->getFullInfo();
            // Log::info('residentInfo' . json_encode($residentInfo));

            // $profile = $resident->profile;
            // Log::info('profile' . json_encode($profile));

            if (!$resident) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resident record not found.',
                    'data' => null
                ]);
            }

            // $bed = $resident->bed;
            // $room = $resident->room;
            // $hostel = $resident->hostel;
            // $university = $resident->university;

            /** FLOOR CALCULATION **/
            // $floor = null;

            // if (!empty($room->floor)) {
            //     $floor = $room->floor;
            // } else {
            //     $digit = $room->room_number ? substr($room->room_number, 0, 1) : null;
            //     $floor = is_numeric($digit) ? intval($digit) : null;
            // }

            $joiningDate = optional($resident->profile)->check_in_date ?? $resident->created_at;

            $stayingDays = $joiningDate
                ? \Carbon\Carbon::parse($joiningDate)->diffInDays(now())
                : null;

            $paidTill = optional($resident->subscription)->to_date;

            $paidTillDays = $paidTill
                ? now()->diffInDays(\Carbon\Carbon::parse($paidTill), false)
                : null;

            // $totalLeaves = optional($resident->leaves->count());

            // $attendanceCount = optional($resident->attendance->count());

            // $guestVisits = optional($resident->guestVisits->count());

            // $totalInOuts = optional($resident->inOutLogs->count());


            $profile = [
                'check_in_date' =>  $joiningDate
                    ? \Carbon\Carbon::parse($joiningDate)->format('d M Y')
                    : null,
                'paid_till' =>   $paidTill
                    ? \Carbon\Carbon::parse($joiningDate)->format('d M Y')
                    : null,
                'staying_date' =>  $stayingDays ?? null,
                'total_leaves' =>  $totalLeaves ?? 1,
                'attendance' =>  $attendance ?? 23,
                'accessory' =>  $accessory ?? 3,
                'guest_visit' =>  $guestVisits ?? 2,
                'total_in_outs' =>  $totalInOuts ?? 56,
            ];

            // ✅ Y-m-d → 2025-11-14 ✅ d-m-Y → 14-11-2025 ✅ M d, Y → Nov 14, 2025 ✅ d M Y → 14 Nov 2025


            /** CURRENT ROOM INFO **/
            $current = [
                'hostel_name'    => $hostel->name ?? 'N/A',
                'room_number'    => $room->room_number ?? 'N/A',
                'floor_number'   => $floor ?? 'N/A',
                'bed_number'     => $resident->bed_number ?? 'N/A',
                'resident_name'  => $resident->user->name ?? 'N/A',
                // 'profile_image'  => $resident->user->profile_image ?? null,
                'joined_at'      => $resident->created_at->format('d M Y'),
            ];

            /** ROOM CHANGE REQUESTS **/
            // $requests = RoomChangeRequest::where('resident_id', $resident->id)
            //     ->orderBy('id', 'DESC')
            //     ->get()
            //     ->map(function ($r) {
            //         return [
            //             'id'            => $r->id,
            //             'reason'        => $r->reason,
            //             'preference'    => $r->preference,
            //             'action'        => $r->action,
            //             'remark'        => $r->remark ?? 'No remark',
            //             'resident_agree' => $r->resident_agree,
            //             'created_at'    => $r->created_at->toDateTimeString()
            //         ];
            //     });

            /** NOTICES (LATEST 5) **/
            // $notices = Notice::latest()->take(5)->get([
            //     'id',
            //     'title',
            //     'description',
            //     'created_at'
            // ]);

            /** DUES SUMMARY **/
            // $dues = Dues::where('resident_id', $resident->id)->first();
            // $duesSummary = [
            //     'total_dues'       => $dues->total_amount ?? 0,
            //     'pending_dues'     => $dues->pending_amount ?? 0,
            //     'last_paid_amount' => $dues->last_paid_amount ?? 0,
            // ];

            /** FINAL RESPONSE **/
            return response()->json([
                'success' => true,
                'message' => 'Resident dashboard loaded successfully.',
                'data' => [
                    'residentInfo'   => $residentInfo,
                    'profile'   => $profile,
                    // 'requests'  => $requests,
                    // 'notices'   => $notices,
                    // 'dues'      => $duesSummary,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function generateProfilesForAllResidents()
    {
        $residents = Resident::with(['user', 'guest'])->get();

        foreach ($residents as $resident) {
            $resident->syncProfile();
        }

        return response()->json([
            'success' => true,
            'message' => 'Profiles generated/updated successfully for all residents.'
        ]);
    }

    /**
     * Sync all existing residents into profile table.
     */
    public function syncAllProfiles()
    {
        $residents = Resident::with(['user', 'guest', 'profile'])->get();

        foreach ($residents as $resident) {

            // Prepare default source (User > Guest > Resident)
            $source = $resident->user ?? $resident->guest ?? null;

            // Build structured profile data
            $profileData = [
                'resident_id' => $resident->id,

                'full_name'   => $source->name ?? $resident->name ?? null,
                'email'       => $source->email ?? null,
                'phone'       => $source->phone ?? null,

                'dob'         => $resident->dob ?? null,
                'gender'      => $resident->gender ?? null,

                'father_name' => $resident->father_name ?? null,
                'mother_name' => $resident->mother_name ?? null,
                'address'     => $resident->address ?? null,

                'aadhaar'     => $resident->aadhaar ?? null,
                'category'    => $resident->category ?? null,

                'other_details' => json_encode([
                    'created_from' => $source?->getTable() === 'guest_users' ? 'guest' : 'user',
                    'course'       => $resident->course ?? null,
                    'admission_no' => $resident->admission_no ?? null,
                    'enrollment_no' => $resident->enrollment_no ?? null,
                ]),
            ];

            // Create/update profile record
            $resident->profile()->updateOrCreate(
                ['resident_id' => $resident->id],
                $profileData
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Profiles synced successfully for all residents.'
        ]);
    }
}
