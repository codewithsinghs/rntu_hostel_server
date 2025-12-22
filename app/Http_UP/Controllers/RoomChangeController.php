<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;
use App\Models\Bed;
use App\Models\RoomChangeRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Models\RoomChangeMessage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Helpers\Helper;
use App\Models\BedAssignmentHistory;

class RoomChangeController extends Controller
{
    public function requestRoomChange(Request $request)
    {
        try {
            $validated = $request->validate([
                'reason' => 'required|string',
                'preference' => 'nullable|string',
            ]);
            // $validated['created_by'] = $request->header('auth-id'); // Admin ID from header
            // $resident= Helper::get_resident_details($request->header('auth-id'));

            $user = $request->user();
            Log::info("Fetching user" . json_encode($user));
            try {
                // $resident = Helper::get_resident_details($request->header('auth-id'));

                // $resident = Resident::where('user_id', $user->id)->firstOrFail();
                // can do also

                $resident = $user->resident; // automatically fetches by user_id

            } catch (ModelNotFoundException $e) {
                return $this->apiResponse(false, 'Resident not found.', null, 404);
            }

            $requestData = RoomChangeRequest::create([
                'resident_id' => $resident->id,
                'reason' => $validated['reason'],
                'preference' => $validated['preference'] ?? null,
                'action' => 'pending',
                'created_by' => $user->id,
                'token' => Str::random(30),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Room change request submitted successfully.',
                'data' => $requestData,
                'redirect_url' => url('/resident/room_change_status'), // example route
                'errors' => null
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'data' => null,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting the room change request.',
                'data' => null,
                'errors' => ['details' => $e->getMessage()],
            ], 500);
        }
    }

