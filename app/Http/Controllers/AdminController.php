<?php

namespace App\Http\Controllers;

use App\Models\University;
use App\Models\User;
use App\Models\Resident;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Exception;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Accessory;
use App\Models\Fee;
use App\Models\GuestAccessory;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Mess;


class AdminController extends Controller
{
    private function apiResponse($success, $message, $data = null, $statusCode = 200, $errors = null)
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        $response['data'] = $data !== null ? $data : null;
        $response['errors'] = $errors !== null ? $errors : null;

        return response()->json($response, $statusCode);
    }

    public function getRoles()
    {
        try {
            $roles = Role::whereNotIn('name', ['admin', 'super_admin', 'resident', 'warden', 'security', 'hod'])->get();
            return $this->apiResponse(true, 'Roles fetched successfully.', $roles);
        } catch (Exception $e) {
            return $this->apiResponse(false, 'Failed to fetch roles.', null, 500, ['error' => $e->getMessage()]);
        }
    }
    public function getStaffRoles()
    {
        try {
            $roles = Role::whereIn('name', ['warden', 'security'])->get();
            return $this->apiResponse(true, 'Roles fetched successfully.', $roles);
        } catch (Exception $e) {
            return $this->apiResponse(false, 'Failed to fetch roles.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    public function getAdminProfile(Request $request)
    {
        try {
            $admin = User::Where('id', $request->header('auth-id'))
                ->first();

            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin not found',
                    'data' => null,
                    'errors' => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Admin profile fetched successfully',
                'data' => $admin,
                'errors' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch Admin profile',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }


    public function getUniversity()
    {
        try {
            $admin = auth()->user();
            $university = University::findOrFail($admin->university_id);

            // Wrap university in data key
            return $this->apiResponse(true, 'University details fetched successfully.', [
                'university' => $university
            ]);
        } catch (Exception $e) {
            return $this->apiResponse(false, 'Failed to fetch university details.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    public function createResident(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'bed_id' => 'required|exists:beds,id',
            ]);

            $adminId = $request->header('auth-id'); // Admin ID is passed in header
            if (!$adminId) {
                return $this->apiResponse(false, 'Unauthorized.', null, 401);
            }

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            $residentRole = Role::where('name', 'resident')->first();
            if (!$residentRole) {
                return $this->apiResponse(false, 'Resident role not found.', null, 500);
            }

            $user->assignRole($residentRole);

            $resident = Resident::create([
                'user_id' => $user->id,
                'bed_id' => $validatedData['bed_id'],
                'created_by' => $adminId,
            ]);

            // Return only safe data for user (hide password etc)
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
            ];

            return $this->apiResponse(true, 'Resident created and assigned to bed successfully.', [
                'user' => $userData,
                'resident' => $resident
            ], 201);
        } catch (ValidationException $e) {
            return $this->apiResponse(false, 'Validation failed.', null, 422, $e->errors());
        } catch (Exception $e) {
            return $this->apiResponse(false, 'An error occurred while creating resident.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    public function guestApproval(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'guest_id' => 'required|exists:guests,id'
            ]);

            // Helper::CreateInvoice($validatedData['guest_id']);

            $admin = User::find($request->header('auth-id'));
            $guest = Guest::findOrFail($validatedData['guest_id']);

            if ($guest->status === 'approved') {
                return $this->apiResponse(false, 'Payment request already approved.', null, 400);
            }

            $guest->status = 'approved';

            if ($guest->bihar_credit_card == 1 || $guest->tnsd == 1 || $guest->is_postpaid == 1) {
                $guest->is_postpaid = 1;

                $user = User::create([
                    'name' => $guest->name,
                    'gender' => $guest->gender,
                    'email' => $guest->email,
                    'university_id' => $admin->university_id,
                    'password' => Hash::make('12345678'),
                ]);

                $residentRole = Role::where('name', 'resident')->firstOrFail();
                $user->roles()->attach($residentRole->id, ['model_type' => User::class]);

                $resident = Resident::create([
                    'name' => $guest->name,
                    'email' => $guest->email,
                    'gender' => $guest->gender,
                    'scholar_no' => $guest->scholar_no,
                    'number' => $guest->number,
                    'parent_no' => $guest->parent_no,
                    'guardian_no' => $guest->guardian_no,
                    'mothers_name' => $guest->mothers_name,
                    'fathers_name' => $guest->fathers_name,
                    'user_id' => $user->id,
                    'guest_id' => $guest->id,
                    'status' => 'pending',
                    'created_by' => $admin->id,
                ]);

                Invoice::where('guest_id', $guest->id)->update(['resident_id' => $resident->id]);
            }

            $guest->save();

            return $this->apiResponse(true, 'Guest approved successfully.', [
                'guest' => [
                    'id' => $guest->id,
                    'status' => $guest->status,
                ],
                $resident ?? null,
                $user ?? null
            ]);
        } catch (ValidationException $e) {
            return $this->apiResponse(false, 'Validation failed.', null, 422, $e->errors());
        } catch (Exception $e) {
            return $this->apiResponse(false, 'An error occurred while approving guest.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    public function guestUpdateAndVerification(Request $request, $guest_id)
    {
        // dd($request->all(), $request->file(), $request->headers->all());
        // Log::info($request->all());
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|string|max:255',

                'email' => [
                    'sometimes',
                    'email',
                    Rule::unique('guests', 'email')->ignore($guest_id), // 👈 Ignore current guest
                ],
                'faculty_id' => 'sometimes|exists:faculties,id',
                'department_id' => 'sometimes|exists:departments,id',
                'course_id' => 'sometimes|exists:courses,id',
                'gender' => 'sometimes|in:Male,Female,Other',

                'scholar_no' => [
                    'sometimes',
                    Rule::unique('guests', 'scholar_no')->ignore($guest_id), // 👈 Ignore current guest
                ],

                'fathers_name' => 'sometimes|string|max:255',
                'mothers_name' => 'sometimes|string|max:255',
                'local_guardian_name' => 'nullable|string|max:255',
                'emergency_no' => 'sometimes|string|max:20',
                'number' => 'nullable|string|max:20',
                'parent_no' => 'nullable|string|max:20',
                'guardian_no' => 'nullable|string|max:20',
                'room_preference' => 'sometimes|string|max:255',
                'food_preference' => 'sometimes|string|max:255',
                'months' => 'nullable|integer|min:1|max:12',
                'accessories' => 'nullable|array',
                'accessories.*' => 'required|exists:accessory,id',
                'fee_waiver' => 'nullable|in:0,1',
                'bihar_credit_card' => 'nullable|in:0,1',
                'tnsd' => 'nullable|in:0,1',
                'is_postpaid' => 'nullable|in:0,1',
                'is_verified' => 'nullable|in:0,1',
                'status' => 'nullable|in:pending,approved,verified,rejected',
                'remarks' => [
                    'nullable',
                    'string',
                    'max:1000',
                    'required_if:fee_waiver,1',
                ],
                'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);

            $accessoryIds = array_map('intval', $request->input('accessories', []));

            $adminId = $request->header('auth-id'); // Admin ID is passed in header
            // Accessories selected from form
            // $selectedAccessories = $request->accessories ?? []; // array of [id => price]
            $months = $request->months ?? 1;

            if (!$adminId) {
                return $this->apiResponse(false, 'Unauthorized.', null, 401);
            }
            $guest = Guest::findOrFail($guest_id);
            foreach ($validatedData as $key => $value) {
                if ($key !== 'accessories' && $key !== 'attachment') { // Exclude accessory_head_ids and attachment from direct assignment
                    $guest->$key = $value;
                }
            }
            // Handle file attachment if provided
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filePath = $file->store('guest_attachments', 'public'); // Store in 'public/guest_attachments' directory
                $guest->attachment_path = $filePath;
            }
            $guest->is_verified = $request->input('is_verified', $guest->is_verified); // Update verification status
            $guest->status = 'verified'; // Update status to 'verified'
            $guest->created_by = $adminId; // Set the admin who verified
            $guest->save(); // Save the guest details

            // $invoice = $guest->invoices()->latest()->first();
            //   Log::info('invoices', $invoice->toArray());
            // $grandTotal = 0;
            // // $totalFee = InvoiceItem::where('invoice_id', $invoice->id)
            // //     ->where('item_type', 'fee')
            // //     ->sum('total_amount');
            // // $grandTotal += $totalFee;
            // // Step 2: Existing accessory IDs in invoice_items
            // $existingAccessoryIds = array_map('intval', $invoice->items()
            //     ->where('item_type', 'accessory')
            //     ->pluck('item_id')
            //     ->toArray());

            // Log::info('existingAccessoryIds: ' . json_encode($existingAccessoryIds));

            // // Step 3: New accessory IDs from request
            // $newAccessoryIds = array_map('intval', $accessoryIds ?? []);   

            // Log::info('newAccessoryIds: ' . json_encode($newAccessoryIds));

            // // Step 4: Find what to delete and what to insert
            // $toDelete = array_diff($existingAccessoryIds, $newAccessoryIds);
            // $toAdd    = array_diff($newAccessoryIds, $existingAccessoryIds);

            // // Log::info('toDelete: ' . json_encode($toDelete));
            // // Log::info('toAdd: ' . json_encode($toAdd));
            // // Step 5: Delete removed accessories
            // if (!empty($toDelete)) {
            //     $invoice->items()
            //         ->where('item_type', 'accessory')
            //         ->whereNot('price', '0') // Do not delete free accessories
            //         ->whereIn('item_id', $toDelete)
            //         ->delete();
            // }
            // // Step 6: Add newly selected accessories
            // // Step 6.5: Update existing accessories with new months
            // // $commonAccessoryIds = array_intersect($existingAccessoryIds, $newAccessoryIds);
            // $allAccessoryIdsToProcess = array_unique(array_merge($newAccessoryIds, $existingAccessoryIds));
            // // Log::info('Processing accessory IDs: ' . json_encode($allAccessoryIdsToProcess));

            // foreach ($allAccessoryIdsToProcess as $accId) {

            //     // Skip if not selected in the current request
            //     if (!in_array($accId, $newAccessoryIds)) {
            //         continue;
            //     }

            //     // foreach ($commonAccessoryIds as $accId) {
            //     $accessory = Accessory::with('accessoryHead')->find($accId);
            //     // Log::info('accessory: ' . json_encode($accessory));
            //     if (!$accessory) {
            //         Log::warning("Accessory ID {$accId} not found or inactive.");
            //         continue;
            //     }
            //     // Log::info('Adding accessory to invoice', ['accessory_id' => $accessory->id, 'accessory_head' => $accessory->accessoryHead?->name]);
            //     $price  = $accessory->price;
            //     $amount = $price * $months;

            //     $invoice->items()->updateOrCreate(
            //         [
            //             'item_type' => 'accessory',
            //             'item_id'   => $accessory->id,
            //         ],
            //         [
            //             'description'  => $accessory->accessoryHead?->name,
            //             'price'        => $price,
            //             'from_date'    => now(),
            //             'to_date'      => now()->addDays($months * 30),
            //             'total_amount' => $amount,
            //         ]
            //     );
            //     // $grandTotal += $amount;
            // }

            // // Step 7: Recalculate totals
            // $grandTotal = $invoice->items()->sum('total_amount');
            // $invoice->update([
            //     'total_amount'     => $grandTotal,
            //     'remaining_amount' => $grandTotal - $invoice->paid_amount, // in case partially paid
            // ]);

            // Step 1: Get the latest invoice
            $invoice = $guest->invoices()->latest()->first();
            if (!$invoice) {
                Log::warning("No invoice found for guest ID {$guest->id}");
                return;
            }

            $fees = Fee::select('id', 'fee_head_id', 'name', 'amount') // ✅ include 'id'
                ->where('is_active', 1)
                ->whereHas('feeHead', function ($q) use ($guest) {
                    $q->where('is_mandatory', 1)
                        ->where('university_id', $guest->faculty->university_id);
                })
                ->with('feeHead:id,is_one_time')
                ->get();




            // Log::info('fee'. json_encode($fees));
            foreach ($fees as $fee) {
                $price  = (float) $fee->amount;
                $isOneTime = $fee->feeHead?->is_one_time ?? false;
                $amount = $isOneTime ? $price : $price * $months;

                $existingItem = $invoice->items()
                    ->where('item_type', 'fee')
                    ->where('item_id', $fee->id)
                    ->first();

                if ($existingItem) {
                    // Check if values are the same
                    if (
                        $existingItem->price == $price &&
                        $existingItem->total_amount == $amount &&
                        $existingItem->description == $fee->name
                    ) {
                        // Log::info("No changes for fee item ID {$fee->id}, skipping update.");
                        continue; // Skip update
                    }

                    // Update only if something changed
                    $existingItem->update([
                        'description'  => $fee->name,
                        'price'        => $price,
                        'from_date'    => now(),
                        'to_date'      => now()->addDays($months * 30),
                        'total_amount' => $amount,
                    ]);
                } else {
                    // Create new item if not found
                    $invoice->items()->create([
                        'item_type'     => 'fee',
                        'item_id'       => $fee->id,
                        'description'   => $fee->name,
                        'price'         => $price,
                        'from_date'     => now(),
                        'to_date'       => now()->addDays($months * 30),
                        'total_amount'  => $amount,
                    ]);
                }
            }


            // Log::info('Applied fees: ' . json_encode($fees->pluck('fee_head_id')));

            // Step 3: Sync accessories
            $existingAccessoryIds = $invoice->items()
                ->where('item_type', 'accessory')
                ->pluck('item_id')
                ->map(fn($id) => (int) $id)
                ->toArray();

            $newAccessoryIds = array_map('intval', $accessoryIds ?? []);
            $toDelete = array_diff($existingAccessoryIds, $newAccessoryIds);

            if (!empty($toDelete)) {
                $invoice->items()
                    ->where('item_type', 'accessory')
                    ->where('price', '>', 0) // skip free accessories
                    ->whereIn('item_id', $toDelete)
                    ->delete();
            }

            $accessoryIdsToProcess = array_unique($newAccessoryIds);
            foreach ($accessoryIdsToProcess as $accId) {
                $accessory = Accessory::with('accessoryHead')->find($accId);
                if (!$accessory) {
                    Log::warning("Accessory ID {$accId} not found or inactive.");
                    continue;
                }

                $price  = (float) $accessory->price;
                $amount = $price * $months;

                $invoice->items()->updateOrCreate(
                    [
                        'item_type' => 'accessory',
                        'item_id'   => $accessory->id,
                    ],
                    [
                        'description'  => $accessory->accessoryHead?->name,
                        'price'        => $price,
                        'from_date'    => now(),
                        'to_date'      => now()->addDays($months * 30),
                        'total_amount' => $amount,
                    ]
                );
            }
            // Log::info('Processed accessories: ' . json_encode($accessoryIdsToProcess));

            // Step 4: Recalculate invoice totals
            $grandTotal = $invoice->items()->sum('total_amount');
            // Log::info('grandTotal'. '->' . json_encode($grandTotal));
            $invoice->update([
                'total_amount'     => $grandTotal,
                'remaining_amount' => $grandTotal - $invoice->paid_amount,
            ]);

            return $this->apiResponse(true, 'Guest details updated and verified successfully.', [
                'guest' => $guest->load('accessories') // Load accessories with accessory head details
            ], 200);
        } catch (ValidationException $e) {
            return $this->apiResponse(false, 'Validation failed.', null, 422, $e->errors());
        } catch (Exception $e) {
            return $this->apiResponse(false, 'An error occurred while updating guest details.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    // public function rejectPaymentRequest(Request $request)
    // {
    //     try {
    //         $validatedData = $request->validate([
    //             'guest_id' => 'required|exists:guests,id'
    //         ]);

    //         $guest = Guest::findOrFail($validatedData['guest_id']);

    //         if ($guest->status === 'rejected') {
    //             return $this->apiResponse(false, 'Payment request already rejected.', null, 400);
    //         }

    //         $guest->status = 'rejected';
    //         $guest->save();

    //         return $this->apiResponse(true, 'Guest payment request rejected successfully.', [
    //             'guest' => [
    //                 'id' => $guest->id,
    //                 'status' => $guest->status,
    //             ]
    //         ]);
    //     } catch (ValidationException $e) {
    //         return $this->apiResponse(false, 'Validation failed.', null, 422, $e->errors());
    //     } catch (Exception $e) {
    //         return $this->apiResponse(false, 'An error occurred while rejecting guest payment request.', null, 500, ['error' => $e->getMessage()]);
    //     }
    // } without admin remakrs


    public function rejectPaymentRequest(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'guest_id' => 'required|exists:guests,id',
                'admin_remarks' => 'nullable|string|max:1000', // Make it nullable if not always required, otherwise 'required'
            ]);

            $guest = Guest::findOrFail($validatedData['guest_id']);

            if ($guest->status === 'rejected') {
                return $this->apiResponse(false, 'Payment request already rejected.', null, 400);
            }

            $guest->status = 'rejected';
            $guest->admin_remarks = $validatedData['admin_remarks'] ?? null; // Store the remarks
            $guest->save();

            return $this->apiResponse(true, 'Guest payment request rejected successfully.', [
                'guest' => [
                    'id' => $guest->id,
                    'status' => $guest->status,
                    'admin_remarks' => $guest->admin_remarks, // Include in response
                ]
            ]);
        } catch (ValidationException $e) {
            return $this->apiResponse(false, 'Validation failed.', null, 422, $e->errors());
        } catch (Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Error rejecting guest payment request: ' . $e->getMessage(), [
                'guest_id' => $request->input('guest_id'),
                'exception' => $e
            ]);
            return $this->apiResponse(false, 'An error occurred while rejecting guest payment request.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    public function guestInvoicePreview(Request $request)
    {
        $months = (int) $request->input('months', 1);
        $accessoryIds = array_map('intval', $request->input('accessory_ids', []));

        // ✅ You probably don’t have $guest->faculty->university_id here
        // Instead, you can fetch the university_id from request OR use a default
        $universityId = $request->input('university_id'); // send from frontend hidden input

        $rawFees = Fee::select('fee_head_id', 'name', 'amount')
            ->where('is_active', 1)
            ->whereHas(
                'feeHead',
                fn($q) => $q->where('is_mandatory', 1)
                    ->where('university_id', $universityId)
            )
            ->with('feeHead:id,is_one_time')
            ->get();

        // same calculation as before...
        $combinedFees = [];
        $hostelMessTotal = 0;
        $hostelMessBase = 0;

        foreach ($rawFees as $fee) {
            $price = (float) $fee->amount;
            $isOneTime = (bool) $fee->feeHead?->is_one_time;
            $amount = $isOneTime ? $price : $price * $months;

            if (in_array($fee->name, ['Hostel Fee', 'Mess Fee'])) {
                $hostelMessTotal += $amount;
                $hostelMessBase += $price;
            } else {
                $combinedFees[] = [
                    'name' => $fee->name,
                    'amount' => $price,
                    'is_one_time' => $isOneTime,
                    'calculated_amount' => $amount,
                ];
            }
        }

        if ($hostelMessTotal > 0) {
            $combinedFees[] = [
                'name' => 'Hostel Fee',
                'amount' => $hostelMessBase,
                'is_one_time' => false,
                'calculated_amount' => $hostelMessTotal,
            ];
        }

        $accessories = Accessory::with('accessoryHead')
            ->whereIn('id', $accessoryIds)
            ->get()
            ->map(fn($acc) => [
                'name' => $acc->accessoryHead?->name,
                'price' => $acc->price,
                'calculated_amount' => $acc->price * $months,
            ]);

        $total = collect($combinedFees)->sum('calculated_amount') + $accessories->sum('calculated_amount');

        return response()->json([
            'fees' => $combinedFees,
            'accessories' => $accessories,
            'total' => $total,
        ]);
    }


    public function InvoicePreview(Request $request, Guest $guest)
    {
        $months = (int) $request->input('months', 1);
        $accessoryIds = array_map('intval', $request->input('accessory_ids', []));

        $rawFees = Fee::select('fee_head_id', 'name', 'amount')
            ->where('is_active', 1)
            ->whereHas(
                'feeHead',
                fn($q) =>
                $q->where('is_mandatory', 1)
                    ->where('university_id', $guest->faculty->university_id)
            )
            ->with('feeHead:id,is_one_time')
            ->get();

        $combinedFees = [];
        $hostelMessTotal = 0;
        $hostelMessBase = 0;

        foreach ($rawFees as $fee) {
            $price = (float) $fee->amount;
            $isOneTime = (bool) $fee->feeHead?->is_one_time;
            $amount = $isOneTime ? $price : $price * $months;

            if (in_array($fee->name, ['Hostel Fee', 'Mess Fee'])) {
                $hostelMessTotal += $amount;
                $hostelMessBase += $price;
            } else {
                $combinedFees[] = [
                    'name' => $fee->name,
                    'amount' => $price,
                    'is_one_time' => $isOneTime,
                    'calculated_amount' => $amount,
                ];
            }
        }

        if ($hostelMessTotal > 0) {
            $combinedFees[] = [
                // 'name' => 'Hostel + Mess Fee',
                'name' => 'Hostel Fee',
                'amount' => $hostelMessBase,
                'is_one_time' => false,
                'calculated_amount' => $hostelMessTotal,
            ];
        }

        $accessories = Accessory::with('accessoryHead')
            ->whereIn('id', $accessoryIds)
            ->get()
            ->map(fn($acc) => [
                'name' => $acc->accessoryHead?->name,
                'price' => $acc->price,
                'calculated_amount' => $acc->price * $months,
            ]);

        $total = collect($combinedFees)->sum('calculated_amount') + $accessories->sum('calculated_amount');

        return response()->json([
            'fees' => $combinedFees,
            'accessories' => $accessories,
            'total' => $total,
        ]);
    }
}
