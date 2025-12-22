<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Helpers\Helper;
use App\Models\Feedback;
use PHPUnit\TextUI\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FeedbackController extends Controller
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

    public function indexs(Request $request)
    {
        try {
            $user = $request->user(); // Sanctum user

            if (!$user || !$user->resident) {
                return $this->apiResponse(false, 'Resident not found.', [], 404);
            }

            $resident = $user->resident;

            $feedbacks = Feedback::where('resident_id', $resident->id)
                ->latest()
                ->get();

            // ✅ Collect all feedback items
            $resFeedbacks = [];

            foreach ($feedbacks as $feedback) {
                $resFeedbacks[] = [
                    'feedback_uid'   => $feedback->feedback_uid,
                    'res_name'       => $resident->name,
                    'facility_name'  => $feedback->facility_name,
                    'feedback_type'  => $feedback->feedback_type,
                    'feedback'       => $feedback->feedback,
                    'suggestion'     => $feedback->suggestion,
                    'attachment'     => $feedback->attachment,
                    'feedback_date'  => $feedback->created_at->format('d M y, H:i A'),
                ];
            }

            // ✅ Summary counts
            $totalfeedbacks     = $feedbacks->count();
            $positivefeedbacks  = $feedbacks->where('feedback_type', 'suggestions')->count();
            $negativefeedbacks  = $feedbacks->where('feedback_type', 'appreciation')->count();
            $neutralfeedbacks   = $feedbacks->where('feedback_type', 'complaint')->count();

            $summary = [
                'totalfeedbacks'     => $totalfeedbacks,
                'positivefeedbacks'  => $positivefeedbacks,
                'negativefeedbacks'  => $negativefeedbacks,
                'neutralfeedbacks'   => $neutralfeedbacks,
            ];

            return $this->apiResponse(
                true,
                'Feedback retrieved successfully.',
                [
                    'summary' => $summary,
                    'items'   => $resFeedbacks
                ],
                200
            );
        } catch (Throwable $e) {

            Log::error('Feedback Fetch Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString()
            ]);

            return $this->apiResponse(
                false,
                'Failed to retrieve feedback.',
                [],
                500,
                ['error' => $e->getMessage()]
            );
        }
    }




    // Store Feedback (Resident ID from URL)
    public function store(Request $request)
    {
        // Log::info('requests' . json_encode($request->all()));
        try {
            // $resident_id = Helper::get_resident_details($request->header('auth-id'))->id;
            $user = $request->user();
            // Log::info("Fetching user" . json_encode($user));

            // $resident = Resident::where('user_id', $user->id)->firstOrFail();
            // can do also

            $resident = $user->resident; // automatically fetches by user_id

            $request->validate([
                'facility_name' => 'required|string',
                'feedback_type' => 'nullable|string',
                'feedback' => 'required|string',
                'suggestion' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('feedback_photos', 'public');
            }

            $feedback = Feedback::create([
                'resident_id' => $resident->id,
                'facility_name' => $request->facility_name,
                'feedback_type' => $request->feedback_type,
                'feedback' => $request->feedback,
                'suggestion' => $request->suggestion,
                'photo_path' => $photoPath
            ]);

            return $this->apiResponse(true, 'Feedback submitted successfully.', $feedback, 201);
        } catch (ValidationException $e) {
            return $this->apiResponse(false, 'Validation error.', null, 422, $e->errors());
        } catch (Exception $e) {
            return $this->apiResponse(false, 'Failed to submit feedback.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    // Fetch all feedbacks with resident & user details
    public function feedbacksForAdmin(Request $request)
    {
        try {
            $user = Helper::get_auth_admin_user($request); // get current logged in user
            $userUniversityId = $user->university_id;

            $feedbacks = Feedback::with(['resident.user'])
                ->whereHas('resident.guest.faculty.university', function ($query) use ($userUniversityId) {
                    $query->where('id', $userUniversityId);
                })
                ->get();

            $feedbacks->transform(function ($feedback) {
                if ($feedback->photo_path) {
                    $feedback->photo_url = asset('storage/' . $feedback->photo_path);
                }
                return $feedback;
            });

            return $this->apiResponse(true, 'Feedbacks retrieved successfully.', $feedbacks);
        } catch (Exception $e) {
            return $this->apiResponse(false, 'Failed to fetch feedbacks.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    // Fetch all feedbacks with resident & user details
    public function index()
    {
        try {
            $feedbacks = Feedback::with(['resident.user'])->get();

            $feedbacks->transform(function ($feedback) {
                if ($feedback->photo_path) {
                    $feedback->photo_url = asset('storage/' . $feedback->photo_path);
                }
                return $feedback;
            });

            return $this->apiResponse(true, 'Feedbacks retrieved successfully.', $feedbacks);
        } catch (Exception $e) {
            return $this->apiResponse(false, 'Failed to fetch feedbacks.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    // Get feedback by ID with resident & user details
    public function show($id)
    {
        try {
            $feedback = Feedback::with(['resident.user'])->findOrFail($id);

            if ($feedback->photo_path) {
                $feedback->photo_url = asset('storage/' . $feedback->photo_path);
            }

            return $this->apiResponse(true, 'Feedback retrieved successfully.', $feedback);
        } catch (ModelNotFoundException $e) {
            return $this->apiResponse(false, 'Feedback not found.', null, 404);
        } catch (Exception $e) {
            return $this->apiResponse(false, 'Failed to fetch feedback.', null, 500, ['error' => $e->getMessage()]);
        }
    }
}