    public function getAllRoomChangeRequests(Request $request)
    {
        Log::info("Headers", $request->headers->all());

        try {
            $university_id = Helper::get_auth_admin_user($request)->university_id;
            $requests = RoomChangeRequest::with([
                'resident.user:id,name',
                'resident.room'
            ])
                ->whereHas('resident.guest.faculty.university', function ($query) use ($university_id) {
                    $query->where('id', $university_id);
                })
                ->get();

            $data = $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'token' => $request->token,
                    'resident_id' => $request->resident_id,
                    'resident_name' => $request->resident->user->name ?? null,
                    'room_number' => $request->resident->room->room_number ?? null,
                    'reason' => $request->reason,
                    'preference' => $request->preference,
                    'action' => $request->action,
                    // Add the 'resident_agree' field from the RoomChangeRequest
                    'resident_agree' => $request->resident_agree,
                    'created_by' => $request->created_by,
                    'created_at' => $request->created_at->toDateTimeString(),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Room change requests retrieved successfully.',
                'data' => $data,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch room change requests.',
                'data' => null,
                'errors' => ['details' => $e->getMessage()]
            ], 500);
        }
    }

    public function respondToRequest(Request $request, $request_id)
    {
        try {
            $validated = $request->validate([
                'action' => 'required|in:available,not_available',
                'remark' => 'nullable|string',
            ]);

            $roomRequest = RoomChangeRequest::findOrFail($request_id);
            $roomRequest->update([
                'remark' => $validated['remark'] ?? null,
            ]);

            RoomChangeMessage::create([
                'room_change_request_id' => $request_id,
                'sender' => 'admin',
                'message' => $validated['remark'] ?? 'No remark',
                'created_by' => $request->header('auth-id'), // Admin ID from header
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Response updated successfully.',
                'data' => $roomRequest,
                'errors' => null
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room change request not found.',
                'data' => null,
                'errors' => null
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'data' => null,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update the room change request.',
                'data' => null,
                'errors' => ['details' => $e->getMessage()],
            ], 500);
        }
    }

    public function respondToAdmin(Request $request, $request_id)
    {
        try {
            $validated = $request->validate([
                'resident_agree' => 'required|boolean',
                'message' => 'nullable|string',
                'created_by' => 'required|integer',
            ]);

            $roomRequest = RoomChangeRequest::findOrFail($request_id);

            RoomChangeMessage::create([
                'room_change_request_id' => $request_id,
                'sender' => 'resident',
                'message' => $validated['message'] ?? 'No message',
                'created_by' => $validated['created_by'],
            ]);

            if ($validated['resident_agree']) {
                $roomRequest->update(['resident_agree' => true]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Resident response updated successfully.',
                'data' => $roomRequest,
                'errors' => null
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room change request not found.',
                'data' => null,
                'errors' => null
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'data' => null,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update the resident response.',
                'data' => null,
                'errors' => ['details' => $e->getMessage()],
            ], 500);
        }
    }

    public function denyRoomChangeByAdmin(Request $request, $request_id)
    {
        try {
            $validated = $request->validate([
                'remark' => 'required|string',
            ]);

            $roomRequest = RoomChangeRequest::findOrFail($request_id);

            $roomRequest->update([
                'action' => 'not_available',
                'remark' => $validated['remark'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Room change request marked as not available by admin.',
                'data' => $roomRequest,
                'errors' => null
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room change request not found.',
                'data' => null,
                'errors' => null
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'data' => null,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update the room change request.',
                'data' => null,
                'errors' => ['details' => $e->getMessage()],
            ], 500);
        }
    }

    public function confirmRoomChange(Request $request, $request_id)
    {
        try {
            $roomRequest = RoomChangeRequest::findOrFail($request_id);

            $roomRequest->update([
                'resident_agree' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Room change confirmed by resident successfully.',
                'data' => $roomRequest,
                'errors' => null
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room change request not found.',
                'data' => null,
                'errors' => null
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm room change.',
                'data' => null,
                'errors' => ['details' => $e->getMessage()],
            ], 500);
        }
    }

    public function finalApproval(Request $request, $request_id)
    {
        try {
            $roomRequest = RoomChangeRequest::findOrFail($request_id);

            if ($roomRequest->resident_agree !== 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resident has not agreed yet.',
                    'data' => null,
                    'errors' => null
                ], 400);
            }

            $resident = $roomRequest->resident;

            if (!$resident) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resident not found.',
                    'data' => null,
                    'errors' => null
                ], 404);
            }

            $roomRequest->update(['action' => 'completed']);

            $bedAssignmentHistory = BedAssignmentHistory::where('resident_id', $resident->id)
                ->whereNull('discharged_at')
                ->latest()
                ->first();

            if ($resident->bed_id) {
                // Free the old bed
                $bed = Bed::find($resident->bed_id);
                if ($bed) {
                    $bed->update(['status' => 'available']);
                    // Update resident's bed to new bed
                    $resident->update(['bed_id' => $request->new_bed_id]);
                    // Close previous bed assignment history
                    if ($bedAssignmentHistory) {
                        if ($bedAssignmentHistory) {
                            $bedAssignmentHistory->update([
                                'discharged_at' => now(),
                                'notes' => 'Bed vacated due to room change',
                            ]);
                        }
                        // Create new bed assignment history
                        BedAssignmentHistory::create([
                            'bed_id' => $request->new_bed_id,
                            'resident_id' => $resident->id,
                            'assigned_at' => now(),
                            'discharged_at' => null,
                            'notes' => 'Bed assigned due to room change',
                        ]);
                    }
                }
                $newBed = Bed::find($request->new_bed_id);
                if ($newBed) {
                    $newBed->update(['status' => 'occupied']);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Room change process completed successfully.',
                'data' => $resident,
                'errors' => null
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room change request or resident not found.',
                'data' => null,
                'errors' => null
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete room change process.',
                'data' => null,
                'errors' => ['details' => $e->getMessage()],
            ], 500);
        }
    }

    public function getRoomChangeRequestsByResidentId($residentId)
    {
        try {
            $requests = RoomChangeRequest::with([
                'resident.user:id,name',
                'resident.room'
            ])->where('resident_id', $residentId)->get();

            $data = $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'token' => $request->token,
                    'resident_name' => $request->resident->user->name ?? null,
                    'room_number' => $request->resident->room->room_number ?? null,
                    'reason' => $request->reason,
                    'preference' => $request->preference,
                    'action' => $request->action,
                    'created_by' => $request->created_by,
                    'created_at' => $request->created_at->toDateTimeString(),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Room change requests retrieved successfully.',
                'data' => $data,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch room change requests for this resident.',
                'data' => null,
                'errors' => ['details' => $e->getMessage()]
            ], 500);
        }
    }

    // public function getRoomChangeRequests(Request $request)
    // {
    //     Log::info('request', $request->all());
    //     // Log::info('user_id'. $request->header('auth-id'));
    //     $user = $request->user();
    //     Log::info("Fetching user" . json_encode($user));
    //     try {
    //         // $residentId = Resident::where('user_id', $request->header('auth-id'))->value('id');
    //         $resident = Resident::where('user_id', $request->header('auth-id'));
    //         Log::info('resident' . json_encode($resident));
    //         $requests = RoomChangeRequest::with([
    //             'resident.user:id,name',
    //             'resident.room'
    //         ])->where('resident_id', $resident->id)->get();

    //         $data = $requests->map(function ($request) {
    //             return [
    //                 'id' => $request->id,
    //                 'token' => $request->token,
    //                 'resident_name' => $request->resident->user->name ?? null,
    //                 'room_number' => $request->resident->room->room_number ?? null,
    //                 'reason' => $request->reason,
    //                 'preference' => $request->preference,
    //                 'action' => $request->action,
    //                 'created_by' => $request->created_by,
    //                 'created_at' => $request->created_at->toDateTimeString(),
    //             ];
    //         });

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Room change requests retrieved successfully.',
    //             'data' => $data,
    //             'errors' => null
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to fetch room change requests for this resident.',
    //             'data' => null,
    //             'errors' => ['details' => $e->getMessage()]
    //         ], 500);
    //     }
    // }

    public function getRoomChangeRequests(Request $request)
    {
        // Log::info('request', $request->all());

        // Try default guard first
        $user = $request->user();

        // Fallback to Sanctum guard if null
        if (!$user) {
            $user = auth('sanctum')->user();
        }

        // Log::info("Fetching user: " . json_encode($user));

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated user.',
                'data' => null
            ], 401);
        }

        try {
            // $resident = Resident::where('user_id', $user->id)->first();
            $resident = $user->resident;
            // Log::info("resident user: " . json_encode($resident));
            if (!$resident) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resident not found.',
                    'data' => null
                ], 404);
            }

            // $bed = $user->resident?->bed;
            // Log::info("resident bed: " . json_encode($bed));
            // $room = $resident->bed?->room;
            // Log::info("resident room: " . json_encode($room));
            // $building = $resident->bed?->room?->building;
            // Log::info("resident building: " . json_encode($building));

            $current = $resident->getHostelInfo();
            // Log::info("resident location: " . json_encode($location));

            $requests = RoomChangeRequest::with([
                'resident.user:id,name',
                // 'resident.room'
                'resident.bed.room',
                'creator:id,name'   // ✅ load creator details
            ])->where('resident_id', $resident->id)->get();

            $requestList = $requests->map(function ($request) {
                $building = $request->resident->bed->room->building->name ?? '';
                $room     = $request->resident->bed->room->room_number ?? '';

                // ✅ Check if created by the resident themselves
                $creator = $request->creator; // User model
                $residentUserId = $request->resident->user_id;

                // ✅ Check if created by the resident themselves
                if ($request->created_by == $residentUserId) {
                    $createdByString = "Self";
                } else {
                    $creatorName = $creator?->name;
                    $role = $creator?->getRoleNames()->first();
                    $createdByString = $creatorName && $role ? "$creatorName ($role)" : ($creatorName ?? null);
                }


                return [
                    'id' => $request->id,
                    'resident_name' => $request->resident->user->name ?? null,
                    // 'room_number' => $request->resident->bed->room->room_number ?? null,
                    // ✅ Combined building + room number
                    'room_details' => trim("$building - $room", " -"),
                    'reason' => $request->reason,
                    'preference' => $request->preference,
                    'resident_agree' => $request->resident_agree,
                    'status' => $request->action,
                    // 'created_by' => $request->created_by,
                    // ✅ Creator details
                    // ✅ Creator details with role as string
                    // 'created_by' => [
                    //     'name' => $creator?->name,
                    //     'role' => $creatorRole,
                    // ],
                    // ✅ Single string, not array
                    'created_by' => $createdByString,
                    // 'created_at' => $request->created_at->toDateTimeString(),
                    'created_at' => $request->created_at->format('d M Y, h:i A'),

                ];
            });

            // Merge current + request list inside "data"
            $data = [
                'current' => $current,
                'requests' => $requestList
            ];

            return response()->json([
                'success' => true,
                'message' => 'Room change requests retrieved successfully.',
                'data' => $data,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch room change requests.',
                'data' => null,
                'errors' => ['details' => $e->getMessage()]
            ], 500);
        }
    }


    public function getRoomChangeRequestsById(Request $request, $id)
    {
        try {
            $residentId = Resident::where('user_id', $request->header('auth-id'))->value('id');
            $requests = RoomChangeRequest::with([
                'resident.user:id,name',
                'resident.room'
            ])->where('resident_id', $residentId)->where('id', $id)->get();

            $data = $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'token' => $request->token,
                    'resident_name' => $request->resident->user->name ?? null,
                    'room_number' => $request->resident->room->room_number ?? null,
                    'reason' => $request->reason,
                    'preference' => $request->preference,
                    'action' => $request->action,
                    'created_by' => $request->created_by,
                    'created_at' => $request->created_at->toDateTimeString(),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Room change requests retrieved successfully.',
                'data' => $data,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch room change requests for this resident.',
                'data' => null,
                'errors' => ['details' => $e->getMessage()]
            ], 500);
        }
    }
}
