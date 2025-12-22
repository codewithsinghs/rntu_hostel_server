<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bed;
use App\Helpers\Helper;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\Accessory;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use App\Models\GuestAccessory;
use App\Models\StudentAccessory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\User;
use Illuminate\Validation\ValidationException;



class StudentAccessoryController extends Controller
{

    public function showPaymentForm($resident_id, $student_accessory_id)
    {
        try {
            // Check if the resident exists
            $resident = Resident::findOrFail($resident_id);

            // Check if the accessory exists
            $accessory = StudentAccessory::findOrFail($student_accessory_id);

            return view('resident.make_payment', [
                'resident_id' => $resident_id,
                'accessory_id' => $student_accessory_id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Resident or Accessory not found.',
                'details' => $e->getMessage()
            ], 404);
        }
    }


    public function addAccessory(Request $request)
    {
        try {
            Log::info('add accessory', $request->all());
            $user = $request->user();

            // If not available, fallback to auth-id header
            if (!$user) {
                $authId = $request->header('auth-id');
                if ($authId) {
                    $user = User::findOrFail($authId);
                }
            }

            // Log::info('user' . json_encode($user));
            $resident = Resident::where('user_id', $user->id)->first();

            // Log::info('res' . json_encode($resident));
            // Validate request
            $validated = $request->validate([
                'accessory_head_id' => 'required|exists:accessory,accessory_head_id', // Ensure accessory exists
                'duration' => 'required|in:1 Month,3 Months,6 Months,1 Year' // Validate duration
            ]);

            // Log::info('validated' . json_encode($validated));

            DB::beginTransaction();

            $accessory = Accessory::where('accessory_head_id', $validated['accessory_head_id'])->with('accessoryHead')->firstOrFail();

            // Get current date
            $fromDate = now();

            // Determine the number of months for the selected duration
            $months = match ($validated['duration']) {
                '1 Month' => 1,
                '3 Months' => 3,
                '6 Months' => 6,
                '1 Year' => 12,
            };

            // Calculate `to_date` based on the duration
            $toDate = $fromDate->copy()->addMonths($months);

            // Set a fixed due date (30 days from now)
            $dueDate = now()->addDays($months * 30);

            // Calculate the total amount (price × duration in months)
            $totalAmount = $accessory->price * $months;

            // $nextId = (Invoice::max('id') ?? 0) + 1;
            // $invoiceNumber = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

                        $invoiceNumber = Invoice::generateInvoiceNumber('A'); // or 'SUB', 'ACC'


            //create Invoice
            $invoice = Invoice::create([
                'resident_id' => $resident->id,
                'invoice_number' => $invoiceNumber,
                'invoice_date' => now(),
                'due_date' => $dueDate,
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'remaining_amount' => $totalAmount,
                'description' => 'Accessory Charge',
                'status' => 'Pending',
            ]);

            //create Invoice Items
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_type' => 'accessory',
                'item_id' => $accessory->id,
                'description' => $accessory->accessoryHead->name,
                'price' => $accessory->price,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'month' => $months,
                'total_amount' => $totalAmount,
            ]);

            // Attach accessory to the resident 
            // $studentAccessory = StudentAccessory::create([
            //     'resident_id' => $resident->id,
            //     'accessory_head_id' => $accessory->accessory_head_id,
            //     'price' => $accessory->price,
            //     'total_amount' => $totalAmount, // Store total amount
            //     'from_date' => $fromDate,
            //     'to_date' => $toDate,
            //     'due_date' => $dueDate
            // ]);

            // Automatically create a pending payment record
            // Payment::create([
            //     'resident_id' => $resident->id,
            //     'student_accessory_id' => $studentAccessory->id,
            //     'total_amount' => $totalAmount, // Store total amount
            //     'amount' => 0, // No initial payment
            //     'remaining_amount' => $totalAmount, // Ensure full amount is pending
            //     'payment_status' => 'Pending',
            //     'payment_method' => 'Null',
            //     'due_date' => $dueDate,
            // ]);

            DB::commit();

