<?php

namespace App\Http\Controllers\ApiV1\Resident;

use Carbon\Carbon;
use App\Models\Leave;
use App\Models\Resident;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LeaveController extends Controller
{
    use ApiResponses;
    /**
     * Display a listing of leave requests for the resident.
     * Supports both API (JSON) and web (View) responses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    // public function index(Request $request)
    // {
    //     Log::info('leaves  fetch initiated  ');
    //     try {
    //         /** ---------------------------------------------
    //          * 1️⃣ Authentication & Authorization
    //          * --------------------------------------------*/
    //         $user = $this->getAuthenticatedUser($request);
    //         if (!$user) {
    //             return $this->handleUnauthenticated($request);
    //         }

    //         $resident = $this->getResident($user);
    //         if (!$resident) {
    //             return $this->handleResidentNotFound($request);
    //         }

    //         /** ---------------------------------------------
    //          * 2️⃣ Determine Response Type
    //          * --------------------------------------------*/
    //         $isDataTable = $request->has('draw'); // DataTable AJAX request
    //         $wantsJson = $request->expectsJson() || $request->is('api/*');

    //         /** ---------------------------------------------
    //          * 3️⃣ Base Query Builder
    //          * --------------------------------------------*/
    //         $query = Leave::with(['resident:id,name,email'])
    //             ->where('resident_id', $resident->id);

    //         Log::info('leaves  query  ');
    //         /** ---------------------------------------------
    //          * 4️⃣ Apply Filters
    //          * --------------------------------------------*/
    //         $query = $this->applyFilters($query, $request);

    //         Log::info('leaves  query  ' . json_encode($query->toSql()));
    //         /** ---------------------------------------------
    //          * 5️⃣ DataTable Specific Processing
    //          * --------------------------------------------*/
    //         if ($isDataTable) {

    //             // TEMPORARY: Add logging to see what's happening
    //             Log::info('DataTable request received', [
    //                 'draw' => $request->get('draw'),
    //                 'order' => $request->get('order'),
    //                 'has_order' => $request->has('order'),
    //                 'search' => $request->get('search')
    //             ]);
    //             return $this->handleDataTableRequest($query, $request);
    //         }

    //         /** ---------------------------------------------
    //          * 6️⃣ Get Records with Serial Number
    //          * --------------------------------------------*/
    //         // $query->latest();
    //         // First priority: Most recent applied date
    //         // Second priority: Pending status within same date
    //         // $query->orderBy('created_at', 'desc')
    //         //     ->orderByRaw("  CASE 
    //         //         WHEN status = 'pending' THEN 1
    //         //             WHEN status = 'approved' THEN 2
    //         //             WHEN status = 'rejected' THEN 3
    //         //             WHEN status = 'cancelled' THEN 4
    //         //             ELSE 5
    //         //         END
    //         //     ")
    //         //     ->orderBy('id', 'desc'); // Third priority: Latest ID for same date and status
    //         // $query->orderBy('created_at', 'desc')
    //         //     ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected', 'cancelled')")
    //         //     ->orderBy('id', 'desc');
    //         // $query->orderBy('id', 'desc')
    //         //     ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
    //         //     ->orderBy('id', 'desc');
    //         $query->orderBy('created_at', 'desc')
    //             ->orderByRaw("status = 'pending' DESC")
    //             ->orderByRaw("status = 'approved' DESC")
    //             ->orderByRaw("status = 'rejected' DESC")
    //             ->orderByRaw("status = 'cancelled' DESC")
    //             ->orderBy('id', 'desc');

    //         // Before getting records, log the SQL
    //         Log::info('Generated SQL Query:', [
    //             'sql' => $query->toSql(),
    //             'bindings' => $query->getBindings()
    //         ]);

    //         $records = $query->get();

    //         // Add serial numbers
    //         $records->each(function ($item, $index) {
    //             $item->serial_no = $index + 1;
    //         });

    //         /** ---------------------------------------------
    //          * 7️⃣ Get Summary Statistics
    //          * --------------------------------------------*/
    //         $summary = $this->getSummary($resident->id);

    //         /** ---------------------------------------------
    //          * 8️⃣ Prepare Response
    //          * --------------------------------------------*/
    //         $transformedRecords = $this->transformRecords($records, true); // with serial

    //         if ($wantsJson) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Leave requests retrieved successfully.',
    //                 'data' => [
    //                     'requests' => $transformedRecords,
    //                     'summary' => [
    //                         'total_leaves' => (int) ($summary->total ?? 0),
    //                         'pending'      => (int) ($summary->pending ?? 0),
    //                         'approved'     => (int) ($summary->approved ?? 0),
    //                         'rejected'     => (int) ($summary->rejected ?? 0),
    //                         'cancelled'    => (int) ($summary->cancelled ?? 0)
    //                     ]
    //                 ]
    //             ]);
    //         }

    //         // Web View Response
    //         return view('resident.leaves.index', [
    //             'leaves'  => $transformedRecords,
    //             'summary' => $summary,
    //             'total'   => $records->count()
    //         ]);
    //     } catch (\Throwable $e) {
    //         Log::error('[LEAVE][RESIDENT][INDEX]', [
    //             'message' => $e->getMessage(),
    //             'line'    => $e->getLine(),
    //             'file'    => $e->getFile(),
    //             'trace'   => $e->getTraceAsString()
    //         ]);

    //         return $this->handleError($request, $e, 'Failed to retrieve leave requests.');
    //     }
    // }

    public function index(Request $request)
    {
        // Log::info('leaves fetch initiated');
        try {
            /** ---------------------------------------------
             * 1️⃣ Authentication & Authorization
             * --------------------------------------------*/
            $user = $this->getAuthenticatedUser($request);
            if (!$user) {
                return $this->handleUnauthenticated($request);
            }

            $resident = $this->getResident($user);
            if (!$resident) {
                return $this->handleResidentNotFound($request);
            }

            /** ---------------------------------------------
             * 2️⃣ Determine Response Type
             * --------------------------------------------*/
            $isDataTable = $request->has('draw'); // DataTable AJAX request
            $wantsJson = $request->expectsJson() || $request->is('api/*');

            /** ---------------------------------------------
             * 3️⃣ Get Summary Statistics (DO THIS FIRST FOR BOTH CASES)
             * --------------------------------------------*/
            $summary = $this->getSummary($resident->id);

            /** ---------------------------------------------
             * 4️⃣ Base Query Builder
             * --------------------------------------------*/
            $query = Leave::with(['resident:id,name,email'])
                ->where('resident_id', $resident->id);

            /** ---------------------------------------------
             * 5️⃣ Apply Filters
             * --------------------------------------------*/
            $query = $this->applyFilters($query, $request);

            /** ---------------------------------------------
             * 6️⃣ DataTable Specific Processing
             * --------------------------------------------*/
            if ($isDataTable) {
                $totalRecords = $query->count();

                // Apply search
                $searchValue = $request->get('search')['value'] ?? null;
                if ($searchValue) {
                    $query = $this->applySearch($query, $searchValue);
                }

                $filteredRecords = $query->count();

                // Apply sorting
                $orderColumn = $request->get('order')[0]['column'] ?? 0;
                $orderDirection = $request->get('order')[0]['dir'] ?? 'desc';
                // $query = $this->applySorting($query, $orderColumn, $orderDirection, true);

                // using columnName
                $query = $this->applySorting($query, $orderColumn, $orderDirection, true, $request);

                // Apply pagination
                $start = $request->get('start', 0);
                $length = $request->get('length', 10);
                $query->skip($start)->take($length);

                $records = $query->get();

                // Add serial numbers for DataTable
                $records->each(function ($item, $index) use ($start) {
                    $item->DT_RowIndex = $start + $index + 1;
                });

                $transformedRecords = $this->transformRecords($records, false);

                // ⭐⭐⭐ RETURN SUMMARY WITH DATATABLE RESPONSE ⭐⭐⭐
                return response()->json([
                    'draw' => intval($request->get('draw')),
                    'recordsTotal' => $totalRecords,
                    'recordsFiltered' => $filteredRecords,
                    'data' => $transformedRecords,
                    'summary' => [  // Add summary here
                        'total_leaves' => (int) ($summary->total ?? 0),
                        'pending'      => (int) ($summary->pending ?? 0),
                        'approved'     => (int) ($summary->approved ?? 0),
                        'rejected'     => (int) ($summary->rejected ?? 0),
                        'cancelled'    => (int) ($summary->cancelled ?? 0)
                    ]
                ]);
            }

            /** ---------------------------------------------
             * 7️⃣ Get Records with Serial Number (NON-DATATABLE)
             * --------------------------------------------*/
            $query->orderBy('created_at', 'desc')
                ->orderByRaw("status = 'pending' DESC")
                ->orderByRaw("status = 'approved' DESC")
                ->orderByRaw("status = 'rejected' DESC")
                ->orderByRaw("status = 'cancelled' DESC")
                ->orderBy('id', 'desc');

            $records = $query->get();

            // Add serial numbers
            $records->each(function ($item, $index) {
                $item->serial_no = $index + 1;
            });

            /** ---------------------------------------------
             * 8️⃣ Prepare Response
             * --------------------------------------------*/
            $transformedRecords = $this->transformRecords($records, true); // with serial

            if ($wantsJson) {
                return response()->json([
                    'success' => true,
                    'message' => 'Leave requests retrieved successfully.',
                    'data' => [
                        'requests' => $transformedRecords,
                        'summary' => [
                            'total_leaves' => (int) ($summary->total ?? 0),
                            'pending'      => (int) ($summary->pending ?? 0),
                            'approved'     => (int) ($summary->approved ?? 0),
                            'rejected'     => (int) ($summary->rejected ?? 0),
                            'cancelled'    => (int) ($summary->cancelled ?? 0)
                        ]
                    ]
                ]);
            }

            // Web View Response
            return view('resident.leaves.index', [
                'leaves'  => $transformedRecords,
                'summary' => $summary,
                'total'   => $records->count()
            ]);
        } catch (\Throwable $e) {
            Log::error('[LEAVE][RESIDENT][INDEX]', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
                'trace'   => $e->getTraceAsString()
            ]);

            return $this->handleError($request, $e, 'Failed to retrieve leave requests.');
        }
    }

    /**
     * Get summary statistics for leaves
     */
    protected function getSummary($residentId)
    {
        return Leave::where('resident_id', $residentId)
            ->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected,
            SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled
        ')
            ->first();
    }

    /**
     * Show the form for creating a new leave request.
     */
    public function create()
    {
        try {
            $user = Auth::user();
            $resident = Resident::where('user_id', $user->id)->first();

            if (!$resident) {
                return back()->with('error', 'Resident profile not found.');
            }

            return view('resident.leaves.create', [
                'leaveTypes' => $this->getLeaveTypes(),
                'reasons'    => $this->getReasonOptions()
            ]);
        } catch (\Throwable $e) {
            Log::error('[LEAVE][RESIDENT][CREATE]', [
                'message' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to load leave application form.');
        }
    }

    /**
     * Store a newly created leave request in storage.
     */
    public function store(Request $request)
    {
        Log::info('store request ', $request->all());
        try {
            $user = $this->getAuthenticatedUser($request);
            if (!$user) {
                return $this->handleUnauthenticated($request);
            }

            $resident = $this->getResident($user);
            if (!$resident) {
                return $this->handleResidentNotFound($request);
            }

            /** ---------------------------------------------
             * 1️⃣ Validation
             * --------------------------------------------*/
            $validator = Validator::make($request->all(), [
                'type'      => ['required', 'string', Rule::in(array_keys($this->getLeaveTypes()))],
                'reason'    => 'required|string|max:500',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date'   => 'required|date|after_or_equal:start_date',
                'description' => 'nullable|string|max:500',
                'attachment' => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx',
                // 'emergency_contact' => 'required_if:type,emergency|nullable|string|max:20',
                // 'emergency_contact' => 'required|string|max:20|regex:/^[+0-9\s\-\(\)]{10,20}$/',
                'declaration' => 'required|accepted'
            ], [
                'start_date.after_or_equal' => 'Start date cannot be in the past.',
                'end_date.after_or_equal' => 'End date must be after start date.',
                // 'emergency_contact.required_if' => 'Emergency contact is required for emergency leaves.',
                'declaration.accepted' => 'You must accept the declaration.'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed.',
                        'errors'  => $validator->errors()
                    ], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            // $rules = [
            //     'type' => 'required|in:medical,personal,official,emergency,semester_break,festival,academic,sports',
            //     'reason' => 'required|string|max:255',
            //     'description' => 'required|string|max:500',
            //     'start_date' => 'required|date',
            //     'end_date' => 'required|date|after_or_equal:start_date',
            //     'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            //     'declaration' => 'required|accepted',
            // ];
            // Add emergency contact validation only for emergency type
            // if ($request->type === 'emergency') {
            //     $rules['emergency_contact'] = 'required|string|max:20|regex:/^[+0-9\s\-\(\)]{10,20}$/';
            // }

            /** ---------------------------------------------
             * 2️⃣ Handle File Upload
             * --------------------------------------------*/
            // Handle attachment safely
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');

                // Check if file is valid and not empty
                if ($file->isValid() && $file->getSize() > 0) {
                    try {
                        $attachmentPath = $file->store('leaveapps', 'public');
                    } catch (\Exception $e) {
                        \Log::error('File upload failed: ' . $e->getMessage());
                        // Continue without attachment
                    }
                }
            }


            /** ---------------------------------------------
             * 3️⃣ Calculate Duration
             * --------------------------------------------*/
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $duration = $startDate->diffInDays($endDate) + 1;

            /** ---------------------------------------------
             * 4️⃣ Create Leave Request
             * --------------------------------------------*/
            $leave = Leave::create([
                'resident_id'    => $resident->id,
                'type'          => $request->type,
                'reason'        => $request->reason,
                'description'   => $request->description,
                'start_date'    => $startDate,
                'end_date'      => $endDate,
                'duration'      => $duration,
                'attachment'    => $attachmentPath,
                // 'emergency_contact' => $request->emergency_contact,
                'status'        => 'pending',
                'hod_status'    => 'pending',
                'admin_status'  => 'pending',
                'token'         => $this->generateUniqueToken(),
                'created_at'    => now()
            ]);

            /** ---------------------------------------------
             * 5️⃣ Generate QR Code
             * --------------------------------------------*/
            // $this->generateAndSaveQrCode($leave);

            /** ---------------------------------------------
             * 6️⃣ Prepare Response
             * --------------------------------------------*/
            $responseData = [
                'id' => $leave->id,
                'type' => $leave->type,
                'reason' => $leave->reason,
                'start_date' => $leave->start_date->format('Y-m-d'),
                'end_date' => $leave->end_date->format('Y-m-d'),
                'duration' => $leave->duration,
                'status' => $leave->status,
                'token' => $leave->token,
                // 'qr_code' => base64_encode($this->generateQrCode($leave->token)),
                'created_at' => $leave->created_at->format('Y-m-d H:i:s')
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Leave request submitted successfully.',
                    'data' => $responseData
                ], 201);
            }

            return redirect()->route('resident.leaves.index')
                ->with('success', 'Leave request submitted successfully!');
        } catch (\Throwable $e) {
            Log::error('[LEAVE][RESIDENT][STORE]', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to submit leave request.',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return back()->with('error', 'Failed to submit leave request. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified leave request.
     */
    public function show(Request $request, $id)
    {
        try {
            $user = $this->getAuthenticatedUser($request);
            if (!$user) {
                return $this->handleUnauthenticated($request);
            }

            $resident = $this->getResident($user);
            if (!$resident) {
                return $this->handleResidentNotFound($request);
            }

            $leave = Leave::with('resident')
                ->where('resident_id', $resident->id)
                ->findOrFail($id);

            $transformedData = $this->transformSingleRecord($leave);

            $transformedData['hostel_name'] = optional($leave->resident->hostel)->name;
            $transformedData['room_number'] = optional($leave->resident->room)->room_number;
            $transformedData['course'] = $leave->resident->course ?? null;
            $transformedData['department'] = $leave->resident->department ?? null;
            $transformedData['hostel_in_time'] = optional($leave->hostel_in_time)->format('d M Y, h:i A');
            $transformedData['hostel_out_time'] = optional($leave->hostel_out_time)->format('d M Y, h:i A');
            $transformedData['applied_on'] = optional($leave->created_at)->format('d M Y, h:i A');
            $transformedData['action_at'] = optional($leave->action_at)->format('d M Y, h:i A');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Leave request retrieved successfully.',
                    'data' => $transformedData
                ]);
            }

            return view('resident.leaves.show', [
                'leave' => $transformedData
            ]);
        } catch (\Throwable $e) {
            Log::error('[LEAVE][RESIDENT][SHOW]', [
                'message' => $e->getMessage(),
                'leave_id' => $id
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave request not found.'
                ], 404);
            }

            return back()->with('error', 'Leave request not found.');
        }
    }

    /**
     * Show the form for editing the specified leave request.
     */
    public function edit($id)
    {
        try {
            $user = Auth::user();
            $resident = Resident::where('user_id', $user->id)->first();

            if (!$resident) {
                return back()->with('error', 'Resident profile not found.');
            }

            $leave = Leave::where('resident_id', $resident->id)
                ->where('status', 'pending')
                ->where('hod_status', 'pending')
                ->where('admin_status', 'pending')
                ->findOrFail($id);

            return view('resident.leaves.edit', [
                'leave' => $this->transformSingleRecord($leave),
                'leaveTypes' => $this->getLeaveTypes(),
                'reasons' => $this->getReasonOptions()
            ]);
        } catch (\Throwable $e) {
            Log::error('[LEAVE][RESIDENT][EDIT]', [
                'message' => $e->getMessage(),
                'leave_id' => $id
            ]);

            return back()->with('error', 'Cannot edit this leave request.');
        }
    }

    /**
     * Update the specified leave request in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = $this->getAuthenticatedUser($request);
            if (!$user) {
                return $this->handleUnauthenticated($request);
            }

            $resident = $this->getResident($user);
            if (!$resident) {
                return $this->handleResidentNotFound($request);
            }

            /** ---------------------------------------------
             * 1️⃣ Find Leave (Only pending leaves can be edited)
             * --------------------------------------------*/
            $leave = Leave::where('resident_id', $resident->id)
                ->where('status', 'pending')
                ->where('hod_status', 'pending')
                ->where('admin_status', 'pending')
                ->findOrFail($id);

            /** ---------------------------------------------
             * 2️⃣ Validation
             * --------------------------------------------*/
            $validator = Validator::make($request->all(), [
                'type'      => ['required', 'string', Rule::in(array_keys($this->getLeaveTypes()))],
                'reason'    => 'required|string|max:500',
                'start_date' => 'required|date',
                'end_date'   => 'required|date|after_or_equal:start_date',
                'description' => 'nullable|string|max:1000',
                'attachment' => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed.',
                        'errors'  => $validator->errors()
                    ], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            /** ---------------------------------------------
             * 3️⃣ Handle File Upload
             * --------------------------------------------*/
            if ($request->hasFile('attachment')) {
                // Delete old attachment if exists
                if ($leave->attachment_path) {
                    Storage::disk('public')->delete($leave->attachment_path);
                }
                $leave->attachment_path = $request->file('attachment')->store('leave-attachments', 'public');
            }

            /** ---------------------------------------------
             * 4️⃣ Calculate Duration
             * --------------------------------------------*/
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $duration = $startDate->diffInDays($endDate) + 1;

            /** ---------------------------------------------
             * 5️⃣ Update Leave Request
             * --------------------------------------------*/
            $leave->update([
                'type'        => $request->type,
                'reason'      => $request->reason,
                'description' => $request->description,
                'start_date'  => $startDate,
                'end_date'    => $endDate,
                'duration'    => $duration,
                'updated_at'  => now()
            ]);

            /** ---------------------------------------------
             * 6️⃣ Prepare Response
             * --------------------------------------------*/
            $transformedData = $this->transformSingleRecord($leave->fresh());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Leave request updated successfully.',
                    'data' => $transformedData
                ]);
            }

            return redirect()->route('resident.leaves.index')
                ->with('success', 'Leave request updated successfully!');
        } catch (\Throwable $e) {
            Log::error('[LEAVE][RESIDENT][UPDATE]', [
                'message' => $e->getMessage(),
                'leave_id' => $id
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update leave request.'
                ], 500);
            }

            return back()->with('error', 'Failed to update leave request.')
                ->withInput();
        }
    }

    /**
     * Remove the specified leave request from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = $this->getAuthenticatedUser($request);
            if (!$user) {
                return $this->handleUnauthenticated($request);
            }

            $resident = $this->getResident($user);
            if (!$resident) {
                return $this->handleResidentNotFound($request);
            }

            /** ---------------------------------------------
             * 1️⃣ Find Leave (Only pending leaves can be deleted)
             * --------------------------------------------*/
            $leave = Leave::where('resident_id', $resident->id)
                ->where('status', 'pending')
                ->where('hod_status', 'pending')
                ->where('admin_status', 'pending')
                ->findOrFail($id);

            /** ---------------------------------------------
             * 2️⃣ Delete Attachment if exists
             * --------------------------------------------*/
            if ($leave->attachment_path) {
                Storage::disk('public')->delete($leave->attachment_path);
            }

            /** ---------------------------------------------
             * 3️⃣ Delete Leave
             * --------------------------------------------*/
            $leave->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Leave request deleted successfully.',
                    'data' => ['id' => $id]
                ]);
            }

            return redirect()->route('resident.leaves.index')
                ->with('success', 'Leave request deleted successfully!');
        } catch (\Throwable $e) {
            Log::error('[LEAVE][RESIDENT][DESTROY]', [
                'message' => $e->getMessage(),
                'leave_id' => $id
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete leave request.'
                ], 500);
            }

            return back()->with('error', 'Failed to delete leave request.');
        }
    }

    /**
     * Cancel a pending leave request.
     */
    public function cancel(Request $request, $id)
    {
        Log::info('cancel leave request id: ' . $id);
        try {
            $user = $this->getAuthenticatedUser($request);
            if (!$user) {
                return $this->handleUnauthenticated($request);
            }

            $resident = $this->getResident($user);
            if (!$resident) {
                return $this->handleResidentNotFound($request);
            }

            $leave = Leave::where('resident_id', $resident->id)
                ->where('status', 'pending')
                ->findOrFail($id);

            // Check if leave hasn't started yet
            if (Carbon::parse($leave->start_date)->isPast()) {
                $message = 'Cannot cancel leave that has already started.';

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }

                return back()->with('error', $message);
            }

            $leave->update([
                'status' => 'cancelled',
                'cancelled_at' => now()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Leave request cancelled successfully.',
                    'data' => $this->transformSingleRecord($leave)
                ]);
            }

            return redirect()->route('resident.leaves.index')
                ->with('success', 'Leave request cancelled successfully!');
        } catch (\Throwable $e) {
            Log::error('[LEAVE][RESIDENT][CANCEL]', [
                'message' => $e->getMessage(),
                'leave_id' => $id
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to cancel leave request.'
                ], 500);
            }

            return back()->with('error', 'Failed to cancel leave request.');
        }
    }

    /**
     * Get gate pass for approved leave.
     */
    public function gatePass(Request $request, $id)
    {
        try {
            $user = $this->getAuthenticatedUser($request);
            if (!$user) {
                return $this->handleUnauthenticated($request);
            }

            $resident = $this->getResident($user);
            if (!$resident) {
                return $this->handleResidentNotFound($request);
            }

            $leave = Leave::where('resident_id', $resident->id)
                ->where('status', 'approved')
                ->where('hod_status', 'approved')
                ->where('admin_status', 'approved')
                ->findOrFail($id);

            $gatePassData = $this->prepareGatePassData($leave);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Gate pass retrieved successfully.',
                    'data' => $gatePassData
                ]);
            }

            return view('resident.leaves.gatepass', [
                'gatePass' => $gatePassData
            ]);
        } catch (\Throwable $e) {
            Log::error('[LEAVE][RESIDENT][GATE_PASS]', [
                'message' => $e->getMessage(),
                'leave_id' => $id
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gate pass not available for this leave.'
                ], 404);
            }

            return back()->with('error', 'Gate pass not available for this leave.');
        }
    }

    /**
     * Get leave summary statistics.
     */
    public function summary(Request $request)
    {
        try {
            $user = $this->getAuthenticatedUser($request);
            if (!$user) {
                return $this->handleUnauthenticated($request);
            }

            $resident = $this->getResident($user);
            if (!$resident) {
                return $this->handleResidentNotFound($request);
            }

            $summary = $this->getSummary($resident->id);

            return response()->json([
                'success' => true,
                'message' => 'Leave summary retrieved successfully.',
                'data' => [
                    'total_leaves' => (int) ($summary->total ?? 0),
                    'pending'      => (int) ($summary->pending ?? 0),
                    'approved'     => (int) ($summary->approved ?? 0),
                    'rejected'     => (int) ($summary->rejected ?? 0),
                    'cancelled'    => (int) ($summary->cancelled ?? 0)
                ]
            ]);
        } catch (\Throwable $e) {
            Log::error('[LEAVE][RESIDENT][SUMMARY]', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve leave summary.'
            ], 500);
        }
    }

    /** ================================================
     * HELPER METHODS
     * ================================================ */

    /**
     * Handle DataTable AJAX requests
     */
    // private function handleDataTableRequest($query, Request $request)
    // {
    //     $totalRecords = $query->count();

    //     // Apply search
    //     $searchValue = $request->get('search')['value'] ?? null;
    //     if ($searchValue) {
    //         $query = $this->applySearch($query, $searchValue);
    //     }

    //     $filteredRecords = $query->count();

    //     // Apply sorting
    //     $orderColumn = $request->get('order')[0]['column'] ?? 0;
    //     $orderDirection = $request->get('order')[0]['dir'] ?? 'desc';
    //     $query = $this->applySorting($query, $orderColumn, $orderDirection, true);

    //     // Apply pagination
    //     $start = $request->get('start', 0);
    //     $length = $request->get('length', 10);
    //     $query->skip($start)->take($length);

    //     $records = $query->get();

    //     // Add serial numbers for DataTable
    //     $records->each(function ($item, $index) use ($start) {
    //         $item->DT_RowIndex = $start + $index + 1;
    //     });

    //     $transformedRecords = $this->transformRecords($records, false); // without serial

    //     return response()->json([
    //         'draw' => intval($request->get('draw')),
    //         'recordsTotal' => $totalRecords,
    //         'recordsFiltered' => $filteredRecords,
    //         'data' => $transformedRecords
    //     ]);
    // }

    // private function handleDataTableRequest($query, Request $request)
    // {
    //     $totalRecords = $query->count();

    //     // Apply search
    //     $searchValue = $request->get('search')['value'] ?? null;
    //     if ($searchValue) {
    //         $query = $this->applySearch($query, $searchValue);
    //     }

    //     $filteredRecords = $query->count();

    //     // Check if sorting is requested by DataTable
    //     $hasOrder = $request->has('order') && count($request->get('order')) > 0;

    //     if ($hasOrder) {
    //         // Apply DataTable sorting
    //         $orderColumn = $request->get('order')[0]['column'] ?? 0;
    //         $orderDirection = $request->get('order')[0]['dir'] ?? 'desc';
    //         $query = $this->applySorting($query, $orderColumn, $orderDirection, true);
    //     } else {
    //         // Apply default sorting when no DataTable order specified
    //         $query = $this->applySorting($query, 0, 'desc', false);
    //     }

    //     // Apply pagination
    //     $start = $request->get('start', 0);
    //     $length = $request->get('length', 10);
    //     $query->skip($start)->take($length);

    //     $records = $query->get();

    //     // Add serial numbers for DataTable
    //     $records->each(function ($item, $index) use ($start) {
    //         $item->DT_RowIndex = $start + $index + 1;
    //     });

    //     $transformedRecords = $this->transformRecords($records, false);

    //     return response()->json([
    //         'draw' => intval($request->get('draw')),
    //         'recordsTotal' => $totalRecords,
    //         'recordsFiltered' => $filteredRecords,
    //         'data' => $transformedRecords
    //     ]);
    // }

    // private function handleDataTableRequest($query, Request $request)
    // {
    //     // Apply default ordering FIRST (created_at desc, then status priority)
    //     $query->orderBy('created_at', 'desc')
    //         ->orderByRaw("
    //           CASE 
    //               WHEN status = 'pending' THEN 1
    //               WHEN status = 'approved' THEN 2
    //               WHEN status = 'rejected' THEN 3
    //               WHEN status = 'cancelled' THEN 4
    //               ELSE 5
    //           END
    //       ")
    //         ->orderBy('id', 'desc');

    //     $totalRecords = $query->count();

    //     // Apply search
    //     $searchValue = $request->get('search')['value'] ?? null;
    //     if ($searchValue) {
    //         $query = $this->applySearch($query, $searchValue);
    //     }

    //     $filteredRecords = $query->count();

    //     // DataTable can still override sorting if requested
    //     if ($request->has('order') && count($request->get('order')) > 0) {
    //         $orderColumn = $request->get('order')[0]['column'] ?? 0;
    //         $orderDirection = $request->get('order')[0]['dir'] ?? 'desc';

    //         // Reorder based on DataTable request (this will override previous order)
    //         $columnMap = [
    //             0 => 'created_at',
    //             1 => 'type',
    //             2 => 'reason',
    //             3 => 'start_date',
    //             4 => 'end_date',
    //             5 => 'duration',
    //             6 => 'status',
    //         ];

    //         if (isset($columnMap[$orderColumn])) {
    //             $column = $columnMap[$orderColumn];

    //             // Remove previous ordering and apply new one
    //             $query->reorder(); // Clear previous orderBy

    //             if ($column === 'status') {
    //                 $query->orderByRaw("
    //                 CASE 
    //                     WHEN status = 'pending' THEN 1
    //                     WHEN status = 'approved' THEN 2
    //                     WHEN status = 'rejected' THEN 3
    //                     WHEN status = 'cancelled' THEN 4
    //                     ELSE 5
    //                 END $orderDirection
    //             ");
    //             } else {
    //                 $query->orderBy($column, $orderDirection);
    //             }

    //             $query->orderBy('id', $orderDirection);
    //         }
    //     }

    //     // Apply pagination
    //     $start = $request->get('start', 0);
    //     $length = $request->get('length', 10);
    //     $query->skip($start)->take($length);

    //     $query->orderBy('id', 'desc');
    //     $records = $query->get();

    //     // Add serial numbers for DataTable
    //     $records->each(function ($item, $index) use ($start) {
    //         $item->DT_RowIndex = $start + $index + 1;
    //     });

    //     $transformedRecords = $this->transformRecords($records, false);

    //     return response()->json([
    //         'draw' => intval($request->get('draw')),
    //         'recordsTotal' => $totalRecords,
    //         'recordsFiltered' => $filteredRecords,
    //         'data' => $transformedRecords
    //     ]);
    // }

    // private function handleDataTableRequest($query, Request $request)
    // {
    //     // Step 1: Get the column data name from request
    //     $orderColumnIndex = $request->get('order')[0]['column'] ?? null;
    //     $orderDirection = $request->get('order')[0]['dir'] ?? 'desc';

    //     // Step 2: Get the column configuration from frontend
    //     $columns = $request->get('columns') ?? [];

    //     // Step 3: Check if this is DataTable's default order
    //     $isDefaultOrder = ($orderColumnIndex === 0 && $orderDirection === 'asc') ||
    //         empty($columns);

    //     if ($isDefaultOrder) {
    //         // Apply YOUR default ordering
    //         $query->orderBy('created_at', 'desc')
    //             ->orderByRaw("
    //               CASE 
    //                   WHEN status = 'pending' THEN 1
    //                   WHEN status = 'approved' THEN 2
    //                   WHEN status = 'rejected' THEN 3
    //                   WHEN status = 'cancelled' THEN 4
    //                   ELSE 5
    //               END
    //           ")
    //             ->orderBy('id', 'desc');
    //     } else {
    //         // User has clicked to sort - get the column data name
    //         $columnData = $columns[$orderColumnIndex]['data'] ?? null;
    //         $columnName = $columns[$orderColumnIndex]['name'] ?? $columnData;

    //         // Log for debugging
    //         Log::info('DataTable sorting requested', [
    //             'column_index' => $orderColumnIndex,
    //             'column_data' => $columnData,
    //             'column_name' => $columnName,
    //             'direction' => $orderDirection
    //         ]);

    //         // Apply sorting based on data name
    //         if ($columnName && in_array($columnName, ['created_at', 'type', 'reason', 'start_date', 'end_date', 'duration', 'status'])) {
    //             if ($columnName === 'status') {
    //                 $query->orderByRaw("
    //                 CASE 
    //                     WHEN status = 'pending' THEN 1
    //                     WHEN status = 'approved' THEN 2
    //                     WHEN status = 'rejected' THEN 3
    //                     WHEN status = 'cancelled' THEN 4
    //                     ELSE 5
    //                 END $orderDirection
    //             ");
    //             } else {
    //                 $query->orderBy($columnName, $orderDirection);
    //             }

    //             // Add secondary ordering for consistency
    //             $query->orderBy('id', $orderDirection);
    //         }
    //     }

    //     $totalRecords = $query->count();

    //     // Apply search
    //     $searchValue = $request->get('search')['value'] ?? null;
    //     if ($searchValue && !empty(trim($searchValue))) {
    //         $query = $this->applySearch($query, $searchValue);
    //     }

    //     $filteredRecords = $query->count();

    //     // Apply pagination
    //     $start = $request->get('start', 0);
    //     $length = $request->get('length', 10);
    //     $query->skip($start)->take($length);

    //     $records = $query->get();

    //     // Add DataTable row index
    //     $records->each(function ($item, $index) use ($start) {
    //         $item->DT_RowIndex = $start + $index + 1;
    //     });

    //     $transformedRecords = $this->transformRecords($records, false);

    //     return response()->json([
    //         'draw' => intval($request->get('draw')),
    //         'recordsTotal' => $totalRecords,
    //         'recordsFiltered' => $filteredRecords,
    //         'data' => $transformedRecords
    //     ]);
    // }

    private function handleDataTableRequest($query, Request $request)
    {
        // ⭐⭐⭐ ADD THIS AT THE VERY BEGINNING ⭐⭐⭐
        // $query->orderBy('created_at', 'desc')
        //     ->orderByRaw("CASE WHEN status = 'pending' THEN 1 WHEN status = 'approved' THEN 2 WHEN status = 'rejected' THEN 3 WHEN status = 'cancelled' THEN 4 ELSE 5 END")
        //     ->orderBy('id', 'desc');
        // ⭐⭐⭐ THAT'S IT - REST OF YOUR CODE STAYS THE SAME ⭐⭐⭐

        $totalRecords = $query->count();

        // Apply search
        $searchValue = $request->get('search')['value'] ?? null;
        if ($searchValue) {
            $query = $this->applySearch($query, $searchValue);
        }

        $filteredRecords = $query->count();

        // Apply sorting - DataTable can still override if it wants
        $orderColumn = $request->get('order')[0]['column'] ?? 0;
        $orderDirection = $request->get('order')[0]['dir'] ?? 'desc';
        $query = $this->applySorting($query, $orderColumn, $orderDirection, true);

        // Apply pagination
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $query->skip($start)->take($length);

        $records = $query->get();

        // Add serial numbers for DataTable
        $records->each(function ($item, $index) use ($start) {
            $item->DT_RowIndex = $start + $index + 1;
        });

        $transformedRecords = $this->transformRecords($records, false);

        // return response()->json([
        //     'draw' => intval($request->get('draw')),
        //     'recordsTotal' => $totalRecords,
        //     'recordsFiltered' => $filteredRecords,
        //     'data' => $transformedRecords
        // ]);

        $dataTable = [
            'draw' => intval($request->get('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $transformedRecords
        ];

        return $dataTable;
    }
    /**
     * Transform records for response (with or without serial number)
     */
    private function transformRecords($records, $withSerial = false, $withQr = false)
    {
        return $records->map(function ($leave, $index) use ($withSerial, $withQr) {
            $data = [
                // Core Data
                'id' => $leave->id,
                'type' => $leave->type,
                'type_formatted' => ucfirst(str_replace('_', ' ', $leave->type)),
                'reason' => $leave->reason,
                'description' => $leave->description,
                'attachment' => $leave->attachment,
                'start_date' => optional($leave->start_date)->format('Y-m-d'),
                'start_date_formatted' => optional($leave->start_date)->format('d M Y'),
                'end_date' => optional($leave->end_date)->format('Y-m-d'),
                'end_date_formatted' => optional($leave->end_date)->format('d M Y'),
                'duration' => $leave->duration,
                'created_at' => optional($leave->created_at)->format('Y-m-d H:i:s'),
                'created_at_formatted' => optional($leave->created_at)
                    // ->timezone('Asia/Kolkata')
                    ->format('d M Y, h:i A'),

                // Status Information
                'status' => $leave->status,
                'status_html' => $this->getStatusHtml($leave->status),
                'hod_status' => $leave->hod_status ?? 'pending',
                'hod_remarks' => $leave->hod_remarks,
                'hod_action_at_formatted' => optional($leave->hod_action_at)
                    // ->timezone('Asia/Kolkata')
                    ->format('d M Y, h:i A'),
                'admin_status' => $leave->admin_status,
                'admin_remarks' => $leave->admin_remarks,
                'admin_action_at' => optional($leave->admin_action_at)
                    // ->timezone('Asia/Kolkata')
                    ->format('d M Y, h:i A'),
                'admin_action_at_formatted' => optional($leave->admin_action_at)
                    // ->timezone('Asia/Kolkata')
                    ->format('d M Y, h:i A'),

                // Resident Information
                'resident_name' => $leave->resident->name,
                'resident_email' => $leave->resident->email,
                'resident_scholar_no' => $leave->resident->scholar_no,
                // 'room_number' => $leave->resident->room->room_number ?? null,
                'resident_mobile' => $leave->resident->number ?? $leave->resident->profile->mobile,

                // QR Code & Verification
                'token' => $leave->token,
                // 'qr_code_base64' => $leave->token ? base64_encode($this->generateQrCode($leave->token)) : null,
                'qr_code_base64' => $withQr && $leave->token ? base64_encode($this->generateQrCode($leave->token)) : null,

                // Attachments
                'attachment_url' => $leave->attachment_path ?
                    url("storage/{$leave->attachment_path}") : null,
                'has_attachment' => !empty($leave->attachment_path),

                // Action Flags
                'can_edit' => $this->canEdit($leave),
                'can_delete' => $this->canDelete($leave),
                'can_cancel' => $this->canCancel($leave),
                'can_view_gatepass' => $this->canViewGatePass($leave)
            ];

            // Add serial number if requested
            if ($withSerial) {
                $data['serial_no'] = $index + 1;
            }

            // Add DataTable specific fields
            if (isset($leave->DT_RowIndex)) {
                $data['DT_RowIndex'] = $leave->DT_RowIndex;
            }

            return $data;
        });
    }
    /**
     * Transform gate pass data with QR code
     */
    public function transformGatepass($leave)
    {
        $data = $this->transformRecords($leave);
        $data['qr_code_base64'] = $leave->token
            ? base64_encode($this->generateQrCode($leave->token))
            : null;
        return $data;
    }


    /**
     * Transform single record
     */
    private function transformSingleRecord($leave)
    {
        $transformed = $this->transformRecords(collect([$leave]), false, true)->first();
        $transformed['attachment_details'] = $leave->attachment_path ? [
            'url' => url("storage/{$leave->attachment_path}"),
            'name' => basename($leave->attachment_path),
            'size' => Storage::disk('public')->size($leave->attachment_path),
            'type' => Storage::disk('public')->mimeType($leave->attachment_path)
        ] : null;

        return $transformed;
    }

    /**
     * Prepare gate pass data
     */
    private function prepareGatePassData($leave)
    {
        $resident = $leave->resident;

        return [
            'leave_id' => $leave->id,
            'student' => [
                'name' => $resident->name,
                'registration_no' => $resident->registration_number,
                // 'room_number' => $leave->resident->room->room_number ?? null,
                'course' => $resident->course,
                'department' => $resident->department,
                'email' => $resident->email,
                'mobile' => $resident->mobile
            ],
            'leave' => [
                'type' => ucfirst($leave->type),
                'reason' => $leave->reason,
                'start_date' => $leave->start_date->format('d M Y'),
                'end_date' => $leave->end_date->format('d M Y'),
                'duration' => $leave->duration . ' day(s)',
                'applied_on' => $leave->created_at->format('d M Y, h:i A')
            ],
            'verification' => [
                'token' => $leave->token,
                'qr_code' => base64_encode($this->generateQrCode($leave->token)),
                'verification_url' => url("/leave/verify/{$leave->token}")
            ],
            'hostel_timing' => [
                'in_time' => $resident->hostel_in_time ?? 'N/A',
                'out_time' => $resident->hostel_out_time ?? 'N/A'
            ],
            'generated_at' => now()->format('d M Y, h:i A'),
            'valid_until' => $leave->end_date->format('d M Y')
        ];
    }

    /**
     * Generate unique token for leave
     */
    private function generateUniqueToken()
    {
        do {
            $token = strtoupper(bin2hex(random_bytes(16)));
        } while (Leave::where('token', $token)->exists());

        return $token;
    }

    /**
     * Generate and save QR code
     */
    private function generateAndSaveQrCode($leave)
    {
        try {
            $qrCode = $this->generateQrCode($leave->token);
            $path = "qr-codes/leave-{$leave->id}.png";
            Storage::disk('public')->put($path, $qrCode);

            $leave->update(['qr_code_path' => $path]);
        } catch (\Exception $e) {
            Log::error('Failed to save QR code', [
                'leave_id' => $leave->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get available leave types
     */
    private function getLeaveTypes()
    {
        return [
            'casual' => 'Casual Leave',
            'medical' => 'Medical Leave',
            'emergency' => 'Emergency Leave',
            'parental' => 'Parental/Family Leave',
            'festival' => 'Festival Leave',
            'official' => 'Official/Academic Leave',
            'exam' => 'Exam Related Leave',
            'personal' => 'Personal Leave',
            'sports' => 'Sports Activity Leave',
            'other' => 'Other'
        ];
    }

    /**
     * Get reason options for different leave types
     */
    private function getReasonOptions()
    {
        return [
            'medical' => [
                'fever_cold' => 'Fever/Cold',
                'medical_checkup' => 'Medical Checkup',
                'hospitalization' => 'Hospitalization',
                'accident' => 'Accident/Injury',
                'other_medical' => 'Other Medical Reason'
            ],
            'emergency' => [
                'family_emergency' => 'Family Emergency',
                'accident_emergency' => 'Accident Emergency',
                'home_emergency' => 'Home Emergency',
                'other_emergency' => 'Other Emergency'
            ],
            // Add more as needed
        ];
    }

    /** ================================================
     * REUSABLE HELPER METHODS (from previous version)
     * ================================================ */

    // Include all the helper methods from the previous version:
    // getAuthenticatedUser, handleUnauthenticated, getResident, 
    // handleResidentNotFound, applyFilters, applySearch, applySorting,
    // getSummary, getStatusHtml, getRowClass, canEdit, canDelete, 
    // canCancel, canViewGatePass, handleError, generateQrCode,
    // calculateDuration, etc.

    // ... [Include all the helper methods from the previous response here]

    /**
     * Handle errors
     */
    private function handleError(Request $request, \Throwable $e)
    {
        $message = 'Failed to retrieve leave requests. Please try again.';
        $data = config('app.debug') ? ['exception' => $e->getMessage()] : null;

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'data'    => $data
            ], 500);
        }

        return back()->with('error', $message);
    }

    /**
     * Get authenticated user from appropriate guard
     */
    private function getAuthenticatedUser(Request $request)
    {
        // Try web guard first
        $user = $request->user();

        // Fallback to Sanctum guard for API
        if (!$user && $request->expectsJson()) {
            $user = Auth::guard('sanctum')->user();
        }

        return $user;
    }

    /**
     * Handle unauthenticated requests
     */
    private function handleUnauthenticated(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'data'    => null
            ], 401);
        }

        return redirect()->route('login');
    }

    /**
     * Get resident for the authenticated user
     */
    private function getResident($user)
    {
        return Resident::where('user_id', $user->id)->first();
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters($query, Request $request)
    {
        // Status filter
        if ($request->filled('status') || $request->filled('status_filter')) {
            $status = $request->filled('status') ? $request->status : $request->status_filter;
            $query->where('status', $status);
        }

        // HOD status filter
        if ($request->filled('hod_status')) {
            $query->where('hod_status', $request->hod_status);
        }

        // Admin status filter
        if ($request->filled('admin_status')) {
            $query->where('admin_status', $request->admin_status);
        }

        // Date range filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('start_date', [
                $request->from_date,
                $request->to_date
            ]);
        }

        // Leave type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        return $query;
    }

    /**
     * Apply sorting to the query
     */
    // private function applySorting($query, $orderColumn, $orderDirection, $isDataTable = false)
    // {
    //     // Define column mapping for DataTable
    //     $columnMap = [
    //         0 => 'created_at',    // Applied Date column
    //         1 => 'type',          // Type column  
    //         2 => 'reason',        // Reason column
    //         3 => 'start_date',    // Start Date
    //         4 => 'end_date',      // End Date
    //         5 => 'duration',      // Duration
    //         6 => 'status',        // Status
    //         // Add more columns as needed
    //     ];

    //     if ($isDataTable && isset($columnMap[$orderColumn])) {
    //         // DataTable specific sorting
    //         $column = $columnMap[$orderColumn];

    //         // Special handling for status column
    //         if ($column === 'status') {
    //             $query->orderByRaw("
    //             CASE 
    //                 WHEN status = 'pending' THEN 1
    //                 WHEN status = 'approved' THEN 2
    //                 WHEN status = 'rejected' THEN 3
    //                 WHEN status = 'cancelled' THEN 4
    //                 ELSE 5
    //             END $orderDirection
    //         ");
    //         } else {
    //             $query->orderBy($column, $orderDirection);
    //         }

    //         // Secondary ordering for consistent results
    //         $query->orderBy('id', $orderDirection);
    //     } else {
    //         // Default ordering when not DataTable or no column specified
    //         $query->orderBy('created_at', 'desc')
    //             ->orderByRaw("
    //               CASE 
    //                   WHEN status = 'pending' THEN 1
    //                   WHEN status = 'approved' THEN 2
    //                   WHEN status = 'rejected' THEN 3
    //                   WHEN status = 'cancelled' THEN 4
    //                   ELSE 5
    //               END
    //           ")
    //             ->orderBy('id', 'desc');
    //     }

    //     return $query;
    // }

    private function applySorting($query, $orderColumn, $orderDirection, $isDataTable = false, Request $request = null)
    {
        if ($isDataTable && $request) {
            // Get the column name from DataTable request
            $columns = $request->get('columns', []);

            if (isset($columns[$orderColumn])) {
                $columnName = $columns[$orderColumn]['name'] ??
                    $columns[$orderColumn]['data'] ??
                    null;

                // Validate allowed column names for security
                $allowedColumns = ['created_at', 'type', 'reason', 'start_date', 'end_date', 'hod_status', 'admin_status', 'duration', 'status'];

                if ($columnName && in_array($columnName, $allowedColumns)) {
                    if ($columnName === 'status') {
                        $query->orderByRaw("
                        CASE 
                            WHEN status = 'pending' THEN 1
                            WHEN status = 'approved' THEN 2
                            WHEN status = 'rejected' THEN 3
                            WHEN status = 'cancelled' THEN 4
                            ELSE 5
                        END $orderDirection
                    ");
                    } else {
                        $query->orderBy($columnName, $orderDirection);
                    }

                    // Secondary ordering for consistency
                    $query->orderBy('id', $orderDirection);
                    return $query;
                }
            }
        }

        // Default ordering when not DataTable or invalid column
        $query->orderBy('created_at', 'desc')
            ->orderByRaw("
              CASE 
                  WHEN status = 'pending' THEN 1
                  WHEN status = 'approved' THEN 2
                  WHEN status = 'rejected' THEN 3
                  WHEN status = 'cancelled' THEN 4
                  ELSE 5
              END
          ")
            ->orderBy('id', 'desc');

        return $query;
    }

    /**
     * Get HTML for status badge
     */
    private function getStatusHtml($status)
    {
        $statusMap = [
            'pending'  => '<span class="badge badge-warning">Pending</span>',
            'approved' => '<span class="badge badge-success">Approved</span>',
            'rejected' => '<span class="badge badge-danger">Rejected</span>',
            'cancelled' => '<span class="badge badge-secondary">Cancelled</span>'
        ];

        return $statusMap[strtolower($status)] ?? '<span class="badge badge-info">' . ucfirst($status) . '</span>';
    }

    /**
     * Generate QR code for leave token
     */
    private function generateQrCode($token)
    {
        try {
            return QrCode::format('png')
                ->size(150)
                ->margin(2)
                ->errorCorrection('H')
                ->generate(url("/leave/verify/{$token}"));
            // return null;
        } catch (\Exception $e) {
            Log::error('QR Code Generation Failed', ['token' => $token, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Check if leave can be edited
     */
    private function canEdit($leave)
    {
        return $leave->status === 'pending' &&
            $leave->hod_status === 'pending' &&
            $leave->admin_status === 'pending';
    }

    /**
     * Check if leave can be deleted
     */
    private function canDelete($leave)
    {
        return $leave->status === 'pending' &&
            $leave->hod_status === 'pending' &&
            $leave->admin_status === 'pending';
    }

    /**
     * Check if leave can be cancelled
     */
    private function canCancel($leave)
    {
        if ($leave->status !== 'pending') {
            return false;
        }

        try {
            $startDate = \Carbon\Carbon::parse($leave->start_date);
            return $startDate->isFuture();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if gate pass can be viewed
     */
    private function canViewGatePass($leave)
    {
        return $leave->status === 'approved' &&
            $leave->hod_status === 'approved' &&
            $leave->admin_status === 'approved';
    }

    /**
     * Apply search to the query for DataTable
     */
    /**
     * Apply search to the query for DataTable
     */
    private function applySearch($query, $searchValue, $startDate = null, $endDate = null)
    {
        return $query->where(function ($q) use ($searchValue) {
            $q->where('reason', 'like', "%{$searchValue}%")
                ->orWhere('type', 'like', "%{$searchValue}%")
                ->orWhere('reason', 'like', "%{$searchValue}%")
                ->orWhere('description', 'like', "%{$searchValue}%")
                ->orWhere('admin_status', 'like', "%{$searchValue}%")
                ->orWhere('hod_status', 'like', "%{$searchValue}%")
                ->orWhere('status', 'like', "%{$searchValue}%")
                ->orWhereHas('resident', function ($residentQuery) use ($searchValue) {
                    $residentQuery->where('name', 'like', "%{$searchValue}%")
                        ->orWhere('email', 'like', "%{$searchValue}%");
                });

            // Add start_date / end_date search in "like" style
            $q->orWhere('start_date', 'like', "%{$searchValue}%")->orWhere('end_date', 'like', "%{$searchValue}%");

            // Optional: if you want to filter by actual ranges
            // if ($startDate && $endDate) {
            //     $q->orWhereBetween('start_date', [$startDate, $endDate])->orWhereBetween('end_date', [$startDate, $endDate]);
            // }

            // // Date filtering using start_date and end_date fields
            // if ($startDate && $endDate) {
            //     $q->whereBetween('start_date', [$startDate, $endDate])->orWhereBetween('end_date', [$startDate, $endDate]);
            // } elseif ($startDate) {
            //     $q->whereDate('start_date', '>=', $startDate)->orWhereDate('end_date', '>=', $startDate);
            // } elseif ($endDate) {
            //     $q->whereDate('start_date', '<=', $endDate)->orWhereDate('end_date', '<=', $endDate);
            // }
        });
    }
}
