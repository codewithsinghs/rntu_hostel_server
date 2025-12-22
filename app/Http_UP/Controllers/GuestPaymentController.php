<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use Carbon\Carbon;
use App\Models\Fee;
use App\Models\Mess;
use App\Models\User;
use App\Models\Guest;
use App\Models\Order;
use App\Helpers\Helper;
use App\Models\Faculty;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\InvoiceItem;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\GuestAccessory;
use App\Models\StudentAccessory;
use App\Services\PaymentHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Services\PaytmPaymentService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class GuestPaymentController extends Controller
{
    protected $paytmService;

    public function __construct(PaytmPaymentService $paytmService)
    {
        $this->paytmService = $paytmService;
    }

    public function guestPayment(Request $request)
    {
        // Log::info('Guest Payment Triggered');
        // Log::info($request->all());

        try {
            $request->validate([
                'guest_id' => 'required|exists:guests,id',
                // 'order_number' => 'required|string|exists:orders,order_number',
                'remarks' => 'nullable|string',
            ]);

            DB::beginTransaction();

            // Fetch guest and order
            $guest = Guest::findOrFail($request->guest_id);
            // $order = Order::where('order_number', $request->order_number)->firstOrFail();

            // Create user
            $university_id = Faculty::find($guest->faculty_id)->university_id;
            $user = User::create([
                'name' => $guest->name,
                'gender' => $guest->gender,
                'email' => $guest->email,
                'university_id' => $university_id,
                'password' => Hash::make('12345678'),
            ]);
            $residentRole = Role::where('name', 'resident')->firstOrFail();
            $user->roles()->attach($residentRole->id, ['model_type' => User::class]);

            // Create resident
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
                'created_by' => $user->id,
            ]);
            $guest->update(['status' => 'paid']);

            // Create mess record
            $fromDate = Carbon::now();
            $toDate = $fromDate->copy()->addMonths(3);
            $dueDate = $toDate->copy();

            $mess = Mess::create([
                'user_id' => $user->id,
                'resident_id' => $resident->id,
                'guest_id' => $guest->id,
                'building_id' => $guest->building_id ?? null,
                'university_id' => $guest->university_id ?? null,
                'created_by' => $user->id,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'due_date' => $dueDate,
            ]);

            // // Decode invoice numbers (JSON or fallback to single)
            // try {
            //     $invoiceNumbers = json_decode($order->invoice_number, true);
            //     if (!is_array($invoiceNumbers)) throw new \Exception('Not JSON');
            // } catch (\Exception $e) {
            //     $invoiceNumbers = [$order->invoice_number];
            // }

            // // Fetch invoices and transactions
            // $invoices = Invoice::whereIn('invoice_number', $invoiceNumbers)->get();
            // $transactions = Transaction::where('order_id', $order->id)
            //     ->where('status', 'Completed')
            //     ->get();

            // foreach ($invoices as $invoice) {
            //     $paidAmount = $transactions->where('invoice_id', $invoice->id)->sum('amount');
            //     $remaining = $invoice->total_amount - $paidAmount;

            //     $invoice->update([
            //         'guest_id' => $guest->id,
            //         'resident_id' => $resident->id,
            //         'paid_amount' => $paidAmount,
            //         'remaining_amount' => $remaining,
            //         'status' => $remaining <= 0 ? 'paid' : ($paidAmount > 0 ? 'partial' : 'unpaid'),
            //         'remarks' => $request->remarks,
            //     ]);

            //     foreach ($invoice->items as $item) {
            //         $itemRemarks = $item->remarks ?? [];
            //         $itemRemarks['payment'] = 'Settled via order ' . $order->order_number . ' on ' . now()->toDateString();
            //         $item->update(['remarks' => $itemRemarks]);
            //     }
            // }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment processed and all records updated successfully.',
                'data' => [
                    'resident' => $resident,
                    'mess' => $mess,
                    // 'paid_total' => $invoices->sum('paid_amount'),
                    // 'updated_invoices' => $invoices->pluck('invoice_number'),
                ],
                'errors' => null,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Guest or order not found',
                'errors' => ['model' => ['Resource not found']],
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process payment.',
                'errors' => ['exception' => [$e->getMessage()]],
            ], 500);
        }
    }


    public function subscribePay(Request $request)
    {
        try {
            $request->validate([
                'transaction_id' => 'required|unique:payments,transaction_id',
                'payment_method' => 'required|in:Cash,UPI,Bank Transfer,Card',
                'subscription_id' => 'required|exists:subscriptions,id',
                'amount' => 'required|numeric|min:1'
            ]);

            DB::beginTransaction();

            $subscription = Subscription::findOrFail($request->subscription_id);

            if ($subscription->status === 'Active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscription is already active.',
                    'data' => null,
                    'errors' => ['status' => ['Subscription already active']]
                ], 400);
            }

            $firstPayment = Payment::where('subscription_id', $subscription->id)->first();
            $totalAmount = $firstPayment ? $firstPayment->total_amount : $subscription->total_amount;
            $totalPaid = Payment::where('subscription_id', $subscription->id)->sum('amount');
            $remainingBalance = max($totalAmount - $totalPaid, 0);

            if ($request->amount > $remainingBalance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Amount exceeds the remaining balance.',
                    'data' => [
                        'total_amount' => $totalAmount,
                        'remaining_balance' => $remainingBalance
                    ],
                    'errors' => ['amount' => ['Exceeds balance']]
                ], 400);
            }

            $newRemainingAmount = max($remainingBalance - $request->amount, 0);
            $paymentStatus = $newRemainingAmount == 0 ? 'Completed' : 'Pending';

            Payment::create([
                'resident_id' => $subscription->resident_id,
                'fees_id' => $subscription->fee_id,
                'subscription_id' => $subscription->id,
                'total_amount' => $totalAmount,
                'amount' => $request->amount,
                'remaining_amount' => $newRemainingAmount,
                'transaction_id' => $request->transaction_id,
                'payment_method' => $request->payment_method,
                'payment_status' => $paymentStatus,
            ]);

            if ($newRemainingAmount == 0) {
                $subscription->update(['status' => 'Active']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully.',
                'data' => [
                    'payment_status' => $paymentStatus,
                    'subscription_status' => $subscription->status,
                    'remaining_balance' => $newRemainingAmount
                ],
                'errors' => null
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'data' => null,
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found',
                'data' => null,
                'errors' => ['subscription' => ['Not found']]
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process payment.',
                'data' => null,
                'errors' => ['exception' => [$e->getMessage()]]
            ], 500);
        }
    }




    public function makePayment(Request $request)
    {
        // Log::info("Got request for users");
        try {
            $request->validate([
                'resident_id' => 'required|exists:residents,id',
                'amount' => 'required|numeric|min:1',
                'payment_method' => 'required|in:Cash,UPI,Bank Transfer,Card,Other',
                'payment_for' => 'required|in:subscription,accessory,fee',
                'subscription_id' => 'nullable|exists:subscriptions,id',
                'student_accessory_id' => 'nullable|exists:student_accessory,id',
                'fees_id' => 'nullable|exists:fees,id',
            ]);

            return DB::transaction(function () use ($request) {
                $residentId = $request->resident_id;
                $amountPaid = $request->amount;
                $paymentMethod = $request->payment_method;
                $paymentFor = $request->payment_for;
                $totalAmount = 0;
                $relatedId = null;

                if ($paymentFor === 'subscription' && $request->subscription_id) {
                    $subscription = Subscription::find($request->subscription_id);
                    $totalAmount = Fee::find($subscription->fee_id)->amount;
                    $relatedId = $subscription->id;
                } elseif ($paymentFor === 'accessory' && $request->student_accessory_id) {
                    $accessory = StudentAccessory::find($request->student_accessory_id);
                    $totalAmount = $accessory->price;
                    $relatedId = $accessory->id;
                } elseif ($paymentFor === 'fee' && $request->fees_id) {
                    $fee = Fee::find($request->fees_id);
                    $totalAmount = $fee->amount;
                    $relatedId = $fee->id;
                }

                $previousPayments = Payment::where('resident_id', $residentId)
                    ->where($paymentFor . '_id', $relatedId)
                    ->sum('amount');
                $remainingAmount = max($totalAmount - ($previousPayments + $amountPaid), 0);

                $payment = Payment::create([
                    'resident_id' => $residentId,
                    $paymentFor . '_id' => $relatedId,
                    'total_amount' => $totalAmount,
                    'amount' => $amountPaid,
                    'remaining_amount' => $remainingAmount,
                    'payment_method' => $paymentMethod,
                    'payment_status' => $remainingAmount > 0 ? 'Pending' : 'Completed',
                    'transaction_id' => $request->transaction_id,
                    'created_by' => auth()->id(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment successful',
                    'data' => $payment,
                    'errors' => null
                ], 201);
            });
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'data' => null,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to make payment',
                'data' => null,
                'errors' => ['exception' => [$e->getMessage()]]
            ], 500);
        }
    }


    // public function initiateGuestTransaction(Request $request)
    // {
    //     Log::info('Guest payment request', $request->all());
    //     // ✅ Validate first
    //     $validatedData = $request->validate([
    //         'guest_id' => 'required|exists:guests,id',
    //         'amount'   => 'required|numeric|min:1',
    //         'invoice_number' => 'nullable', // Optional, but will be verified if present
    //     ]);

    //     // Try to get invoice_number from request
    //     $invoiceIds = [];
    //     $invoiceInput = $validatedData['invoice_number'] ?? null;

    //     if ($invoiceInput) {
    //         // Normalize to array
    //         $invoiceArray = is_array($invoiceInput) ? $invoiceInput : [$invoiceInput];

    //         // Fetch valid invoice numbers from DB
    //         $validInvoices = \App\Models\Invoice::where('guest_id', $validatedData['guest_id'])
    //             ->where('total_amount', $validatedData['amount'])
    //             ->pluck('id', 'invoice_number', 'total_amount')
    //             ->toArray();

    //         // Check for tampering
    //         $invalid = array_diff($invoiceArray, $validInvoices);

    //         if (!empty($invalid)) {
    //             abort(403, 'Invalid invoice number detected.');
    //         }
    //         $invoiceIds = array_values($validInvoices);
    //     } else {

    //         // if (!$invoiceInput) {
    //         // Fetch invoice numbers matching guest_id AND amount
    //         $invoiceInput = Invoice::where('guest_id', $validatedData['guest_id'])
    //             ->where('total_amount', $validatedData['amount'])
    //             ->pluck('id', 'invoice_number', 'total_amount')
    //             ->toArray();

    //         // Fallback to random if none found
    //         if (empty($invoiceInput)) {
    //             $invoiceInput = rand(1000, 9999);
    //         }
    //         $invoiceIds = array_values($invoiceInput);
    //     }

    //     // Normalize to JSON
    //     // $invoiceJson = is_array($rawInvoice) ? json_encode($rawInvoice) : json_encode([$rawInvoice]);

    //     // Normalize using your helper
    //     $invoiceJson = Order::prepareInvoiceNumber($invoiceInput);
    //     Log::info('Invoice Number' . json_encode($invoiceJson));

    //     // $order = Helper::createOrder($request);

    //     // // ✅ Call Paytm Service
    //     // $result = $this->paytmService->initiateTransaction(
    //     //     $order->order_id,   // make sure you use the unique Paytm order_id field
    //     //     $validatedData['guest_id'],
    //     //     $validatedData['amount']
    //     // );

    //     $guestId = $validatedData['guest_id'];
    //     // new process
    //     $userType = 'guest';
    //     // Log::info('userType: ' . json_encode($userType));

    //     // Generate order ID
    //     $orderId = Order::generateOrderId($userType);
    //     //  Log::info('orderId: ' . json_encode($orderId));

    //     // metadata
    //     $metaData = $request->input('metadata');
    //     if (!$metaData || !is_array($metaData)) {
    //         $metaData = $validatedData;
    //     }

    //     // Create the order
    //     $order = Order::create([
    //         'guest_id' =>     $guestId,
    //         'order_number'       => $orderId,
    //         'invoice_number' => $invoiceJson,
    //         // 'invoice_number' => Order::prepareInvoiceNumber($validatedData['invoice_number'] ?? rand(1000, 9999)),
    //         // 'user_id'        => $userId ?? null,
    //         // 'resident_id'    => $validatedData['resident_id'] ?? null,
    //         'amount'         => $validatedData['amount'],
    //         'purpose'        => $validatedData['remark'] ?? 'Guest Payment',
    //         'origin_url'     => $request->input('origin_url') ?? null,
    //         'redirect_url'   => $request->input('redirect_url') ?? null,
    //         'callback_route' => $request->input('callback_route') ?? route('guest.payment.callback'),
    //         'status'         => 'pending',
    //         'metadata'       => $metaData ?? [],
    //     ]);

    //     Log::info('order Details: ' . json_encode($order));


    //     // Attach Invoices with Pivot Data
    //     // if (!empty($invoiceIds)) {
    //     //     $order->invoices()->attach($invoiceIds);
    //     // }

    //     $pivotData = [];

    //     foreach ($invoiceIds as $invoiceId) {
    //         $pivotData[$invoiceId] = [
    //             'amount_paid' => 0,
    //             'paid_at' => null,
    //         ];
    //     }

    //     $order->invoices()->attach($pivotData);

    //     $callbackUrl = "guest/payment/callback";
    //     // ✅ Call Paytm Service
    //     $result = $this->paytmService->initiateTransaction(
    //         $order->order_number,   // make sure you use the unique Paytm order_id field
    //         $guestId,
    //         $order->amount,
    //         $callbackUrl
    //     );

    //     // ✅ Format response for frontend
    //     return response()->json([
    //         'success' => true,
    //         'data'    => $result,   // contains txnUrl + body
    //         'order'   => $order,    // optional: return order details also
    //     ], 200);
    // }

    // GuestPaymentController.php

    // public function confirmGuestPayment(Request $request)
    // {
    //     Log::info('guest payment confirm', $request->all());
    //     $validatedData = $request->validate([
    //         'guest_id' => 'required|exists:guests,id',
    //         'amount'   => 'required|numeric|min:1',
    //         'invoice_number' => 'nullable',
    //     ]);

    //     $guest = Guest::findOrFail($validatedData['guest_id']);

    //     // If invoices are given, fetch them
    //     $invoices = [];
    //     if (!empty($validatedData['invoice_number'])) {
    //         $invoices = Invoice::whereIn('invoice_number', (array) $validatedData['invoice_number'])
    //             ->where('guest_id', $guest->id)
    //             ->get();
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'guest'   => $guest->only(['id', 'name', 'email', 'phone']),
    //         'amount'  => $validatedData['amount'],
    //         'invoices' => $invoices,
    //         'purpose' => $request->input('remark', 'Guest Payment'),
    //     ]);
    // }



    public function initiateGuestTransaction(Request $request)
    {
        // Log::info('🔔 Guest payment request received', $request->all());

        try {
            // 1. ✅ Validate input
            $validated = $this->validateGuestTransaction($request);

            // 2. ✅ Resolve invoice(s) and detect tampering
            [$invoiceInput, $invoiceIds] = $this->resolveInvoices($validated);

            // 3. ✅ Generate invoice JSON & Order ID
            $invoiceJson = Order::prepareInvoiceNumber($invoiceInput);
            $orderId     = Order::generateOrderId('guest');

            // 4. ✅ Create order record
            $order = $this->createGuestOrder($validated, $invoiceJson, $orderId, $request);

            // 5. ✅ Attach invoices to pivot
            $this->attachInvoicesToOrder($order, $invoiceIds);

            // 6. ✅ Call payment service
            $callbackUrl = $request->input('callback_route') ?? route('guest.payment.callback');
            $result = $this->paytmService->initiateTransaction(
                $order->order_number,
                $validated['guest_id'],
                $order->amount,
                $callbackUrl
            );

            // 7. ✅ Log confirmation record (for future reference)
            // Log::info('✅ Guest order initiated', [
            //     'order_number'   => $order->order_number,
            //     'amount'         => $order->amount,
            //     'invoice_numbers' => $invoiceInput,
            //     'redirect_url'   => $order->redirect_url,
            //     'callback_url'   => $callbackUrl
            // ]);

            // 8. ✅ Send response
            return response()->json([
                'success' => true,
                'message' => 'Guest payment transaction initiated successfully.',
                'data'    => $result,   // txnUrl + body
                'order'   => $order,    // full order details for record keeping
            ], 200);
        } catch (\Throwable $e) {
            Log::error('❌ Guest payment initiation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->respondWithError(
                'Unable to initiate payment transaction. Please try again later.',
                500,
                'payment_initiation_failed',
                ['exception' => $e->getMessage()]
            );
        }
    }

    protected function validateGuestTransaction(Request $request): array
    {
        return $request->validate([
            'guest_id'       => 'required|exists:guests,id',
            'amount'         => 'required|numeric|min:1',
            'invoice_number' => 'nullable',
            'remark'         => 'nullable|string|max:255',
            'origin_url'     => 'nullable|url',
            'redirect_url'   => 'nullable|url',
            'callback_route' => 'nullable|string',
            'metadata'       => 'nullable|array',
        ]);
    }

    protected function resolveInvoices(array $validated): array
    {
        $invoiceInput = $validated['invoice_number'] ?? null;
        $invoiceIds   = [];

        if ($invoiceInput) {
            $invoiceArray = is_array($invoiceInput) ? $invoiceInput : [$invoiceInput];

            $validInvoices = \App\Models\Invoice::where('guest_id', $validated['guest_id'])
                ->where('total_amount', $validated['amount'])
                ->pluck('id', 'invoice_number')
                ->toArray();

            $invalid = array_diff($invoiceArray, array_keys($validInvoices));
            if (!empty($invalid)) {
                throw new \Exception('Invalid invoice number detected: ' . implode(',', $invalid));
            }

            $invoiceIds   = array_values($validInvoices);
            $invoiceInput = $invoiceArray;
        } else {
            $invoiceInput = \App\Models\Invoice::where('guest_id', $validated['guest_id'])
                ->where('total_amount', $validated['amount'])
                ->pluck('id', 'invoice_number')
                ->toArray();

            if (empty($invoiceInput)) {
                $invoiceInput = [rand(1000, 9999)];
            }

            $invoiceIds = array_values($invoiceInput);
        }

        return [$invoiceInput, $invoiceIds];
    }

    protected function createGuestOrder(array $validated, $invoiceJson, string $orderId, Request $request): Order
    {
        $metaData = $request->input('metadata');
        if (!$metaData || !is_array($metaData)) {
            $metaData = $validated;
        }

        return Order::create([
            'guest_id'       => $validated['guest_id'],
            'order_number'   => $orderId,
            'invoice_number' => $invoiceJson,
            'amount'         => $validated['amount'],
            'purpose'        => $validated['remark'] ?? 'Guest Payment',
            'origin_url'     => $request->input('origin_url'),
            'redirect_url'   => $request->input('redirect_url'),
            'callback_route' => $request->input('callback_route') ?? route('guest.payment.callback'),
            'status'         => 'pending',
            'metadata'       => $metaData,
        ]);
    }

    protected function attachInvoicesToOrder(Order $order, array $invoiceIds): void
    {
        $pivotData = [];
        foreach ($invoiceIds as $invoiceId) {
            $pivotData[$invoiceId] = [
                'amount_paid' => 0,
                'paid_at'     => null,
            ];
        }
        $order->invoices()->attach($pivotData);
    }

    private function respondWithError( string $message,int $statusCode = 500, ?string $errorType = null,array $extra = [] ) 
    {
        Log::error('Payment error', array_merge([
            'type'    => $errorType,
            'message' => $message,
        ], $extra));

        return response()->json([
            'success' => false,
            'message' => $message,
            'error'   => $errorType,
        ], $statusCode);
    }




    // public function guestPayCallback(Request $request)
    // {
    //     $orderId = null; // declare early for logging

    //     try {
    //         Log::info("Paytm callback received", $request->all());

    //         // Verify signature / response from gateway
    //         $result = $this->paytmService->verifyCallback($request);
    //         Log::info('Verify result', $result);

    //         // 1️⃣ Invalid signature
    //         if (!$result['valid']) {
    //             $orderId = $result['data']['ORDERID'] ?? null;

    //             Log::warning('Invalid Paytm signature', [
    //                 'order_id' => $orderId,
    //                 'payload'  => $result['data'] ?? [],
    //             ]);

    //             return $this->respondWithError(
    //                 'Invalid payment response. Please try again.',
    //                 $orderId,
    //                 $request
    //             );
    //         }

    //         $data    = $result['data'] ?? [];
    //         $orderId = $data['ORDERID'] ?? null;
    //         $status  = $data['STATUS'] ?? 'UNKNOWN';

    //         // 2️⃣ Fetch order with invoices
    //         $order = Order::where('order_number', $orderId)
    //             ->with('invoices')
    //             ->first();

    //         if (!$order) {
    //             Log::error('Order not found', ['order_id' => $orderId]);

    //             return $this->respondWithError(
    //                 'Order not found. Please contact support.',
    //                 $orderId,
    //                 $request
    //             );
    //         }

    //         Log::info("Order loaded", ['order' => $order->toArray()]);
    //         Log::info("Payment status: {$status}", ['full_data' => $data]);

    //         // 3️⃣ Handle explicit failure
    //         if ($status === 'TXN_FAILURE') {
    //             Log::info('Transaction failed', [
    //                 'order_id' => $order->id,
    //                 'reason'   => $data['RESPMSG'] ?? 'Unknown failure',
    //             ]);

    //             return $this->respondWithError(
    //                 $data['RESPMSG'] ?? 'Transaction failed. Please retry or check with your bank.',
    //                 $order->id,
    //                 $request
    //             );
    //         }

    //         // 4️⃣ Record transaction
    //         $transaction = Transaction::create([
    //             'order_id'         => $order->id,
    //             'txn_id'           => $data['TXNID']       ?? null,
    //             'status'           => $status,
    //             'bank_txn_id'      => $data['BANKTXNID']   ?? null,
    //             'txn_amount'       => $data['TXNAMOUNT']   ?? null,
    //             'payment_mode'     => $data['PAYMENTMODE'] ?? null,
    //             'bank_name'        => $data['BANKNAME']    ?? null,
    //             'currency'         => $data['CURRENCY']    ?? null,
    //             'm_id'             => $data['MID']         ?? null,
    //             'response_code'    => $data['RESPCODE']    ?? null,
    //             'response_message' => $data['RESPMSG']     ?? null,
    //             'response_payload' => json_encode($data),
    //         ]);
    //         Log::info('Transaction recorded successfully', $transaction->toArray());

    //         // 5️⃣ On success, update order + invoices
    //         if ($transaction->status === 'TXN_SUCCESS') {
    //             Log::info("Processing successful payment", ['order_id' => $orderId]);

    //             $order->update(['status' => 'paid']);

    //             foreach ($order->invoices as $invoice) {
    //                 $pivotPaid = (float) $order->amount; // distribute properly if multiple invoices

    //                 // Update pivot
    //                 $order->invoices()->updateExistingPivot($invoice->id, [
    //                     'amount_paid' => $pivotPaid,
    //                     'paid_at'     => now(),
    //                 ]);

    //                 // Update invoice
    //                 $newPaid = (float) $invoice->paid_amount + $pivotPaid;
    //                 $remaining = max($invoice->total_amount - $newPaid, 0);

    //                 $invoice->update([
    //                     'paid_amount'      => $newPaid,
    //                     'remaining_amount' => $remaining,
    //                     'status'           => $remaining <= 0 ? 'paid' : 'partial',
    //                 ]);

    //                 Log::info("Invoice updated", [
    //                     'invoice_id' => $invoice->id,
    //                     'paid'       => $newPaid,
    //                     'remaining'  => $remaining,
    //                 ]);
    //             }

    //             // Fire internal payment handling (optional)
    //             try {
    //                 $txnId = $transaction->txn_id ?? rand(1000, 9999);

    //                 $this->guestPayment(new Request([
    //                     'guest_id'       => $order->guest_id,
    //                     'transaction_id' => $txnId,
    //                     'payment_method' => $transaction->payment_mode ?? 'Other',
    //                     'remarks'        => 'Paid via Paytm callback',
    //                 ]));

    //                 Log::info("Post-payment handler executed", ['order_id' => $orderId]);
    //             } catch (\Exception $ex) {
    //                 Log::error("Post-payment handler failed", [
    //                     'order_id' => $orderId,
    //                     'error'    => $ex->getMessage(),
    //                 ]);
    //             }
    //         } else {
    //             Log::warning("Payment pending or failed at gateway", [
    //                 'order_id' => $orderId,
    //                 'status'   => $transaction->status,
    //             ]);
    //         }

    //         // 6️⃣ Redirect to frontend with success
    //         return redirect()->away(config('app.frontend_url') . '/guest/payment/reciept?' . http_build_query([
    //             'order_id' => $data['ORDERID']   ?? null,
    //             'txn_id'   => $data['TXNID']     ?? null,
    //             'amount'   => $data['TXNAMOUNT'] ?? null,
    //             'status'   => 'success',
    //         ]));
    //     } catch (Throwable $e) {
    //         Log::critical('Unexpected error during Paytm callback', [
    //             'order_id'  => $orderId,
    //             'exception' => $e->getMessage(),
    //             'trace'     => $e->getTraceAsString(),
    //             'payload'   => $request->all(),
    //         ]);

    //         return $this->respondWithError(
    //             'Something went wrong. Please try again later.',
    //             $orderId,
    //             $request
    //         );
    //     }
    // }

    // public function guestPayCallback(Request $request)
    // {
    //     $orderId = null; // Declare early for consistent logging

    //     try {
    //         Log::info("🔔 Paytm callback received", ['payload' => $request->all()]);

    //         // Step 1: Verify signature
    //         $result = $this->paytmService->verifyCallback($request);
    //         Log::info('✅ Callback verification result', $result);

    //         if (!$result['valid']) {
    //             $orderId = $result['data']['ORDERID'] ?? null;
    //             Log::warning('❌ Invalid Paytm signature', ['order_id' => $orderId]);

    //             return $this->respondWithError(
    //                 'Invalid payment response. Please try again.',
    //                 $orderId,
    //                 $request,
    //                 'signature_invalid'
    //             );
    //         }

    //         // Step 2: Locate order
    //         $orderId = $result['data']['ORDERID'] ?? null;
    //         $order   = Order::where('order_number', $orderId)->with('invoices')->first();

    //         if (!$order) {
    //             Log::error('❌ Order not found', ['order_id' => $orderId]);
    //             return $this->respondWithError(
    //                 'Order not found. Please contact support.',
    //                 $orderId,
    //                 $request,
    //                 'order_not_found'
    //             );
    //         }
    //         Log::info('✅ Order located', ['order' => $order->id]);

    //         // Step 3: Check transaction status
    //         $status = $result['data']['STATUS'] ?? 'UNKNOWN';
    //         Log::info("ℹ️ Payment status received: {$status}", ['order_id' => $order->id]);

    //         if ($status === 'TXN_FAILURE') {
    //             Log::warning('❌ Transaction failed', [
    //                 'order_id' => $order->id,
    //                 'reason'   => $result['data']['RESPMSG'] ?? 'Unknown failure'
    //             ]);

    //             return $this->respondWithError(
    //                 $result['data']['RESPMSG'] ?? 'Transaction failed. Please retry or check with your bank.',
    //                 $order->id,
    //                 $request,
    //                 'txn_failure'
    //             );
    //         }

    //         // Step 4: Record transaction
    //         $transaction = Transaction::create([
    //             'order_id'          => $order->id,
    //             'txn_id'            => $result['data']['TXNID'] ?? null,
    //             'status'            => $status,
    //             'bank_txn_id'       => $result['data']['BANKTXNID'] ?? null,
    //             'txn_amount'        => $result['data']['TXNAMOUNT'] ?? null,
    //             'payment_mode'      => $result['data']['PAYMENTMODE'] ?? null,
    //             'bank_name'         => $result['data']['BANKNAME'] ?? null,
    //             'currency'          => $result['data']['CURRENCY'] ?? null,
    //             'm_id'              => $result['data']['MID'] ?? null,
    //             'response_code'     => $result['data']['RESPCODE'] ?? null,
    //             'response_message'  => $result['data']['RESPMSG'] ?? null,
    //             'response_payload'  => json_encode($result['data']),
    //         ]);
    //         Log::info('✅ Transaction recorded', ['transaction_id' => $transaction->id]);

    //         // Step 5: Handle success
    //         if ($transaction->status === 'TXN_SUCCESS') {
    //             Log::info("💰 Processing successful payment", ['order_id' => $orderId]);

    //             $order->update(['status' => 'paid']);

    //             foreach ($order->invoices as $invoice) {
    //                 $pivotPaid = (float) $order->amount; // Could be improved if multiple invoices
    //                 $order->invoices()->updateExistingPivot($invoice->id, [
    //                     'amount_paid' => $pivotPaid,
    //                     'paid_at'     => now(),
    //                 ]);

    //                 $newPaidAmount = (float) $invoice->paid_amount + $pivotPaid;
    //                 $remaining     = (float) $invoice->total_amount - $newPaidAmount;

    //                 $invoice->update([
    //                     'paid_amount'      => $newPaidAmount,
    //                     'remaining_amount' => max($remaining, 0),
    //                     'status'           => $remaining <= 0 ? 'paid' : 'partial',
    //                 ]);

    //                 Log::info("📄 Invoice updated", [
    //                     'invoice_id' => $invoice->id,
    //                     'paid'       => $newPaidAmount,
    //                     'remaining'  => $remaining
    //                 ]);
    //             }

    //             // Notify guest system
    //             try {
    //                 $txnId = $transaction->txn_id ?? rand(1000, 9999);
    //                 $this->guestPayment(new Request([
    //                     'guest_id'       => $order->guest_id,
    //                     'transaction_id' => $txnId,
    //                     'payment_method' => $transaction->payment_mode ?? 'Other',
    //                     'remarks'        => 'Paid via Paytm callback',
    //                 ]));
    //                 Log::info("📢 Guest payment recorded for guest_id {$order->guest_id}");
    //             } catch (Exception $ex) {
    //                 Log::error("Guest payment handler failed", [
    //                     'order_id' => $orderId,
    //                     'error'    => $ex->getMessage()
    //                 ]);
    //             }
    //         } else {
    //             Log::warning("⚠️ Payment not successful", [
    //                 'order_id' => $orderId,
    //                 'status'   => $transaction->status
    //             ]);
    //         }

    //         // Step 6: Redirect user
    //         return redirect()->away(config('app.frontend_url') . '/guest/payment/reciept?' . http_build_query([
    //             'order_id' => $result['data']['ORDERID'] ?? null,
    //             'txn_id'   => $result['data']['TXNID'] ?? null,
    //             'amount'   => $result['data']['TXNAMOUNT'] ?? null,
    //             'status'   => 'success',
    //         ]));
    //     } catch (Throwable $e) {
    //         Log::critical('💥 Unexpected error during Paytm callback', [
    //             'exception' => $e->getMessage(),
    //             'order_id'  => $orderId,
    //             'payload'   => $request->all(),
    //         ]);

    //         return $this->respondWithError(
    //             'Something went wrong. Please try again later.',
    //             $orderId,
    //             'exception',
    //              $request,
    //         );
    //     }
    // }

    // private function respondWithError(string $message, ?string $orderId, ?array $result, Request $request)
    // {
    //     Log::debug('Error occurred in respondWithError()', [
    //         'order_id' => $orderId,
    //     ]);

    //     Log::critical('Unexpected error during payment callback', [
    //         'exception' => $message,
    //         'order_id'  => $orderId,
    //         'payload'   => $request->all(),
    //     ]);

    //     return redirect()->away(config('app.frontend_url') . '/guest/payment/reciept?' . http_build_query([
    //         'order_id' => $orderId,
    //         'txn_id'   => $result['TXNID']    ?? null,
    //         'amount'   => $result['TXNAMOUNT'] ?? null,
    //         'status'   => 'failed',
    //         'message'  => $message,
    //     ]));
    // }



    public function guestPayCallback(Request $request)
    {
        $orderId = null;

        try {
            // Log::info("🔔 Paytm callback received", ['payload' => $request->all()]);

            // Step 1: Verify signature
            $callbackResult = $this->paytmService->verifyCallback($request);
            // Log::info('✅ Callback verification result', $callbackResult);

            if (!$callbackResult['valid']) {
                return $this->handleCallbackError(
                    'Invalid payment response. Please try again.',
                    $callbackResult['data']['ORDERID'] ?? null,
                    $request,
                    'signature_invalid'
                );
            }

            $orderId = $callbackResult['data']['ORDERID'] ?? null;

            // Step 2: Locate order
            $order = Order::where('order_number', $orderId)->with('invoices')->first();
            if (!$order) {
                return $this->handleCallbackError(
                    'Order not found. Please contact support.',
                    $orderId,
                    $request,
                    'order_not_found'
                );
            }
            // Log::info('✅ Order located', ['order_id' => $order->id]);

            // Step 3: Check transaction status
            $status = $callbackResult['data']['STATUS'] ?? 'UNKNOWN';
            if ($status === 'TXN_FAILURE') {
                return $this->handleCallbackError(
                    $callbackResult['data']['RESPMSG'] ?? 'Transaction failed. Please retry or check with your bank.',
                    $order->id,
                    $request,
                    'txn_failure',
                    $callbackResult['data']
                );
            }

            // Step 4: Record transaction
            $transaction = $this->recordTransaction($order, $callbackResult['data']);

            // Step 5: Handle success
            if ($transaction->status === 'TXN_SUCCESS') {
                $this->processSuccessfulPayment($order, $transaction);
            } else {
                Log::warning("⚠️ Payment not successful", [
                    'order_id' => $order->id,
                    'status'   => $transaction->status
                ]);
            }

            // Step 6: Redirect to receipt
            return $this->redirectWithStatus($callbackResult['data'], 'success');
        } catch (Throwable $e) {
            Log::critical('💥 Unexpected error during Paytm callback', [
                'exception' => $e->getMessage(),
                'order_id'  => $orderId,
                'payload'   => $request->all(),
            ]);

            return $this->handleCallbackError(
                'Something went wrong. Please try again later.',
                $orderId,
                $request,
                'exception'
            );
        }
    }

    /**
     * Record transaction in DB
     */
    private function recordTransaction(Order $order, array $data): Transaction
    {
        $transaction = Transaction::create([
            'order_id'          => $order->id,
            'txn_id'            => $data['TXNID'] ?? null,
            'status'            => $data['STATUS'] ?? null,
            'bank_txn_id'       => $data['BANKTXNID'] ?? null,
            'txn_amount'        => $data['TXNAMOUNT'] ?? null,
            'payment_mode'      => $data['PAYMENTMODE'] ?? null,
            'bank_name'         => $data['BANKNAME'] ?? null,
            'currency'          => $data['CURRENCY'] ?? null,
            'm_id'              => $data['MID'] ?? null,
            'response_code'     => $data['RESPCODE'] ?? null,
            'response_message'  => $data['RESPMSG'] ?? null,
            'response_payload'  => json_encode($data),
        ]);

        // Log::info('✅ Transaction recorded', ['transaction_id' => $transaction->id]);

        return $transaction;
    }

    /**
     * Process successful payment and update related invoices
     */
    private function processSuccessfulPayment(Order $order, Transaction $transaction): void
    {
        $order->update(['status' => 'paid']);

        foreach ($order->invoices as $invoice) {
            $pivotPaid = (float) $order->amount;
            $order->invoices()->updateExistingPivot($invoice->id, [
                'amount_paid' => $pivotPaid,
                'paid_at'     => now(),
            ]);

            $newPaidAmount = (float) $invoice->paid_amount + $pivotPaid;
            $remaining     = (float) $invoice->total_amount - $newPaidAmount;

            $invoice->update([
                'paid_amount'      => $newPaidAmount,
                'remaining_amount' => max($remaining, 0),
                'status'           => $remaining <= 0 ? 'paid' : 'partial',
            ]);

            // Log::info("📄 Invoice updated", [
            //     'invoice_id' => $invoice->id,
            //     'paid'       => $newPaidAmount,
            //     'remaining'  => $remaining
            // ]);
        }

        $this->notifyGuestPayment($order, $transaction);
    }

    /**
     * Notify guest system about successful payment
     */
    private function notifyGuestPayment(Order $order, Transaction $transaction): void
    {
        try {
            $txnId = $transaction->txn_id ?? rand(1000, 9999);
            $this->guestPayment(new Request([
                'guest_id'       => $order->guest_id,
                'transaction_id' => $txnId,
                'payment_method' => $transaction->payment_mode ?? 'Other',
                'remarks'        => 'Paid via Paytm callback',
            ]));
            // Log::info("📢 Guest payment recorded for guest_id {$order->guest_id}");
        } catch (Exception $ex) {
            Log::error("Guest payment handler failed", [
                'order_id' => $order->id,
                'error'    => $ex->getMessage()
            ]);
        }
    }

    /**
     * Handle callback error and redirect
     */
    private function handleCallbackError(string $message, ?string $orderId, Request $request, string $errorCode, ?array $result = null)
    {
        // Log::warning("❌ Callback error: {$errorCode}", [
        //     'order_id' => $orderId,
        //     'message'  => $message,
        //     'payload'  => $request->all(),
        // ]);

        return redirect()->away(config('app.frontend_url') . '/guest/payment/reciept?' . http_build_query([
            'order_id' => $orderId,
            'txn_id'   => $result['TXNID'] ?? null,
            'amount'   => $result['TXNAMOUNT'] ?? null,
            'status'   => 'failed',
            'message'  => $message,
            'code'     => $errorCode,
        ]));
    }

    /**
     * Redirect to frontend receipt on success
     */
    private function redirectWithStatus(array $data, string $status)
    {
        return redirect()->away(config('app.frontend_url') . '/guest/payment/reciept?' . http_build_query([
            'order_id' => $data['ORDERID'] ?? null,
            'txn_id'   => $data['TXNID'] ?? null,
            'amount'   => $data['TXNAMOUNT'] ?? null,
            'status'   => $status,
        ]));
    }



    // 11092025
    public function guestPaymentStatus(Request $request)
    {
        // Log::info('fetching payment Status');
        $authId = $request->header('auth-id'); // Get auth-id from headers
        // Log::alert($authId);

        //  Authenticate guest using token guard
        try {
            // $guest = Guest::findOrFail($authId);
            $guest = Guest::with('accessories')->find($authId);
            // Log::alert($guest);

            // Format the response for frontend
            $accessories = $guest->accessories->map(function ($item) {
                // Log::info('items', $item->toArray());
                return [
                    'name'  => $item->description,
                    'qty'   => $item->qty,
                    'price' => $item->price,
                    'from_date' => $item->from_date,
                    'to_date' => $item->to_date,
                    'total_amount' => $item->total_amount,
                    'is_default' => $item->is_default,
                ];
            });

            Log::alert($accessories);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }


        $order = Order::with(['transactions', 'guest'])
            ->where('order_number', $request->order_id)
            // ->where('guest_id', $guest->id) // ensure guest owns the order
            ->first();

        // Log::info('order Info', $order->toArray());

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // if ($order->guest_id != $authId) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Unauthorized'
        //     ], 401);
        // }

        // Log::info('amount fetched', $feeData);
        $response = [
            'success' => true,
            'status'  => $order->status,
            'txn_id'  => $order->transaction->txn_id ?? $request->txnId,
            'amount'  => $order->transaction->txn_amount ?? $order->amount,
            'order_id' => $order->order_number,
            'guest'   => [
                'sc_n' => $order->guest->scholar_no ?? null,
                'name'  => $order->guest->name ?? null,
                'email' => $order->guest->email ?? null,
                'number' => $order->guest->number ?? null,
                'gender' => $order->guest->gender ?? null,
                'fathers_name' => $order->guest->fathers_name ?? null,
                'mothers_name' => $order->guest->mothers_name ?? null,
                'parent_no' => $order->guest->parent_no ?? null,
                'guardian_name' => $order->guest->local_guardian_name ?? null,
                'guardian_no' => $order->guest->guardian_no ?? null,
                'emergency_no' => $order->guest->emergency_no ?? null,
                'stay_duration' => $order->guest->months ?? null,
                'course' => $order->guest->course ?? null,

            ],
            'accessories' => $accessories,
        ];

        // Log::info($response);

        return response()->json($response);
    }






    public function getInvoiceItems(Request $request)
    {
        try {

            $invoiceId = $request->input('invoice_id');

            $invoice = Invoice::with('items')->findOrFail($invoiceId);

            return response()->json([
                'success' => true,
                'items' => $invoice->items
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'data' => null,
                'errors' => ['exception' => [$e->getMessage()]]
            ], 500);
        }
    }




    public function getAccessoryPendingPayments(Request $request)
    {
        try {
            $resident_id = Helper::get_resident_details($request->header('auth-id'))->id;

            // Subquery to get the latest payment ID for each student_accessory_id
            $latestPaymentId = Payment::where('resident_id', $resident_id)
                ->whereNotNull('student_accessory_id')
                ->max('id'); // Get the max ID for the given resident_id

            // Log::info('Latest Payment ID: ' . $latestPaymentId);


            $formattedPayments = Payment::with([
                'studentAccessory.accessory.accessoryHead',
                'resident.user',
                'resident.guest'
            ])
                ->where('id', $latestPaymentId) // Filter by max ID
                ->where('resident_id', $resident_id)
                ->whereNotNull('student_accessory_id')
                ->where('remaining_amount', '>', 0)
                // ->select('id as payment_id', 'amount', 'remaining_amount', 'student_accessory_id')
                ->with(['studentAccessory.accessoryHead' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->get()
                ->map(function ($payment) {
                    return [
                        'payment_id' => $payment->payment_id,
                        'amount' => $payment->amount,
                        'remaining_amount' => $payment->remaining_amount,
                        'student_accessory_id' => $payment->student_accessory_id,
                        'accessory_name' => $payment->studentAccessory->accessoryHead->name ?? 'N/A',
                        'resident_name' => $payment->resident->user->name ?? 'N/A',
                        'scholar_no' => $payment->resident->guest->scholar_no ?? 'N/A'
                    ];
                });

            // Log::info($formattedPayments);


            if ($formattedPayments->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No pending payments found.',
                    'data' => [],
                    'errors' => null
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pending accessory payments retrieved successfully.',
                'data' => $formattedPayments,
                'errors' => null
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'data' => null,
                'errors' => ['exception' => [$e->getMessage()]]
            ], 500);
        }
    }




    // public function getPendingPayments($resident_id)
    // {
    //     try {
    //         // Validate the resident_id manually
    //         $validator = Validator::make(['resident_id' => $resident_id], [
    //             'resident_id' => 'required|exists:residents,id',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'error' => 'Validation failed',
    //                 'messages' => $validator->errors()
    //             ], 422);
    //         }

    //         // Get the latest payment ID for this resident
    //         $latestPaymentId = Payment::where('resident_id', $resident_id)
    //             ->orderByDesc('id')
    //             ->value('id');

    //         if (!$latestPaymentId) {
    //             return response()->json([
    //                 'message' => 'No payments found for this resident.'
    //             ], 404);
    //         }

    //         // Fetch the latest pending payment with relationships
    //         $payment = Payment::with('resident.user')
    //             ->where('id', $latestPaymentId)
    //             ->where('remaining_amount', '>', 0)
    //             ->first();

    //         if (!$payment) {
    //             return response()->json([
    //                 'message' => 'No pending payments found for this resident.'
    //             ], 404);
    //         }

    //         // Format the response
    //         $response = [
    //             'payment_id'       => $payment->id,
    //             'resident_id'      => $payment->resident_id,
    //             'resident_name'    => optional($payment->resident->user)->name,
    //             'total_amount'     => $payment->total_amount,
    //             'amount_paid'      => $payment->amount,
    //             'remaining_amount' => $payment->remaining_amount,
    //             'payment_method'   => $payment->payment_method,
    //             'payment_status'   => $payment->payment_status,
    //             'due_date'         => $payment->due_date,
    //             'created_at'       => $payment->created_at->toDateTimeString(),
    //         ];

    //         return response()->json([
    //             'pending_payment' => $response
    //         ], 200);
    //     } catch (ModelNotFoundException $e) {
    //         return response()->json([
    //             'error' => 'Resident not found'
    //         ], 404);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'error' => 'An unexpected error occurred.',
    //             'details' => $e->getMessage()
    //         ], 500);
    //     }
    // }



    // public function getAllPendingPayments(Request $request)
    // {
    //     try {
    //         $user = Helper::get_auth_admin_user($request);
    //         $latestPayments = \App\Models\Payment::selectRaw('MAX(id) as latest_id')
    //             ->whereNotNull('resident_id')
    //             ->groupBy('resident_id')
    //             ->pluck('latest_id');

    //         $payments = \App\Models\Payment::with('resident.user')
    //             ->whereIn('id', $latestPayments)
    //             ->whereHas('resident.user', function($query) use ($user) {
    //                 $query->where('university_id', $user->university_id);
    //             })
    //             ->where('remaining_amount', '>', 0)
    //             ->get();

    //         if ($payments->isEmpty()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'No pending payments found for any resident.',
    //                 'data' => null,
    //                 'errors' => null
    //             ], 404);
    //         }

    //         $response = $payments->map(function ($payment) {
    //             return [
    //                 'payment_id'       => $payment->id,
    //                 'resident_id'      => $payment->resident_id,
    //                 'resident_name'    => optional($payment->resident->user)->name,
    //                 'subscription_id'  => $payment->subscription_id,
    //                 'total_amount'     => $payment->total_amount,
    //                 'amount_paid'      => $payment->amount,
    //                 'remaining_amount' => $payment->remaining_amount,
    //                 'payment_method'   => $payment->payment_method,
    //                 'payment_status'   => $payment->payment_status,
    //                 'due_date'         => $payment->due_date,
    //                 'created_at'       => $payment->created_at->toDateTimeString(),
    //             ];
    //         });

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Latest pending payments fetched successfully.',
    //             'data'    => $response,
    //             'errors'  => null
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Something went wrong while fetching pending payments.',
    //             'data' => null,
    //             'errors' => ['exception' => $e->getMessage()]
    //         ], 500);
    //     }
    // }


    public function getAllPendingPayments(Request $request)
    {
        try {
            $user = Helper::get_auth_admin_user($request);
            $latestInvoiceIds = Invoice::selectRaw('id as latest_id')
                ->whereNotNull('resident_id')
                ->orderBy('resident_id')
                ->pluck('latest_id');
            // Log::info('Latest Invoice IDs: ' . $latestInvoiceIds);
            $payments = Invoice::with('resident.user')
                ->whereIn('id', $latestInvoiceIds)
                ->whereHas('resident.user', function ($query) use ($user) {
                    $query->where('university_id', $user->university_id);
                })
                ->where('remaining_amount', '>', 0)
                ->get();

            if ($payments->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No pending payments found for any resident.',
                    'data' => null,
                    'errors' => null
                ], 404);
            }

            $response = $payments->map(function ($payment) {
                return [
                    'payment_id'       => $payment->id,
                    'resident_id'      => $payment->resident_id,
                    'resident_name'    => optional($payment->resident->user)->name,
                    'subscription_id'  => $payment->subscription_id,
                    'total_amount'     => $payment->total_amount,
                    'amount_paid'      => $payment->paid_amount,
                    'remaining_amount' => $payment->remaining_amount,
                    // 'payment_method'   => $payment->payment_method,
                    'payment_status'   => $payment->status,
                    'due_date'         => $payment->due_date,
                    'created_at'       => $payment->created_at->toDateTimeString(),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Latest pending payments fetched successfully.',
                'data'    => $response,
                'errors'  => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching pending payments.',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }



    public function getPaymentsByResident($id)
    {
        $validator = Validator::make(['resident_id' => $id], [
            'resident_id' => 'required|integer|exists:residents,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'data' => null,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payments = DB::table('payments')
                ->select(
                    'payments.transaction_id',
                    'payments.total_amount',
                    'payments.amount',
                    'payments.remaining_amount',
                    'payments.payment_method',
                    'payments.payment_status',
                    'payments.due_date',
                    'payments.remarks',
                    'payments.created_at',
                    'fees.name as fee_head_name',
                    'accessory_heads.name as accessory_name',
                    'subscriptions.subscription_type as subscription_name'
                )
                ->leftJoin('fees', 'payments.fee_head_id', '=', 'fees.id')
                ->leftJoin('subscriptions', 'payments.subscription_id', '=', 'subscriptions.id')
                // Joining sequence: payments -> student_accessory -> accessory -> accessory_heads
                ->leftJoin('student_accessory', 'payments.student_accessory_id', '=', 'student_accessory.id')
                ->leftJoin('accessory', 'student_accessory.accessory_head_id', '=', 'accessory.id')
                ->leftJoin('accessory_heads', 'accessory.accessory_head_id', '=', 'accessory_heads.id')
                ->where('payments.resident_id', $id)
                ->orderBy('payments.created_at', 'desc')
                ->get();

            if ($payments->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No payments found for this resident.',
                    'data' => null,
                    'errors' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payments retrieved successfully.',
                'data'    => $payments,
                'errors'  => null
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching payments with joins: " . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching payments.',
                'data'    => null,
                'errors'  => ['exception' => $e->getMessage()]
            ], 500);
        }
    }


    public function getAllPaymentsByResidentId($residentId)
    {
        try {
            $payments = Payment::with([
                'feeHead',
                'subscription',
                'studentAccessory.accessoryHead'
            ])
                ->where('resident_id', $residentId)
                ->get();

            if ($payments->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No payments found for this resident.',
                    'data'    => null,
                    'errors'  => null
                ], 404);
            }

            $formatted = $payments->map(function ($payment) {
                return [
                    'transaction_id'     => $payment->transaction_id,
                    'total_amount'       => $payment->total_amount,
                    'amount'             => $payment->amount,
                    'remaining_amount'   => $payment->remaining_amount,
                    'payment_method'     => $payment->payment_method,
                    'payment_status'     => $payment->payment_status,
                    'due_date'           => $payment->due_date,
                    'fee_head_name'      => optional($payment->feeHead)->name,
                    'accessory_name'     => optional(optional($payment->studentAccessory)->accessory)->name,
                    'subscription_name'  => optional($payment->subscription)->subscription_type,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Payments fetched successfully.',
                'data'    => $formatted,
                'errors'  => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching payments.',
                'data'    => null,
                'errors'  => ['exception' => $e->getMessage()]
            ], 500);
        }
    }




    public function getAllPayments()
    {
        try {
            $payments = Payment::with([
                'guest',
                'resident',
                'fees',
                'subscription',
                'studentAccessory',
                'createdBy'
            ])
                ->orderBy('created_at', 'desc')
                ->get();

            if ($payments->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No payments found.',
                    'data'    => null,
                    'errors'  => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payments retrieved successfully.',
                'data'    => $payments,
                'errors'  => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching payments.',
                'data'    => null,
                'errors'  => ['exception' => $e->getMessage()]
            ], 500);
        }
    }


    public function showAccessoryPaymentForm(Request $request)
    {
        $residentId = $request->query('resident_id');
        $studentAccessoryId = $request->query('student_accessory_id');

        return view('accountant.accessory_pay', compact('residentId', 'studentAccessoryId'));
    }







    public function initiateResidentPayment(Request $request)
    {
        // Log::info($request->all());
        $userId = $request->header('auth-id') ?? null;
        // Log::info($userId);
        // ✅ Validate first
        $validatedData = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'amount'   => 'required|numeric|min:1',
            // 'user_id' => 'nullable|exists:users,id',
            'invoice_number' => 'nullable|exists:invoices,invoice_number',
            'remark' => 'nullable',
        ]);

        $user = Helper::get_auth_admin_user($request);
        $userType = $user ? 'resident' : 'guest';
        // Log::info('userType: ' . json_encode($userType));

        // Generate order ID
        $orderId = Order::generateOrderId($userType);
        //  Log::info('orderId: ' . json_encode($orderId));
        // $order = Helper::createOrder($request);

        // metadata
        $metaData = $request->input('metadata');
        if (!$metaData || !is_array($metaData)) {
            $metaData = $validatedData;
        }

        // Create the order
        $order = Order::create([
            'order_number'       => $orderId,
            'invoice_number' => $validatedData['invoice_number'] ?? rand(1000, 9999),
            'user_id'        => $userId ?? null,
            'resident_id'    => $validatedData['resident_id'] ?? null,
            'amount'         => $validatedData['amount'],
            'purpose'        => $validatedData['remark'] ?? 'general',
            'origin_url'     => $request->input('origin_url') ?? null,
            'redirect_url'   => $request->input('redirect_url') ?? null,
            'callback_route' => $request->input('callback_route') ?? route('resident.payment.callback'),
            'status'         => 'pending',
            'metadata'       => $metaData ?? [],
        ]);

        // Log::info('order Details: ' . json_encode($order));

        $callbackUrl = "resident/payment/callback";
        // ✅ Call Paytm Service
        $result = $this->paytmService->initiateTransaction(
            $order->order_id,   // make sure you use the unique Paytm order_id field
            $userId,
            $order->amount,
            $callbackUrl
        );

        // ✅ Format response for frontend
        return response()->json([
            'success' => true,
            'data'    => $result,   // contains txnUrl + body
            'order'   => $order,    // optional: return order details also
        ], 200);
    }

    public function resPayCallback(Request $request)
    {
        // Log::info("Paytm callback received for resident acc", $request->all());
        // // Pass the payload (Request object) directly
        // $result = $this->paytmService->verifyCallback($request);
        $orderId = null; // ✅ Declare early

        try {

            // Pass the payload (Request object) directly
            $result = $this->paytmService->verifyCallback($request);
            // Log::info('recived verify result', $result);

            if (!$result['valid']) {
                Log::warning('Invalid Paytm signature', ['payload' => $result['data']]);
                $orderId = $result['data']['ORDERID'] ?? null;

                return $this->respondWithError(
                    'Invalid payment response. Please try again.',
                    $orderId,
                    $request
                );
            }

            $orderId = $result['data']['ORDERID'] ?? null;
            $txnId = $result['data']['TXNID'] ?? null;
            $order = Order::where('order_number', $orderId)->first();

            if (!$order) {
                Log::error('Order not found', ['order_id' => $orderId]);
                return $this->respondWithError('Order not found. Please contact support.', $orderId, $request);
            }

            $status = $result['data']['STATUS'] ?? '';

            // Log::info('payment_status' . $status);

            $order->update([
                'status' => $status,
                'message' => $result['data']['RESPMSG'] ?? null,
                'payment_method' => $result['data']['PAYMENTMODE'] ?? null,
            ]);

            // Log::info('Order Updated successfully', $order->toArray());

            // if ($status === 'TXN_FAILURE') {
            //     Log::info('Transaction failed', ['order_id' => $order->id]);
            //     return $this->respondWithError('Transaction failed. Please retry or check with your bank.', $order->id, $request);
            // }

            if ($status === 'TXN_FAILURE') {
                // Log::info('Transaction failed', [
                //     'order_id' => $order->id,
                //     'reason' => $result['data']['RESPMSG'] ?? 'Unknown failure'
                // ]);

                return $this->respondWithError(
                    $result['data']['RESPMSG'] ?? 'Transaction failed. Please retry or check with your bank.',
                    $order->id,
                    $request
                );
            }

            $transaction = Transaction::create([
                'order_id' => $order->id,
                'txn_id' => $txnId ?? $result['data']['TXNID'] ?? null,
                'status' => $status,
                'bank_txn_id' => $result['data']['BANKTXNID'] ?? null,
                'txn_amount' => $result['data']['TXNAMOUNT'] ?? null,
                'payment_mode' => $result['data']['PAYMENTMODE'] ?? null,
                'bank_name' => $result['data']['BANKNAME'] ?? null,
                'currency' => $result['data']['CURRENCY'] ?? null,
                'm_id' => $result['data']['MID'] ?? null,
                'response_code' => $result['data']['RESPCODE'] ?? null,
                'response_message' => $result['data']['RESPMSG'] ?? null,
                'response_payload' => json_encode($result['data']),
            ]);

            // $order->update([
            //     'status' => $status,
            //     'message' => $result['data']['RESPMSG'] ?? null,
            //     'payment_method' => $result['data']['PAYMENTMODE'] ?? null,

            // ]);

            // Log::info('Transaction recorded successfully', $transaction->toArray());

            // Log::info('order_id' . $orderId);
            // Log::info('redirect_url' . $$order->redirect_url);
            // Log::info('user_data', json_decode($order->user_data, true));

            $retryUrl = url("/api/resident/payment");

            $responseData = [
                'payment_status' => $status,
                'order_id' => $order->order_id,
                'transaction_id' => $transaction->txn_id,
                'amount' => $transaction->txn_amount,
                'payment_mode' => $transaction->payment_mode,
                'redirect_url' => $order->redirect_url ?? $retryUrl,
                'user_data' => json_decode($order->user_data, true),
            ];

            // Log::info('response after transaction' . $responseData);

            // Log::info('Response data before redirect', ['responseData' => $responseData]);

            // ✅ Or replace with:
            // Log::info('Redirecting after successful transaction');

            $message = 'Payment successful';

            if ($transaction->status === 'TXN_SUCCESS') {
                try {
                    // Log::info("Processing successful payment for order_id: {$orderId}");



                    $txnId = optional($order->transaction)->txn_id ?? $txnId ?? rand(1000, 9999);


                    // Log::info("sending new request fo accessory" . json_encode($txnId, $order->payment_mode, $order->user_id));
                    // Delegate directly to GuestController::guestPayment

                    $userId = $order->user_id;
                    $residentId = $order->resident_id;
                    $invoiceNumber = $order->invoice_number;

                    // Log::info("User Id :" . json_encode($userId));
                    // Log::info("Resident Id :" . json_encode($residentId));
                    // Log::info("Invoice number :" . json_encode($invoiceNumber));

                    $StudentAccessoryController = app(StudentAccessoryController::class);
                    $StudentAccessoryController->payAccessory(
                        new Request([

                            'user_id'       => $userId,
                            'transaction_id' => $txnId,
                            'payment_method' => $order->payment_mode ?? 'Other', // Cash,UPI,Bank Transfer,Card,Other',
                            'amount'        =>  $order->amount,

                        ]),
                        $invoiceNumber,
                        $residentId
                        // or whatever value represents the invoice ID
                    );

                    // $newRequest = new Request([
                    //     'transaction_id' => $txnId,
                    //     'payment_method' => $order->payment_mode ?? 'Other',
                    //     'amount'         => $order->amount,
                    // ]);

                    // // $newRequest->headers->set('auth-id', $userId);
                    // $StudentAccessoryController = app(StudentAccessoryController::class);
                    // $StudentAccessoryController->payAccessory($newRequest, $order->invoice_number, $userId);


                    // Log::error("PaymentHandler processed");

                    // $handler = app(PaymentHandler::class);
                    // $finalResult = $handler->handle($order);


                } catch (Exception $ex) {
                    // Log::error("PaymentHandler failed for order_id {$orderId}", [
                    //     'error' => $ex->getMessage(),
                    //     'trace' => $ex->getTraceAsString()
                    // ]);
                }
            } else {
                // Log::warning("Payment failed or pending for order_id: {$orderId}", [
                //     'status' => $transaction->status
                // ]);
            }

            // Log::info('Final Result');

            // return redirect()->away(config('app.frontend_url') . '/resident/payment-success?' . http_build_query([
            // return redirect()->away('resident/pay/status?' . http_build_query([
            return redirect()->away(config('services.auth.url') . '/resident/payment/pay/status?' . http_build_query([
                'order_id' => $result['data']['ORDERID'] ?? null,
                'txn_id'   => $result['data']['TXNID'] ?? null,
                'amount'  => $result['data']['TXNAMOUNT'] ?? null,
                'status'  => 'success',
            ]));

            // return redirect()->to("payment/status?order_id=" . $orderId);


        } catch (Throwable $e) {
            Log::debug('Error occurred in respondWithError()', ['orderId' => $orderId]);

            Log::critical('Unexpected error during Paytm callback', [
                'exception' => $e->getMessage(),
                'order_id' => $orderId,
                'payload' => $request->all(),
            ]);


            return $this->respondWithError('Something went wrong. Please try again later.', null, $request);
        }


        if ($result['valid'] && ($result['data']['STATUS'] ?? '') === 'TXN_SUCCESS') {
            // Redirect to frontend success page
            return redirect()->away(config('app.frontend_url') . '/guest/payment-success?' . http_build_query([
                'order_id' => $result['data']['ORDERID'] ?? null,
                'txn_id'   => $result['data']['TXNID'] ?? null,
                'amount'  => $result['data']['TXNAMOUNT'] ?? null,
                'status'  => 'success',
            ]));
        }

        // Redirect to frontend failure page
        return redirect()->away(config('app.frontend_url') . '/payment-failure?' . http_build_query([
            'order_id' => $result['data']['ORDERID'] ?? null,
            'txn_id'   => $result['data']['TXNID'] ?? null,
            'amount'  => $result['data']['TXNAMOUNT'] ?? null,
            'status'  => 'failed',
            'message' => $result['error'] ?? 'Checksum verification failed',
        ]));
    }






    // public function ResidentPaymentStatus(Request $request)
    // {
    //     Log::info('📥 Fetching Resident Payment Status');

    //     // Step 1: Validate request parameters
    //     $validator = Validator::make($request->all(), [
    //         'order_id'   => 'required|string',
    //         'txnId'      => 'required|string',
    //         'txnAmount'  => 'required|numeric',
    //     ]);

    //     if ($validator->fails()) {
    //         Log::warning('⚠️ Validation Failed', $validator->errors()->toArray());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid request parameters',
    //             'errors'  => $validator->errors()
    //         ], 422);
    //     }

    //     // // Step 2: Extract auth-id from headers
    //     // $authId = $request->header('auth-id');
    //     // if (!$authId) {
    //     //     Log::warning('⚠️ Missing auth-id header');
    //     //     return response()->json(['success' => false, 'message' => 'Missing auth-id header'], 400);
    //     // }

    //     try {
    //         // Step 3: Fetch resident and accessories
    //         $resident = Resident::with('accessories')->where('user_id', $authId)->firstOrFail();

    //         Log::info('👤 Resident Found', ['resident_id' => $resident->id]);

    //         // Step 4: Fetch order and transaction
    //         $order = Order::with(['transaction', 'resident'])
    //             ->where('order_id', $request->order_id)
    //             ->where('resident_id', $resident->id)
    //             ->first();

    //         if (!$order) {
    //             Log::warning('⚠️ Order not found or unauthorized access', ['order_id' => $request->order_id]);
    //             return response()->json(['success' => false, 'message' => 'Order not found'], 404);
    //         }

    //         // Step 5: Map accessories
    //         $accessories = $resident->accessories->map(function ($item) {
    //             return [
    //                 'name'          => $item->description ?? 'N/A',
    //                 'qty'           => $item->qty ?? 0,
    //                 'price'         => $item->price ?? 0,
    //                 'from_date'     => $item->from_date ?? null,
    //                 'to_date'       => $item->to_date ?? null,
    //                 'total_amount'  => $item->total_amount ?? 0,
    //                 'is_default'    => $item->is_default ?? false,
    //             ];
    //         });

    //         // Step 6: Build response
    //         $response = [
    //             'success'   => true,
    //             'status'    => $order->status,
    //             'txn_id'    => $order->transaction->txn_id ?? $request->txnId,
    //             'amount'    => $order->transaction->txn_amount ?? $request->txnAmount,
    //             'order_id'  => $order->order_id,
    //             'resident'  => [
    //                 'sc_n'             => $resident->scholar_no,
    //                 'name'             => $resident->name,
    //                 'email'            => $resident->email,
    //                 'number'           => $resident->number,
    //                 'gender'           => $resident->gender,
    //                 'fathers_name'     => $resident->fathers_name,
    //                 'mothers_name'     => $resident->mothers_name,
    //                 'parent_no'        => $resident->parent_no,
    //                 'guardian_name'    => $resident->local_guardian_name,
    //                 'guardian_no'      => $resident->guardian_no,
    //                 'emergency_no'     => $resident->emergency_no,
    //                 'stay_duration'    => $resident->months,
    //                 'course'           => $resident->course,
    //             ],
    //             'accessories' => $accessories,
    //         ];

    //         Log::info('✅ Payment Status Response', $response);
    //         return response()->json($response);

    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         Log::error('❌ Resident not found', ['auth-id' => $authId]);
    //         return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    //     } catch (\Exception $e) {
    //         Log::critical('🔥 Unexpected Error', ['error' => $e->getMessage()]);
    //         return response()->json(['success' => false, 'message' => 'Internal server error'], 500);
    //     }
    // }

    public function ResidentPaymentStatus(Request $request)
    {
        Log::info('📥 Fetching Resident Payment Receipt');

        Log::info('Request ' . json_encode($request->all()));
        // Step 1: Validate request
        $validator = Validator::make($request->all(), [
            'order_id'   => 'required|string',
            'txnId'      => 'required|string',
            'txnAmount'  => 'required|numeric',
        ]);

        if ($validator->fails()) {
            Log::warning('⚠️ Validation Failed', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Invalid request parameters',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Step 2: Authenticate resident via user_id
        $userId = $request->header('auth-id');
        if (!$userId) {
            Log::warning('⚠️ Missing auth-id header');
            return response()->json(['success' => false, 'message' => 'Missing auth-id header'], 400);
        }

        try {
            $resident = Resident::with('accessories')->where('user_id', $userId)->firstOrFail();
            Log::info('👤 Resident Authenticated', ['resident_id' => $resident]);

            // Step 3: Find matching order
            $order = Order::with(['transaction', 'invoices.items.accessory'])
                ->where('order_id', $request->order_id)
                ->where('resident_id', $resident->id)
                ->whereHas('transaction', function ($query) use ($request) {
                    $query->where('txn_amount', $request->txnAmount);
                })
                ->first();

            Log::info('Order ' . json_encode($order));

            // ->where('resident_id', $resident->id)
            // ->where('invoice_number', $request->txnId) // assuming txnId maps to invoice_number
            // ->whereHas('transaction', function ($query) use ($request) {
            //     $query->where('txn_amount', $request->txnAmount);
            // })
            // ->first();

            // $order = Order::with(['transactions', 'invoices->items->accessory'])
            //     ->where('order_id', $request->order_id)
            //     ->where('resident_id', $resident->id)
            //     ->where('invoice_number', $request->txnId) // assuming txnId maps to invoice_number
            //     ->whereHas('transactions', function ($query) use ($request) {
            //         $query->where('txn_amount', $request->txnAmount);
            //     })
            //     ->first();

            // Log::info('Order with transactions ' .json_encode($order));
            if (!$order) {
                Log::warning('⚠️ No matching order found');
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            // Step 4: Map accessories
            // $accessories = $resident->accessories->map(function ($item) {
            //     return [
            //         'name'          => $item->description ?? 'N/A',
            //         'qty'           => $item->qty ?? 0,
            //         'price'         => $item->price ?? 0,
            //         'from_date'     => $item->from_date ?? null,
            //         'to_date'       => $item->to_date ?? null,
            //         'total_amount'  => $item->total_amount ?? 0,
            //         'is_default'    => $item->is_default ?? false,
            //     ];
            // });

            // Step 3: Validate transaction and amount
            if (
                $order->transaction->txn_amount != $request->txnAmount
                // ||
                // $order->transaction->txn_id != $request->txnId
            ) {
                Log::warning('⚠️ Transaction mismatch');
                return response()->json(['success' => false, 'message' => 'Transaction details mismatch'], 400);
            }

            // Step 4: Structure accessories from invoice items
            $accessories = collect();
            foreach ($order->invoices->items ?? [] as $item) {
                if ($item->item_type === 'accessory' && $item->accessory) {
                    $accessories->push([
                        'name'          => $item->accessory->name ?? $item->description,
                        'description'   => $item->description,
                        'qty'           => 1,
                        'price'         => $item->price,
                        'from_date'     => $item->from_date,
                        'to_date'       => $item->to_date,
                        'total_amount'  => $item->total_amount,
                        'is_default'    => (bool) ($item->accessory->is_default ?? false),
                        'accessory_meta' => [
                            'accessory_id' => $item->accessory->id,
                            'head_id'      => $item->accessory->accessory_head_id,
                            'is_active'    => (bool) $item->accessory->is_active,
                            'created_by'   => $item->accessory->created_by,
                            'created_at'   => $item->accessory->created_at,
                        ]
                    ]);
                }
            }

            Log::info('accessory ' . json_encode($accessories));


            // Step 5: Build structured response
            $response = [
                'success' => true,
                'status'    => $order->status,
                'txn_id'    => $order->transaction->txn_id ?? $request->txnId,
                'amount'    => $order->transaction->txn_amount ?? $request->txnAmount,
                'resident' => [
                    'name'       => $resident->name,
                    'scholar_no'       => $resident->scholar_no,
                    'email'       => $resident->email,
                    'mobile'       => $resident->number,
                    'gender'       => $resident->gender,
                    'bed_id'  => $resident->bed_id,
                    'status'  => $resident->status,
                ],
                'order' => [
                    'order_id'       => $order->order_id,
                    'invoice_number' => $order->invoice_number,
                    'amount'         => $order->amount,
                    'status'         => $order->status,
                    'message'        => $order->message,
                    'purpose'        => $order->purpose,
                ],
                'transaction' => [
                    'txn_id'           => $order->transaction->txn_id,
                    'status'           => $order->transaction->status,
                    'bank_name'        => $order->transaction->bank_name,
                    'payment_mode'     => $order->transaction->payment_mode,
                    'txn_amount'       => $order->transaction->txn_amount,
                    'currency'         => $order->transaction->currency,
                    'response_code'    => $order->transaction->response_code,
                    'response_message' => $order->transaction->response_message,
                    'merchant_id'      => $order->transaction->m_id,
                    'created_at'       => $order->transaction->created_at,
                ],
                'invoice' => [
                    'invoice_id'      => $order->invoices->id ?? null,
                    'invoice_number'  => $order->invoices->invoice_number ?? null,
                    'resident_id'     => $order->invoices->resident_id ?? null,
                    'invoice_date'    => $order->invoices->invoice_date ?? null,
                    'due_date'        => $order->invoices->due_date ?? null,
                    'total_amount'    => $order->invoices->total_amount ?? null,
                    'paid_amount'     => $order->invoices->paid_amount ?? null,
                    'remaining_amount' => $order->invoices->remaining_amount ?? null,
                    'remarks'         => $order->invoices->remarks ?? null,
                    'status'          => $order->invoices->status ?? null,
                ],
                'accessories' => $accessories->values()
            ];

            Log::info('✅ Structured Receipt Response', $response);
            return response()->json($response);

            // // Step 5: Build response
            // $response = [
            //     'success'   => true,
            //     'status'    => $order->status,
            //     'txn_id'    => $order->transaction->txn_id ?? $request->txnId,
            //     'amount'    => $order->transaction->txn_amount ?? $request->txnAmount,
            //     'order_id'  => $order->order_id,
            //     'invoice_number' => $order->invoice_number,
            //     'resident'  => [
            //         'name'             => $resident->name,
            //         'email'            => $resident->email,
            //         'number'           => $resident->number,
            //         'gender'           => $resident->gender,
            //         'scholar_no'       => $resident->scholar_no,
            //         'course'           => $resident->course,
            //         'fathers_name'     => $resident->fathers_name,
            //         'mothers_name'     => $resident->mothers_name,
            //         'parent_no'        => $resident->parent_no,
            //         'guardian_name'    => $resident->local_guardian_name,
            //         'guardian_no'      => $resident->guardian_no,
            //         'emergency_no'     => $resident->emergency_no,
            //         'stay_duration'    => $resident->months,
            //     ],
            //     'accessories' => $accessories,
            // ];

            // Log::info('✅ Receipt Response Ready', $response);
            // return response()->json($response);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('❌ Resident not found', ['user_id' => $userId]);
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        } catch (\Exception $e) {
            Log::critical('🔥 Unexpected Error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Internal server error'], 500);
        }
    }
}