            return response()->json([
                'message' => 'Accessory added successfully, waiting for payment.',
                'total_amount' => $totalAmount,
                'remaining_amount' => $totalAmount,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'due_date' => $dueDate
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to add accessory.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // public function getRecentAccessoryRequest(Request $request)
    // {
    //     try {
    //         $user = $request->user();

    //         // Fallback to auth-id header
    //         if (!$user) {
    //             $authId = $request->header('auth-id');
    //             if ($authId) {
    //                 $user = User::findOrFail($authId);
    //             }
    //         }

    //         $resident = Resident::where('user_id', $user->id)->firstOrFail();

    //         // Fetch up to 10 most recent accessory invoice items
    //         $recentAccessories = InvoiceItem::where('item_type', 'accessory')
    //             ->whereHas('invoice', function ($q) use ($resident) {
    //                 $q->where('resident_id', $resident->id);
    //             })
    //             ->with(['invoice', 'accessory.accessoryHead'])
    //             ->latest()
    //             ->take(10)
    //             ->get();

    //         if ($recentAccessories->isEmpty()) {
    //             return response()->json([
    //                 'message' => 'No accessory requests found.'
    //             ], 404);
    //         }

    //         // Format response
    //         $data = $recentAccessories->map(function ($item) {
    //             return [
    //                 'accessory_name'   => $item->accessory->accessoryHead->name,
    //                 'price'            => $item->price,
    //                 'total_amount'     => $item->total_amount,
    //                 'from_date' => $item->from_date
    //                     ? Carbon::parse($item->from_date)->timezone('Asia/Kolkata')->format('d-M-Y')
    //                     : null,

    //                 'to_date' => $item->to_date
    //                     ? Carbon::parse($item->to_date)->timezone('Asia/Kolkata')->format('d-M-Y')
    //                     : null,
    //                 'month'            => $item->month,
    //                 'invoice_number'   => $item->invoice->invoice_number,
    //                 'status'           => $item->invoice->status,
    //                 // 'created_at'       => $item->created_at,
    //                 // 'created_at'       => $item->created_at->timezone('Asia/Kolkata')->format('d-M-Y h:i A'),
    //                 'created_at'       => $item->created_at->timezone('Asia/Kolkata')->format('d-M-Y'),
    //             ];
    //         });

    //         return response()->json([
    //             'message' => 'Recent accessory requests fetched successfully.',
    //             'data' => $data
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => 'Failed to fetch recent accessory requests.',
    //             'details' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function getRecentAccessoryRequest(Request $request)
    {
        try {
            $user = $request->user();

            // Fallback to auth-id header
            if (!$user) {
                $authId = $request->header('auth-id');
                if ($authId) {
                    $user = User::findOrFail($authId);
                }
            }

            $resident = Resident::where('user_id', $user->id)->firstOrFail();

            // Fetch up to 10 most recent accessory invoice items
            $recentAccessories = InvoiceItem::where('item_type', 'accessory')
                ->whereHas('invoice', function ($q) use ($resident) {
                    $q->where('resident_id', $resident->id);
                })
                ->with(['invoice', 'accessory.accessoryHead'])
                ->latest()
                ->take(10)
                ->get();

            if ($recentAccessories->isEmpty()) {
                return response()->json([
                    'message' => 'No accessory requests found.'
                ], 404);
            }

            // ✅ EXTRA SUMMARY DATA
            // $accessoryCount = InvoiceItem::where('item_type', 'accessory')
            //     ->whereHas('invoice', function ($q) use ($resident) {
            //         $q->where('resident_id', $resident->id);
            //     })
            //     ->count();
            $accessoryCount = InvoiceItem::where('item_type', 'accessory')
                ->whereHas('invoice', function ($q) use ($resident) {
                    $q->where('resident_id', $resident->id)
                        ->where('status', 'paid');
                })
                ->count();


            $pendingRequests = Invoice::where('resident_id', $resident->id)
                ->where('status', 'pending')
                ->count();

            $approvedRequests = Invoice::where('resident_id', $resident->id)
                ->where('status', 'approved')
                ->count();

            $pendingPayments = Invoice::where('resident_id', $resident->id)
                ->where('status', 'pending')
                ->sum('remaining_amount');


            // Format response
            $data = $recentAccessories->map(function ($item) {
                return [
                    'accessory_name'   => $item->accessory->accessoryHead->name,
                    'price'            => $item->price,
                    'total_amount'     => $item->total_amount,
                    'from_date' => $item->from_date
                        ? Carbon::parse($item->from_date)->timezone('Asia/Kolkata')->format('d-M-Y')
                        : null,
                    'to_date' => $item->to_date
                        ? Carbon::parse($item->to_date)->timezone('Asia/Kolkata')->format('d-M-Y')
                        : null,
                    'month'            => $item->month,
                    'invoice_number'   => $item->invoice->invoice_number,
                    'status'           => $item->invoice->status,
                    'created_at'       => $item->created_at->timezone('Asia/Kolkata')->format('d-M-Y'),
                ];
            });

            return response()->json([
                'message' => 'Recent accessory requests fetched successfully.',
                'summary' => [
                    'accessory_count'   => $accessoryCount,
                    'pending_requests'  => $pendingRequests,
                    'approved_requests' => $approvedRequests,
                    'pending_payments'  => $pendingPayments,
                ],
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch recent accessory requests.',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    public function payAccessory(Request $request, $invoice_id, $resident_id = null)
    {
        Log::info("paid for acc" . json_encode($request->all()));

        // $resident_id = Helper::get_resident_details($request->header('auth-id'))->id;
        if ($request->header('auth-id')) {
            Log::info("here");
            $resident_id = Helper::get_resident_details($request->header('auth-id'))->id;
        } else {
            Log::info("there");
            // $resident_id = Resident::where('user_id', $request->input('user_id'))->value('id');
            $resident_id = $resident_id;
        }

        // Validate payment request
        Log::info("paid acc resident_id " . json_encode($resident_id));
        $validated = $request->validate([
            'transaction_id' => 'nullable|unique:payments,transaction_id',
            'payment_method' => 'required|in:Cash,UPI,Bank Transfer,Card,Other',
            'amount' => 'required|numeric|min:1',
        ]);

        Log::info("paid for acc validated" . json_encode($validated));
        DB::beginTransaction();
        try {

            $resident = Resident::findOrFail($resident_id);

            Log::info("paid for acc resident" . json_encode($resident));
            Log::info("paid for invoice Number " . json_encode($invoice_id));
            // Find the accessory record using `invoice_id`
            // $invoice = Invoice::where('resident_id', $resident_id)
            //     ->find($invoice_id);
            $invoice = Invoice::where('resident_id', $resident_id)
                ->where('invoice_number', $invoice_id)
                ->first();

            Log::info("paid acc resident_id " . json_encode($invoice));
            // Ensure total amount is derived correctly
            $totalAmount = $invoice->total_amount;

            // Get total payments made so far for this accessory
            // $totalPaid = Payment::where('invoice_id', $invoice->id)->sum('amount');

            // Calculate remaining balance
            $remainingBalance = $invoice->remaining_amount;

            // Ensure payment does not exceed the remaining balance
            if ($validated['amount'] > $remainingBalance) {
                return response()->json([
                    'error' => 'Amount exceeds the remaining balance.',
                    'total_amount' => $totalAmount,
                    'remaining_balance' => $remainingBalance
                ], 400);
            }

            // Calculate new remaining amount after payment
            $newRemainingAmount = max($remainingBalance - $validated['amount'], 0);

            // Determine new payment status
            $paymentStatus = ($newRemainingAmount == 0) ? 'paid' : 'Partial';

            // Record the payment
            Invoice::where('id', $invoice->id)->update([
                'paid_amount' => $invoice->paid_amount + $validated['amount'],
                'remaining_amount' => $newRemainingAmount,
                'remarks' => 'Accessory Payment',
                'status' => $paymentStatus,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Accessory payment recorded successfully.',
                'transaction_id' => $validated['transaction_id'],
                'remaining_balance' => $newRemainingAmount
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process payment.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    //getResidentInvoices
    public function getResidentInvoices(Request $request, $invoice_id)
    {
        $resident_id = Helper::get_resident_details($request->header('auth-id'))->id;
        try {
            $invoices = Invoice::find($invoice_id);
            return response()->json([
                'status' => 'success',
                'data' => $invoices
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => 'Failed to fetch invoices.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function adminSendAccessoryToResident(Request $request)
    {
        try {
            $request->validate([
                'resident_id' => 'required|exists:residents,id',
                'accessory_head_id' => 'required|exists:accessory_heads,id', // ✅ validate from accessory_heads table
                'duration' => 'required|in:1 Month,3 Months,6 Months,1 Year',
                'remarks' => 'nullable|string',
            ]);

            DB::beginTransaction();
            $resident = Resident::findOrFail($request->resident_id);

            // ✅ Find active accessory using accessory_head_id
            $accessory = Accessory::where('accessory_head_id', $request->accessory_head_id)
                ->with('accessoryHead')
                ->where('is_active', true)
                ->first();

            if (!$accessory) {
                return response()->json([
                    'error' => 'Active accessory not found for the given accessory_head_id'
                ], 404);
            }

            $fromDate = now();

            $months = match ($request->duration) {
                '1 Month' => 1,
                '3 Months' => 3,
                '6 Months' => 6,
                '1 Year' => 12,
            };

            $toDate = $fromDate->copy()->addMonths($months);
            $dueDate = now()->addDays($months * 30);
            $price = $accessory->price;
            $totalAmount = $price * $months;

            // $nextId = (Invoice::max('id') ?? 0) + 1;
            // $invoiceNumber = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
                        $invoiceNumber = Invoice::generateInvoiceNumber('A'); // or 'SUB', 'ACC'

            //create Invoice
            $invoice = Invoice::create([
                'resident_id' => $resident->id,
                'invoice_number' => $invoiceNumber,
                'invoice_date' => now(),
                'due_date' => $dueDate,
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'remaining_amount' => $totalAmount,
                'remarks' => $request->remarks ?? 'Accessory Charge',
                'status' => 'Pending',
            ]);
            //create Invoice Items
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_type' => 'accessory',
                'item_id' => $accessory->id,
                'description' => $accessory->accessoryHead->name,
                'price' => $accessory->price,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'month' => $months,
                'total_amount' => $totalAmount,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Accessory assigned and payment created successfully.',
                'invoice_id' => $invoice->id,
                'payment_status' => 'Pending',
                'total_amount' => $totalAmount,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json([
                'error' => 'Failed to assign accessory or create payment.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }



    public function getAccessories($resident_id)
    {
        try {
            $resident = Resident::with('accessories')->findOrFail($resident_id);

            $total_price = $resident->accessories->sum('pivot.price');

            return response()->json([
                'resident' => $resident,
                'accessories' => $resident->accessories,
                'total_price' => $total_price
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Resident not found.',
                'details' => $e->getMessage()
            ], 404);
        }
    }
}
