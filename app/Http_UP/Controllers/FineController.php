<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Fee;
use App\Models\Mess;
use App\Helpers\Helper;
use App\Models\FeeHead;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\InvoiceItem;
use Illuminate\Support\Str;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class FineController extends Controller
{


    // public function adminSetFine(Request $request)
    // {
    //     try {
    //         // Base validation for admin side
    //         $rules = [
    //             'resident_id' => 'required|exists:residents,id',
    //             'fee_head_id' => 'required|exists:fees,fee_head_id', // Make sure 'fees' has 'fee_head_id' column or adjust
    //             'subscription_type' => 'required|string|in:Other', // Updated types
    //             'duration' => 'nullable|string|in:1 Month,3 Months,6 Months,1 Year', // For standard types
    //             // Admin does NOT set custom_amount directly here.
    //             'remarks' => 'nullable|string', // Remarks for general notes or for 'Other' type
    //             'created_by' => 'nullable|exists:users,id', // Assuming 'users' table for created_by
    //         ];

    //         // Conditional validation based on subscription_type
    //         if ($request->subscription_type === 'Other') {
    //             $rules['remarks'] = 'required|string'; // Remarks mandatory for 'Other'
    //             $rules['duration'] = 'nullable'; // Duration is not applicable for 'Other'
    //         } else {
    //             $rules['duration'] = 'required|string|in:1 Month,3 Months,6 Months,1 Year'; // Duration mandatory for standard types
    //         }

    //         $request->validate($rules);

    //         $resident = Resident::findOrFail($request->resident_id);

    //         // Fetch the active fee details
    //         $fee = Fee::where('fee_head_id', $request->fee_head_id)->where('is_active', true)->first();

    //         if (!$fee) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Active fee not found for the given fee_head_id.',
    //                 'data' => null,
    //                 'errors' => ['fee_head_id' => ['Active fee not found.']],
    //             ], 404);
    //         }

    //         $startDate = null;
    //         $endDate = null;
    //         $pricePerUnit = $fee->amount; // This is the base amount per month/period
    //         $calculatedTotalAmount = 0;

    //         if ($request->subscription_type === 'Other') {
    //             // For 'Other', admin sets a 'placeholder' total amount as 0, accountant will fill it
    //             $calculatedTotalAmount = 0;
    //             $startDate = null; // Or Carbon::now() if 'Other' can still have a start date
    //             $endDate = null;   // And a corresponding end date
    //         } else {
    //             $months = match ($request->duration) {
    //                 '1 Month' => 1,
    //                 '3 Months' => 3,
    //                 '6 Months' => 6,
    //                 '1 Year' => 12,
    //                 default => throw ValidationException::withMessages(['duration' => 'Invalid duration.']),
    //             };

    //             $startDate = Carbon::now();
    //             $endDate = $startDate->copy()->addMonths($months);
    //             $calculatedTotalAmount = $pricePerUnit * $months;
    //         }

    //         // Create subscription (no payment entry from admin side)
    //         $subscription = Subscription::create([
    //             'resident_id' => $resident->id,
    //             'fee_head_id' => $fee->fee_head_id,
    //             'subscription_type' => $request->subscription_type,
    //             'price' => $pricePerUnit, // This is the base fee amount (e.g., monthly fee)
    //             'total_amount' => $calculatedTotalAmount, // Calculated based on duration or 0 for 'Other'
    //             'start_date' => $startDate,
    //             'end_date' => $endDate,
    //             'status' => 'Pending', // Initial status is pending payment
    //             'remarks' => $request->remarks,
    //             'created_by' => $request->created_by ?? auth()->id(), // Use authenticated user if not provided
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Subscription created successfully. Awaiting accountant review and payment.',
    //             'data' => ['subscription_id' => $subscription->id],
    //             'errors' => null,
    //         ], 201);
    //     } catch (ValidationException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation error.',
    //             'data' => null,
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (ModelNotFoundException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Resident or Fee not found.',
    //             'data' => null,
    //             'errors' => null,
    //         ], 404);
    //     } catch (Exception $e) {
    //         \Log::error("Error in adminSubscribeResident: " . $e->getMessage() . " - " . $e->getFile() . " on line " . $e->getLine());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Server error occurred during subscription creation.',
    //             'data' => null,
    //             'errors' => ['exception' => $e->getMessage()],
    //         ], 500);
    //     }
    // }

    // // Calculate `to_date` based on the duration
    // $toDate = $fromDate->copy()->addMonths($months);

    // // Set a fixed due date (30 days from now)
    // $dueDate = now()->addDays($months * 30);

    // // Calculate the total amount (price × duration in months)
    // $totalAmount = $accessory->price * $months;

    // $nextId = (Invoice::max('id') ?? 0) + 1;
    // $invoiceNumber = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    // //create Invoice
    // $invoice = Invoice::create([
    //     'resident_id' => $resident->id,
    //     'invoice_number' => $invoiceNumber,
    //     'invoice_date' => now(),
    //     'due_date' => $dueDate,
    //     'total_amount' => $totalAmount,
    //     'paid_amount' => 0,
    //     'remaining_amount' => $totalAmount,
    //      'remarks' => 'Accessory Charge',
    //     'status' => 'Pending',
    // ]);

    // //create Invoice Items
    // InvoiceItem::create([
    //     'invoice_id' => $invoice->id,
    //     'item_type' => 'accessory',
    //     'item_id' => $accessory->id,
    //     'description' => $accessory->accessoryHead->name,
    //     'price' => $accessory->price,
    //     'from_date' => $fromDate,
    //     'to_date' => $toDate,
    //     'month' => $months,
    //     'total_amount' => $totalAmount,
    // ]); 

    // Changed on 21092025
    public function adminSetFine(Request $request)
    {
        try {
            $user = Helper::get_auth_admin_user($request);
            // Step 1: Validate incoming request
            $rules = [
                'resident_id' => 'required|exists:residents,id',
                'subscription_type' => 'required|string|in:Other',
                'duration' => 'nullable|string|in:1 Month,3 Months,6 Months,1 Year',
                'remarks' => 'nullable|string',
                'created_by' => 'nullable|exists:users,id',
            ];

            // For "Other" subscription type, remarks required and duration not needed
            if ($request->subscription_type === 'Other') {
                $rules['remarks'] = 'required|string';
                $rules['duration'] = 'nullable';
            } else {
                $rules['duration'] = 'required|string|in:1 Month,3 Months,6 Months,1 Year';
            }

            $request->validate($rules);

            // Step 2: Get resident
            $resident = Resident::findOrFail($request->resident_id);

            // Step 3: Auto-fetch fee by subscription type name (e.g., "Other")
            $fee = Fee::where('name', $request->subscription_type)
                ->where('is_active', true)
                ->first();

            if (!$fee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Active fee not found for the subscription type: ' . $request->subscription_type,
                    'data' => null,
                    'errors' => ['fee_head_id' => ['Fee for this type is missing.']],
                ], 404);
            }

            $feeHeadId = $fee->fee_head_id;
            $pricePerUnit = $fee->amount;
            $calculatedTotalAmount = 0;
            $startDate = null;
            $endDate = null;

            // Step 4: Handle amount & duration based on subscription type
            if ($request->subscription_type === 'Other') {
                $calculatedTotalAmount = 0;
                $startDate = null;
                $endDate = null;
            } else {
                $months = match ($request->duration) {
                    '1 Month' => 1,
                    '3 Months' => 3,
                    '6 Months' => 6,
                    '1 Year' => 12,
                    default => throw ValidationException::withMessages(['duration' => 'Invalid duration.']),
                };

                $startDate = Carbon::now();
                $endDate = $startDate->copy()->addMonths($months);
                $calculatedTotalAmount = $pricePerUnit * $months;
            }

            // Step 5: Create subscription
            $subscription = Subscription::create([
                'resident_id' => $resident->id,
                'fee_head_id' => $feeHeadId,
                'subscription_type' => $request->subscription_type,
                'price' => $pricePerUnit,
                'total_amount' => $calculatedTotalAmount,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'Pending',
                // 'remarks' => $request->remarks,

                'remarks' => json_encode([
                    $user->role ?? 'admin' => [
                        'text' => $request->remarks,
                        'timestamp' => now()->toDateTimeString(),
                        'user_id' => $user->id
                    ]
                ]),
                'created_by' => $request->created_by ?? $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Fine assigned successfully. Awaiting accountant approval.',
                'data' => ['subscription_id' => $subscription->id],
                'errors' => null,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'data' => null,
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resident not found.',
                'data' => null,
                'errors' => null,
            ], 404);
        } catch (\Exception $e) {
            \Log::error("adminSetFine Error: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");

            return response()->json([
                'success' => false,
                'message' => 'Server error occurred during fine assignment.',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }

    // 21092025
    public function assignFineToResident(Request $request)
    {
        Log::info('Fine Appliying', $request->all());
        try {

            $user = Helper::get_auth_admin_user($request);
            Log::info('user' . json_encode($user));
            // Step 1: Validate incoming request
            $rules = [
                'resident_id' => 'required|exists:residents,id',
                'subscription_type' => 'required|string|in:Other',
                'duration' => 'nullable|string|in:1 Month,3 Months,6 Months,1 Year',
                'remarks' => 'nullable|string',
                'created_by' => 'nullable|exists:users,id',
            ];

            // dd(die);
            // For "Other" subscription type, remarks required and duration not needed

            $request->validate($rules);

            $residentId = $request->resident_id;

            $resident = Resident::findOrFail($residentId);
            $amount = $request->amount;
            $remarks = $request->remarks;
            $fineDetails = [
                'resident_id' => $request->resident_id,
                'amount' => $request->resident_id,
                'description' => $request->remarks
            ];
            // Step 1: Find existing fine-only invoice
            $invoice = Invoice::where('resident_id', $residentId)
                ->where('type', 'fine')
                ->where('status', 'pending') // optional: only open invoices
                ->first();

            Log::info('invoice exisitng' . json_encode($invoice));

            $feeHead = FeeHead::select('id', 'name') // ✅ include 'id'
                ->where('status', 1)
                ->where('name', 'Other Fee')
                ->where('university_id', $resident->user->university_id)
                // ->with('feeHead:id,is_one_time')
                ->get();

            // Log::info('fees'. json_encode($feeHead))
            $feeHead = $feeHead[0];
            if (!$feeHead) {
                $feeHead = [
                    'id' => 17,
                    'name' => "Other Fee",
                ];
            }

            // Log::info('feeHeadId'. json_encode($feeHeadId));
            // Step 2: Create new invoice if none found
            if (!$invoice) {

                $invoiceNumber = Invoice::generateInvoiceNumber('O'); // or 'SUB', 'ACC'
                // Log::info('InvoiceNumber'. json_encode($invoiceNumber));

                $invoice = Invoice::create([
                    'resident_id' => $residentId,
                    'invoice_number' => $invoiceNumber,
                    'type' => 'fine',
                    'invoice_date' => now(),
                    'total_amount' => $amount,
                    'paid_amount' => 0,
                    // 'remaining_amount' => ,
                    // 'remarks' => $remarks ?? 'Fine',
                    'remarks' => json_encode([
                        $user->role ?? 'admin' => [
                            'text' => $request->remarks,
                            'timestamp' => now()->toDateTimeString(),
                            'user_id' => $user->id
                        ]
                    ]),

                    'status' => 'Pending',
                    'due_date' => now()->addDays(7), // optional
                ]);
            }

            // $invoiceId = $invoice->id;
            // Log::info('invoiceId'. json_encode($invoiceId));

            $items = $invoice->items()->create([
                'invoice_id' => $invoice->id,
                'item_type' => 'fine',
                'item_id' => $feeHead->id,
                'description' => $remarks ?? $feeHead->name,
                'price' => $amount,
                'from_date' => now(),
                'to_date' => now()->addDays(120),
                'month' => '4',
                'total_amount' => $amount,
            ]);

            // Log::info('invoice items' . json_encode($items));

            // Step 4: Recalculate invoice totals
            $grandTotal = $invoice->items()->sum('total_amount');
            // Log::info('grandTotal'. '->' . json_encode($grandTotal));
            $invoice->update([
                'total_amount'     => $grandTotal,
                'remaining_amount' => $grandTotal - $invoice->paid_amount,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Fine assigned successfully. Awaiting accountant approval.',
                'data' => ['subscription_id' => $invoice->id],
                'errors' => null,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'data' => null,
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resident not found.',
                'data' => null,
                'errors' => null,
            ], 404);
        } catch (\Exception $e) {
            \Log::error("adminSetFine Error: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");

            return response()->json([
                'success' => false,
                'message' => 'Server error occurred during fine assignment.',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }
    // public function assignFineToResident(Request $request)
    // {
    //     \Log::info('Fine Applying', $request->all());

    //     try {
    //         $user = Helper::get_auth_admin_user($request);

    //         // Step 1: Validate request
    //         $rules = [
    //             'resident_id' => 'required|exists:residents,id',
    //             'subscription_type' => 'required|string|in:Other',
    //             'duration' => 'nullable|string|in:1 Month,3 Months,6 Months,1 Year',
    //             'remarks' => 'nullable|string',
    //             'amount' => 'required|numeric|min:0.01',
    //             'created_by' => 'nullable|exists:users,id',
    //         ];

    //         $request->validate($rules);

    //         $resident = Resident::findOrFail($request->resident_id);
    //         $amount = $request->amount;
    //         $remarks = $request->remarks;

    //         // Step 2: Find or create fine invoice
    //         $invoice = Invoice::firstOrCreate([
    //             'resident_id' => $resident->id,
    //             'type' => 'fine',
    //             'status' => 'Proposed',
    //         ], [
    //             'invoice_number' => Invoice::generateInvoiceNumber('F'),
    //             'invoice_date' => now(),
    //             'total_amount' => 0,
    //             'paid_amount' => 0,
    //             'remarks' => json_encode(['admin' => $remarks]), // Store structured remarks
    //             'due_date' => now()->addDays(7),
    //             'created_by' => $request->created_by ?? $user->id,
    //         ]);

    //         // Step 3: Get fee head
    //         // $feeHead = FeeHead::where('status', 1)
    //         //     ->where('name', 'Other Fee')
    //         //     ->where('university_id', $resident->user->university_id)
    //         //     ->firstOrFail();

    //         $feeHead = FeeHead::firstOrCreate([
    //             'name' => 'Other Fee',
    //             'university_id' => $resident->user->university_id,
    //         ], [
    //             'status' => 1,
    //         ]);


    //         // Step 4: Add invoice item
    //         $invoiceItem = $invoice->items()->create([
    //             'item_type' => 'fine',
    //             'item_id' => $feeHead->id,
    //             'description' => $remarks ?? $feeHead->name,
    //             'price' => $amount,
    //             'from_date' => now(),
    //             'to_date' => now()->addDays(120),
    //             'month' => now()->format('m'),
    //             'total_amount' => $amount,
    //         ]);

    //         // Step 5: Recalculate totals
    //         $grandTotal = $invoice->items()->sum('total_amount');
    //         $invoice->update([
    //             'total_amount' => $grandTotal,
    //             'remaining_amount' => $grandTotal - $invoice->paid_amount,
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Fine proposed successfully. Awaiting accountant approval.',
    //             'data' => ['invoice_id' => $invoice->id],
    //         ], 201);
    //     } catch (ValidationException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation error.',
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (ModelNotFoundException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Resident or Fee Head not found.',
    //         ], 404);
    //     } catch (\Exception $e) {
    //         \Log::error("assignFineToResident Error: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Server error occurred during fine assignment.',
    //             'errors' => ['exception' => $e->getMessage()],
    //         ], 500);
    //     }
    // }





    // public function accountantSetFineAmount(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'subscription_id' => 'required|exists:subscriptions,id',
    //             'amount_paid' => 'required|numeric|min:0',
    //             'payment_method' => 'required|string|in:Cash,UPI,Bank Transfer,Card,Other,Null',
    //             'payment_remarks' => 'nullable|string|max:1000',
    //             'created_by' => 'nullable|exists:users,id',
    //         ]);

    //         $subscription = Subscription::findOrFail($request->subscription_id);

    //         if ($subscription->payments()->exists()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'A payment record for this subscription already exists.',
    //                 'data' => null,
    //                 'errors' => ['subscription_id' => ['Subscription already has an initial payment.']],
    //             ], 409);
    //         }

    //         $totalAmountExpected = $subscription->total_amount;

    //         if ($subscription->subscription_type === 'Other' && $subscription->total_amount == 0) {
    //             $totalAmountExpected = $request->amount_paid;
    //             $subscription->total_amount = $totalAmountExpected;
    //             $subscription->price = $request->amount_paid;
    //             $subscription->save();
    //         }

    //         $paymentAmountInRecord = 0;
    //         $remainingAmountForPaymentRecord = $totalAmountExpected;
    //         $paymentStatusForPaymentRecord = 'Pending';
    //         $transactionIdForPaymentRecord = null;

    //         $subscriptionRemainingBalance = $totalAmountExpected - $request->amount_paid;
    //         $subscription->status = $subscriptionRemainingBalance <= 0 ? 'Active' : 'Partially Paid';
    //         $subscription->save();

    //         $dueDate = ($subscriptionRemainingBalance > 0 && $subscription->start_date)
    //             ? Carbon::now()->addDays(7)
    //             : null;

    //         $payment = Payment::create([
    //             'resident_id' => $subscription->resident_id,
    //             'fee_head_id' => $subscription->fee_head_id,
    //             'subscription_id' => $subscription->id,
    //             'total_amount' => $totalAmountExpected,
    //             'amount' => $paymentAmountInRecord,
    //             'remaining_amount' => $remainingAmountForPaymentRecord,
    //             'transaction_id' => $transactionIdForPaymentRecord,
    //             'payment_method' => $request->payment_method,
    //             'payment_status' => $paymentStatusForPaymentRecord,
    //             'due_date' => $dueDate,
    //             'payment_date' => Carbon::now(),
    //             'created_by' => $request->created_by ?? auth()->id(),
    //             'remarks' => $request->payment_remarks,
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Payment record created and subscription updated successfully.',
    //             'data' => ['payment_id' => $payment->id, 'subscription_id' => $subscription->id],
    //             'errors' => null,
    //         ], 201);
    //     } catch (ValidationException $e) {
    //         \Log::error("Validation Error: " . json_encode($e->errors()));
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation error.',
    //             'data' => null,
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (ModelNotFoundException $e) {
    //         \Log::error("Model Not Found: " . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Subscription not found.',
    //             'data' => null,
    //             'errors' => null,
    //         ], 404);
    //     } catch (Exception $e) {
    //         \Log::error("Server Error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Server error occurred during payment processing.',
    //             'data' => null,
    //             'errors' => ['exception' => $e->getMessage()],
    //         ], 500);
    //     }
    // }




    public function accountantSetFineAmount(Request $request)
    {
        try {
            $request->validate([
                'subscription_id' => 'required|exists:subscriptions,id',
                'amount_paid' => 'required|numeric|min:0',
                'payment_method' => 'required|string|in:Cash,UPI,Bank Transfer,Card,Other,Null',
                'payment_remarks' => 'nullable|string|max:1000',
                'created_by' => 'nullable|exists:users,id',
            ]);

            $subscription = Subscription::findOrFail($request->subscription_id);

            if ($subscription->payments()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A payment record for this subscription already exists.',
                    'data' => null,
                    'errors' => ['subscription_id' => ['Subscription already has an initial payment.']],
                ], 409);
            }

            $totalAmountExpected = $subscription->total_amount;

            // Set price and total amount if subscription type is 'Other' and total is 0
            if ($subscription->subscription_type === 'Other' && $subscription->total_amount == 0) {
                $totalAmountExpected = $request->amount_paid;
                $subscription->total_amount = $totalAmountExpected;
                $subscription->price = $request->amount_paid;
            }

            // ✅ Always set status to 'Pending'
            $subscription->status = 'Pending';
            $subscription->save();

            // Set due date only if there's remaining amount
            $dueDate = ($subscription->total_amount > $request->amount_paid && $subscription->start_date)
                ? Carbon::now()->addDays(7)
                : null;

            $payment = Payment::create([
                'resident_id' => $subscription->resident_id,
                'fee_head_id' => $subscription->fee_head_id,
                'subscription_id' => $subscription->id,
                'total_amount' => $totalAmountExpected,
                'amount' => 0,
                'remaining_amount' => $totalAmountExpected,
                'transaction_id' => null,
                'payment_method' => $request->payment_method,
                'payment_status' => 'Pending',
                'due_date' => $dueDate,
                'payment_date' => Carbon::now(),
                'created_by' => $request->created_by ?? auth()->id(),
                'remarks' => $request->payment_remarks,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment record created and subscription updated successfully.',
                'data' => [
                    'payment_id' => $payment->id,
                    'subscription_id' => $subscription->id,
                ],
                'errors' => null,
            ], 201);
        } catch (ValidationException $e) {
            \Log::error("Validation Error: " . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'data' => null,
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            \Log::error("Model Not Found: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found.',
                'data' => null,
                'errors' => null,
            ], 404);
        } catch (Exception $e) {
            \Log::error("Server Error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred during payment processing.',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }



    // 21092025
    public function viewAllFineDetails()
    {
        try {
            // Fetch all subscriptions where subscription_type is 'Other' (i.e., Fines)
            $fines = Subscription::with(['resident', 'feeHead', 'payments', 'createdBy'])
                ->where('subscription_type', 'Other')
                ->where('status', 'Pending')
                ->where('total_amount', 0)
                ->orderByDesc('created_at')
                ->get();

            $data = [];
            foreach ($fines as $subscription) {
                $fineData = [
                    'subscription_id' => $subscription->id,
                    'resident_name' => $subscription->resident->name ?? 'N/A',
                    'resident_scholar_no' => $subscription->resident->scholar_no ?? 'N/A',
                    'fee_head_name' => $subscription->feeHead->name ?? 'N/A',
                    'subscription_type' => $subscription->subscription_type,
                    'base_fee_per_unit' => $subscription->price,
                    'calculated_total_amount_by_admin' => $subscription->total_amount,
                    'start_date' => $subscription->start_date ? $subscription->start_date->toDateString() : null,
                    'end_date' => $subscription->end_date ? $subscription->end_date->toDateString() : null,
                    'subscription_status' => $subscription->status,
                    'admin_remarks' => $subscription->remarks,
                    'created_by_admin' => $subscription->createdBy->name ?? 'Admin',
                    'created_at' => $subscription->created_at->toDateTimeString(),
                    'payment_details' => [],
                ];

                if ($subscription->payments->isNotEmpty()) {
                    foreach ($subscription->payments as $payment) {
                        $fineData['payment_details'][] = [
                            'payment_id' => $payment->id,
                            'total_amount_expected' => $payment->total_amount,
                            'amount_paid_this_transaction' => $payment->amount,
                            'remaining_amount' => $payment->remaining_amount,
                            'payment_method' => $payment->payment_method,
                            'payment_status' => $payment->payment_status,
                            'transaction_id' => $payment->transaction_id,
                            'due_date' => $payment->due_date ? $payment->due_date->toDateString() : null,
                            'payment_date' => $payment->payment_date ? $payment->payment_date->toDateTimeString() : null,
                            'accountant_remarks' => $payment->remarks,
                            'processed_by' => $payment->createdBy->name ?? 'Accountant',
                            'paid_at' => $payment->created_at->toDateTimeString(),
                        ];
                    }
                } else {
                    $fineData['payment_details'][] = [
                        'message' => 'No payment has been processed for this fine yet.',
                        'payment_status' => 'Pending',
                    ];
                }

                $data[] = $fineData;
            }

            return response()->json([
                'success' => true,
                'message' => 'All fine (Other type) subscriptions fetched successfully.',
                'data' => $data,
                'errors' => null,
            ], 200);
        } catch (Exception $e) {
            \Log::error("Error in viewAllFineDetails: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred while fetching fine details.',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }

    // 21092025
    // public function PendingFines()
    // {
    //     try {
    //         // Fetch all subscriptions where subscription_type is 'Other' (i.e., Fines)

    //         // $fines = InvoiceItem::with(['invoice'])
    //         $fines = InvoiceItem::where('item_type', 'fine')
    //             ->whereHas('invoice', 'fine')
    //             ->whereHas('invoice', function ($q) use (invoice) {
    //                 $q->where('status', 'pending')
    //                     ->where('type', 'fine');
    //             })

    //             ->orderByDesc('created_at')
    //             ->get();

    //             Log::info('fines'.  json_encode($fines));
    //             dd(die);
    //         $roster = [];
    //         foreach ($invoices as $invoice) {
    //             $roster[] = $invoice->items;
    //         }
    //         Log::info('invoices'.  json_encode($roster));


    //         $invoices = Invoice::with(['items'])
    //             ->where('type', 'fine')
    //             ->where('status', 'Pending')
    //             ->orderByDesc('created_at')
    //             ->get();

    //         $roster = [];
    //         foreach ($invoices as $invoice) {
    //             $roster[] = $invoice->items;
    //         }
    //         Log::info('invoices'.  json_encode($roster));


    //         $fines = $roster;
    //         Log::info('roster'.  json_encode($fines));
    //         foreach ($fines as $fine) {
    //             $allfine[] = $fine;
    //         }

    //         Log::info('fines'.  json_encode($fine));

    //         dd(die);
    //         $data = [];
    //         foreach ($fines as $subscription) {
    //             $fineData = [
    //                 'subscription_id' => $subscription->id,
    //                 'resident_name' => $subscription->resident->name ?? 'N/A',
    //                 'resident_scholar_no' => $subscription->resident->scholar_no ?? 'N/A',
    //                 'fee_head_name' => $subscription->feeHead->name ?? 'N/A',
    //                 'subscription_type' => $subscription->subscription_type,
    //                 'base_fee_per_unit' => $subscription->price,
    //                 'calculated_total_amount_by_admin' => $subscription->total_amount,
    //                 'start_date' => $subscription->start_date ? $subscription->start_date->toDateString() : null,
    //                 'end_date' => $subscription->end_date ? $subscription->end_date->toDateString() : null,
    //                 'subscription_status' => $subscription->status,
    //                 'admin_remarks' => $subscription->remarks,
    //                 'created_by_admin' => $subscription->createdBy->name ?? 'Admin',
    //                 'created_at' => $subscription->created_at->toDateTimeString(),
    //                 'payment_details' => [],
    //             ];

    //             if ($subscription->payments->isNotEmpty()) {
    //                 foreach ($subscription->payments as $payment) {
    //                     $fineData['payment_details'][] = [
    //                         'payment_id' => $payment->id,
    //                         'total_amount_expected' => $payment->total_amount,
    //                         'amount_paid_this_transaction' => $payment->amount,
    //                         'remaining_amount' => $payment->remaining_amount,
    //                         'payment_method' => $payment->payment_method,
    //                         'payment_status' => $payment->payment_status,
    //                         'transaction_id' => $payment->transaction_id,
    //                         'due_date' => $payment->due_date ? $payment->due_date->toDateString() : null,
    //                         'payment_date' => $payment->payment_date ? $payment->payment_date->toDateTimeString() : null,
    //                         'accountant_remarks' => $payment->remarks,
    //                         'processed_by' => $payment->createdBy->name ?? 'Accountant',
    //                         'paid_at' => $payment->created_at->toDateTimeString(),
    //                     ];
    //                 }
    //             } else {
    //                 $fineData['payment_details'][] = [
    //                     'message' => 'No payment has been processed for this fine yet.',
    //                     'payment_status' => 'Pending',
    //                 ];
    //             }

    //             $data[] = $fineData;
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'All fine (Other type) subscriptions fetched successfully.',
    //             'data' => $data,
    //             'errors' => null,
    //         ], 200);
    //     } catch (Exception $e) {
    //         \Log::error("Error in viewAllFineDetails: " . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Server error occurred while fetching fine details.',
    //             'data' => null,
    //             'errors' => ['exception' => $e->getMessage()],
    //         ], 500);
    //     }
    // }


    public function showFineAssignmentForm()
    {
        return view('admin.fine');
    }



    public function PendingFines()
    {
        try {
            // $fines = InvoiceItem::with('invoice')
            //     ->where('item_type', 'fine')
            //     ->whereHas('invoice', function ($query) {
            //         $query->where('status', 'pending');
            //     })
            //     ->get();

            $fines = InvoiceItem::with('invoice')
                ->where('item_type', 'fine')
                // ->where('status', null)
                // ->orWhere('status', 'proposed')
                ->whereHas('invoice', function ($query) {
                    // $query->where('status', 'pending')
                    $query->where('status', 'proposed')
                        ->where('type', 'fine');
                })
                ->get();

            // Log::info('fines' . json_encode($fines));

            // Group fines by invoice
            // $grouped = $fines->groupBy('invoice_id')->map(function ($items, $invoiceId) {
            //     $invoice = $items->first()->invoice;

            //     return [
            //         'invoice_id' => $invoice->id,
            //         'invoice_number' => $invoice->invoice_number,
            //         'resident_id' => $invoice->resident_id,
            //         'invoice_date' => $invoice->invoice_date,
            //         'due_date' => $invoice->due_date,
            //         'remarks' => $invoice->remarks,
            //         'status' => $invoice->status,
            //         'total_invoice_amount' => $invoice->total_amount,
            //         'paid_amount' => $invoice->paid_amount,
            //         'remaining_amount' => $invoice->remaining_amount,
            //         'fines' => $items->map(function ($item) {
            //             return [
            //                 'id' => $item->id,
            //                 'description' => $item->description,
            //                 'price' => $item->price,
            //                 'from_date' => $item->from_date,
            //                 'to_date' => $item->to_date,
            //                 'total_amount' => $item->total_amount,
            //                 'created_at' => $item->created_at->toDateTimeString(),
            //             ];
            //         }),
            //         'total_fine_amount' => $items->sum('total_amount'),
            //     ];
            // })->values(); // Reset keys for clean JSON

            $allfines = [];
            foreach ($fines as $fine) {
                $allfines[] = [
                    'fine_id' => $fine->id,
                    'invoice_id' => $fine->invoice_id,
                    'resident_id' => $fine->invoice->resident_id,
                    'scholar_no' => $fine->invoice->resident->scholar_no,
                    'resident_name' => $fine->invoice->resident->name,
                    'proposed_amount' => $fine->price,
                    'admin_remarks' => $fine->description,
                    'invoice_number' => $fine->invoice->invoice_number,
                    'fine_date' => $fine->created_at,
                ];
            }

            $data = $allfines;
            // Log::info('allFines' . json_encode($allfines));

            return response()->json([
                'success' => true,
                'message' => 'All fine (Other type) subscriptions fetched successfully.',
                'data' => $data,
                'errors' => null,
            ], 200);
        } catch (Exception $e) {
            Log::error("Error in viewAllFineDetails: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred while fetching fine details.',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }

    public function updateFineItem(Request $request)
    {
        // Log::info('updting fine',  $request->all());
        // $user = Helper::get_auth_admin_user($request);
        // Log::info('user' . json_encode($user));
        try {
            $request->validate([
                'payable_amount' => 'required|numeric|min:0',
                // 'payment_method' => 'required|string|in:Cash,UPI,Bank Transfer,Card,Other,Null',
                'payment_remarks' => 'nullable|string|max:1000',
                'created_by' => 'nullable|exists:users,id',
            ]);

            $id = $request->fine_id;
            $item = InvoiceItem::findOrFail($id);

            // Update the fine item
            $item->update([
                'description' => $request->payment_remarks,
                'price' => $request->payable_amount,
                // 'status' => "processed",
                'total_amount' => $request->payable_amount,
            ]);

            // Recalculate invoice total
            $invoice = $item->invoice;
            $newTotal = $invoice->items()->sum('total_amount');

            $invoice->update([
                'total_amount' => $newTotal,
                'remaining_amount' => $newTotal - $invoice->paid_amount,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Fine item and invoice updated successfully',
                'data' => [
                    'invoice_id' => $invoice->id,
                    'new_total' => $newTotal,
                ],
                'errors' => null,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Payment record created and subscription updated successfully.',
                'data' => [
                    'payment_id' => $payment->id,
                    'subscription_id' => $subscription->id,
                ],
                'errors' => null,
            ], 201);
        } catch (ValidationException $e) {
            \Log::error("Validation Error: " . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'data' => null,
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            \Log::error("Model Not Found: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found.',
                'data' => null,
                'errors' => null,
            ], 404);
        } catch (Exception $e) {
            \Log::error("Server Error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred during payment processing.',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }


    // public function getResidentFines(Request $request)
    // {
    //     try {

    //         $residentId = $request->input('resident_id');

    //         // Fetch all invoices for the resident
    //         $invoices = Invoice::where('resident_id', $residentId)->pluck('id');

    //         // Fetch all fine-type items across those invoices
    //         $fines = InvoiceItem::with('invoice')
    //             ->where('item_type', 'fine')
    //             ->where(function ($query) {
    //                 $query->where('status', '!=', 'paid')
    //                     ->orWhere('status', 'processed');
    //             })
    //             ->whereHas('invoice', function ($query) {
    //                 $query->where('status', 'pending')
    //                     ->where('type', 'fine');
    //             })
    //             ->get();


    //         return response()->json([
    //             'success' => true,
    //             'resident_id' => $residentId,
    //             'fines' => $fines
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred while fetching payments.',
    //             'data'    => null,
    //             'errors'  => ['exception' => $e->getMessage()]
    //         ], 500);
    //     }
    // }


    public function getResidentFines(Request $request)
    {
        // Log::info($request->all());
        try {
            // $user = $request->user();
            $resident = $request->user()->resident;

            if (!$resident) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resident not found for this user.'
                ]);
            }

            // Log::info('resident' . json_encode($resident));
            // $invoices = Invoice::with(['items' => function ($q) {
            //     $q->where('item_type', 'fine')
            //         ->where(function ($query) {
            //             // $query->where('status', '!=', 'paid');
            //             //  $query->where('item_type', '!=', 'paid');
            //             // ->orWhere('status', 'processed');
            //             // ->orWhere('status', 'pending');
            //         });
            // }])
            //     ->where('resident_id', $resident->id)
            //     ->where('type', 'fine')
            //     ->where('status', 'pending')
            //     ->get();

            $invoices = Invoice::with(['items' => function ($q) {
                $q->where('item_type', 'fine');
            }])
                ->where('resident_id', $resident->id)
                ->where('type', 'fine')
                ->get();   // ⬅ fetch all fines (paid + pending) for summary

            // ---------------------------------------
            // 🔥 SMART SUMMARY CALCULATION
            // ---------------------------------------

            $totalFinesIncurred = $invoices->sum('total_amount');
            $totalFinesPaid     = $invoices->sum('paid_amount');
            $pendingFines       = $invoices->sum('remaining_amount');

            $lastFine = $invoices
                ->sortByDesc('created_at')
                ->first();

            $lastFineDate = $lastFine
                ? \Carbon\Carbon::parse($lastFine->created_at)->format('d M Y')
                : null;

            // $resInfo = [
            //     'resName' => $resident->name,
            //     'email' => $resident->email,
            //     'phone' => $resident->phone,
            //     'hostel' => $resident->hostel->name . ' , ' . $resident->room->room_number,
            // ];
            // $resInfo = $resident->name;
            // $email = $resident->email;
            // $phone = $resident->phone;
            // $hostel = $resident->hostel->name . ' , ' . $resident->room->room_number;


            // ---------------------------------------
            // 🔥 FORMAT INVOICES FOR LIST DISPLAY
            // ---------------------------------------

            // Only show pending in table list
            $pendingInvoices = $invoices->where('status', 'pending');

            // ✅ Build a clean formatted response
            $formatted = $pendingInvoices->map(function ($invoice) {
                // ✅ Extract remarks from the FIRST item (or merge all)
                // ✅ Decode invoice-level remarks
                $remarks = $pendingInvoices->remarks ?? [];   // cast to array if using $casts

                // ✅ Flatten remarks: admin → text only
                $flatRemarks = [];
                foreach ($remarks as $role => $data) {
                    $flatRemarks[$role] = $data['text'] ?? null;
                }

                // If multiple roles exist, join them with comma
                $remarksString = collect($remarks)
                    // ->map(fn($data) => $data['text'] ?? '')
                    ->map(function ($data, $role) {
                        return $role . ': ' . ($data['text'] ?? '');
                    })
                    ->filter()
                    ->implode(', ');


                // ✅ Collect item types
                $itemTypes = $invoice->items->pluck('item_type')->filter()->implode(', ');

                // ✅ Collect descriptions (fee_type or description field)
                $descriptions = $invoice->items->pluck('description')->filter()->implode(', ');


                return [
                    'invoice_id' => $invoice->id,
                    'resident_id' => $invoice->resident_id,
                    'invoice_number' => $invoice->invoice_number ?? null,
                    'item_type' => $itemTypes,
                    'descriptions' => $descriptions,
                    'total_amount' => $invoice->total_amount ?? 0,
                    'paid_amount' => $invoice->paid_amount ?? 0,
                    'balance' => $invoice->remaining_amount ?? 0,
                    'status' => $invoice->status ?? '',
                    'created_at' => $invoice->created_at ? \Carbon\Carbon::parse($invoice->created_at)->format('d M Y, h:i A')
                        : null,

                    // ✅ flattened remarks here
                    // 'remarks' => $flatRemarks,
                    // ✅ stringify remarks
                    // 'remarks' => $remarksString,

                    // ✅ Format each fine item
                    'items' => $invoice->items->map(function ($item) {
                        $remarks = $item->remarks ?? []; // already cast to array if using $casts

                        return [
                            'id' => $item->id,
                            'item_type' => $item->item_type,
                            'desc' => $item->description,
                            'remarks' => $item->remarks,
                            'amount' => $item->price,
                            'total' => $item->total_amount,
                        ];
                    }),
                ];
            });

            // ---------------------------------------
            // 🔥 FORMAT INVOICES FOR PAID LIST DISPLAY
            // ---------------------------------------

            // Only show pending in table list
            $paidInvoices = $invoices->where('status', 'paid');

            // ✅ Build a clean formatted response
            $paidFormatted = $paidInvoices->map(function ($invoice) {

                // ---------------------------------------------
                // ✅ 1. INVOICE-LEVEL REMARKS (safe, flattened)
                // ---------------------------------------------
                $remarks = $invoice->remarks ?? []; // using $casts on model

                $remarksString = collect($remarks)
                    ->map(function ($data, $role) {
                        return $role . ': ' . ($data['text'] ?? '');
                    })
                    ->filter()
                    ->implode(', '); // Final string — admin: text, superadmin: text


                // ---------------------------------------------
                // ✅ 2. COLLECT ITEM TYPES + DESCRIPTIONS
                // ---------------------------------------------
                $itemTypes = $invoice->items->pluck('item_type')->filter()->implode(', ');
                $descriptions = $invoice->items->pluck('description')->filter()->implode(', ');


                // ---------------------------------------------
                // ✅ 3. ORDERS (NOT ARRAY) — LATEST 5 ONLY
                // ---------------------------------------------
                // $ordersString = $invoice->orders
                //     ->sortByDesc('created_at')
                //     ->take(5)                     // only latest 5
                //     ->map(function ($order) {
                //         $details = [];

                //         if ($order->order_number) {
                //             $details[] = "Order#: {$order->order_number}";
                //         }
                //         if ($order->payment_mode) {
                //             $details[] = "Mode: {$order->payment_mode}";
                //         }
                //         if ($order->amount) {
                //             $details[] = "Amt: {$order->amount}";
                //         }
                //         if ($order->created_at) {
                //             $details[] = "Date: " . \Carbon\Carbon::parse($order->created_at)->format('d M Y');
                //         }

                //         return implode(' | ', $details);
                //     })
                //     ->filter()
                //     ->implode(' || '); // final single string

                $latestOrders = $invoice->orders
                    ->sortByDesc('pivot.paid_at')   // sort by payment time
                    ->take(5);                      // only latest 5

                $order_number = $latestOrders->pluck('order_number')->filter()->implode(', ');

                $amount_paid = $latestOrders
                    ->sum(fn($order) => (float) ($order->pivot->amount_paid ?? 0));

                $paid_at      = $latestOrders->pluck('pivot.paid_at')
                    ->filter()
                    ->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M Y, H i A'))
                    ->implode(', ');

                $payment_mode = $latestOrders
                    ->map(fn($order) => optional($order->transaction)->payment_mode)
                    ->filter()
                    ->unique()
                    ->implode(', ');
                $transaction_ids = $latestOrders
                    ->map(fn($order) => optional($order->transaction)->transaction_id)
                    ->filter()
                    ->implode(', ');



                $resident = $invoice->resident;

                // ---------------------------------------------
                // RETURN FINAL STRUCTURE
                // ---------------------------------------------
                return [
                    'invoice_id'    => $invoice->id,
                    'resident_id'   => $invoice->resident_id,
                    'invoice_number' => $invoice->invoice_number ?? null,

                    'item_type'     => strtoUpper($itemTypes),
                    'descriptions'  => strtoUpper($descriptions),

                    'total_amount'  => $invoice->total_amount ?? 0,
                    'paid_amount'   => $invoice->paid_amount ?? 0,
                    'balance'       => $invoice->remaining_amount ?? 0,
                    'status'        => $invoice->status ?? '',

                    // Orders (string, not array, latest 5)
                    'order_number' => $order_number,
                    'paid_at' => $paid_at,
                    'amount_paid' => $amount_paid,
                    'payment_mode' => $payment_mode,
                    'transaction_id' => $transaction_ids,
                    'invoice_date' => $invoice->created_at
                        ? \Carbon\Carbon::parse($invoice->created_at)->format('d M Y')
                        : null,

                    'resident_name' => $resident->name,
                    'email' => $resident->email,
                    'phone' => $resident->phone,
                    'hostel' => $resident->hostel->name . ' , ' . $resident->room->room_number,
                    // Invoice Remarks (single string)
                    'remarks' => $remarksString,
 
                    // Fine items
                    'items' => $invoice->items->map(function ($item) {

                        return [
                            'id'        => $item->id,
                            'item_type' => $item->item_type,
                            'desc'      => $item->description,
                            'remarks'   => $item->remarks, // already JSON cast
                            'amount'    => $item->price,
                            'total'     => $item->total_amount,
                        ];
                    }),

                ];
            });


            return response()->json([
                'success' => true,
                'invoices' => $formatted,
                'paidFines' => $paidFormatted,
                'summary' => [
                    'total_fines_incurred' => $totalFinesIncurred,
                    'pending_fines'        => $pendingFines,
                    'total_fines_paid'     => $totalFinesPaid,
                    'last_fine_date'       => $lastFineDate,
                ],
                // 'resInfo' => $resInfo,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching fines.',
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }
}
