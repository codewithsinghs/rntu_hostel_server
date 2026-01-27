<?php

namespace App\Http\Controllers\ApiV1;

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
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\PaytmPaymentService;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class PaytmCallbackController extends Controller
{
    protected $paytmService;

    public function __construct(PaytmPaymentService $paytmService)
    {
        $this->paytmService = $paytmService;
    }

    private function apiResponse($success, $message, $data = null, $status = 200, $errors = null)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data ?? null,
            'errors' => $errors ?? null
        ], $status);
    }


    private function respondWithError($message, $orderId = null, Request $request, $errorCode = 'general_error')
    {
        Log::debug('Preparing error response', [
            'order_id' => $orderId,
            'message'  => $message,
            'code'     => $errorCode,
            'payload'  => $request->all(),
        ]);

        $queryParams = [
            'order_id' => $orderId,
            'status'   => 'failure',
            'error'    => $errorCode,
        ];

        return redirect()->away(config('app.frontend_url') . '/guest/payment/reciept?' . http_build_query($queryParams));
    }

    public function guestPayCallback(Request $request)
    {
        $orderId = null;

        try {
            // 1️⃣ Verify Paytm payload
            $result = $this->paytmService->verifyCallback($request);
            $payload = $result['data'] ?? [];

            Log::info('Guest Paytm callback received', $payload);
            $orderId = $payload['ORDERID'] ?? null;

            if (!$result['valid']) {
                Log::warning('Invalid Paytm signature (Guest)', $payload);

                return $this->respondWithError(
                    'Invalid payment response.',
                    $orderId,
                    $request,
                    'signature_invalid'
                );
            }

            $status = $payload['STATUS'] ?? 'UNKNOWN';
            $txnId  = $payload['TXNID'] ?? null;

            // 2️⃣ Fetch order
            $order = Order::with('invoices')->where('order_number', $orderId)->first();

            if (!$order) {
                Log::error('Guest order not found', ['order_id' => $orderId]);

                return $this->respondWithError(
                    'Order not found.',
                    $orderId,
                    $request,
                    'order_not_found'
                );
            }

            // 3️⃣ Idempotency: avoid duplicate callbacks
            if ($txnId && Transaction::where('txn_id', $txnId)->exists()) {
                return redirect()->away(
                    config('app.frontend_url') . '/guest/payment/reciept?' . http_build_query([
                        'order_id' => $orderId,
                        'txn_id'   => $txnId,
                        'amount'   => $payload['TXNAMOUNT'] ?? null,
                        'status'   => 'already_processed',
                    ])
                );
            }

            // 4️⃣ Record transaction ALWAYS (success/failure/pending)
            $transaction = Transaction::create([
                'order_id'         => $order->id,
                'txn_id'           => $txnId,
                'status'           => $status,
                'bank_txn_id'      => $payload['BANKTXNID'] ?? null,
                'txn_amount'       => $payload['TXNAMOUNT'] ?? null,
                'payment_mode'     => $payload['PAYMENTMODE'] ?? null,
                'bank_name'        => $payload['BANKNAME'] ?? null,
                'currency'         => $payload['CURRENCY'] ?? 'INR',
                'm_id'             => $payload['MID'] ?? null,
                'response_code'    => $payload['RESPCODE'] ?? null,
                'response_message' => $payload['RESPMSG'] ?? null,
                'response_payload' => json_encode($payload),
            ]);

            // 5️⃣ Update order meta (always)
            $order->update([
                'payment_method' => $payload['PAYMENTMODE'] ?? null,
                'message'        => $payload['RESPMSG'] ?? null,
            ]);

            // 6️⃣ Handle SUCCESS
            if ($status === 'TXN_SUCCESS') {

                DB::transaction(function () use ($order, $transaction) {

                    $order->update(['status' => 'paid']);

                    $remaining = (float) $transaction->txn_amount;

                    foreach ($order->invoices as $invoice) {
                        if ($remaining <= 0) break;

                        $payable = min(
                            $remaining,
                            $invoice->total_amount - $invoice->paid_amount
                        );

                        $invoice->increment('paid_amount', $payable);

                        $invoice->update([
                            'remaining_amount' => max(0, $invoice->total_amount - $invoice->paid_amount),
                            'status' => $invoice->paid_amount >= $invoice->total_amount
                                ? 'paid'
                                : 'partial'
                        ]);

                        $order->invoices()->updateExistingPivot($invoice->id, [
                            'amount_paid' => $payable,
                            'paid_at'     => now(),
                        ]);

                        $remaining -= $payable;
                    }

                    // Guest-specific accounting
                    app(GuestPaymentService::class)->record([
                        'guest_id'       => $order->guest_id,
                        'transaction_id' => $transaction->txn_id,
                        'payment_method' => $transaction->payment_mode,
                        'remarks'        => 'Paid via Paytm',
                    ]);
                });

                return redirect()->away(
                    config('app.frontend_url') . '/guest/payment/reciept?' . http_build_query([
                        'order_id' => $order->order_number,
                        'txn_id'   => $txnId,
                        'amount'   => $transaction->txn_amount,
                        'status'   => 'success',
                    ])
                );
            }

            // 7️⃣ Handle FAILURE
            if ($status === 'TXN_FAILURE') {
                return redirect()->away(
                    config('app.frontend_url') . '/guest/payment/reciept?' . http_build_query([
                        'order_id' => $order->order_number,
                        'txn_id'   => $txnId,
                        'amount'   => $order->amount,
                        'status'   => 'failed',
                    ])
                );
            }

            // 6️⃣ Handle PENDING
            if ($status === 'PENDING' || $status === 'UNKNOWN') {
                Log::info('Transaction pending', [
                    'order_id' => $order->id,
                    'txn_id'   => $txnId,
                ]);
                return redirect()->away(
                    config('app.frontend_url') . '/guest/payment/reciept?' . http_build_query([
                        'order_id' => $order->order_number,
                        'txn_id'   => $txnId,
                        'amount'   => $order->amount,
                        'status'   => 'pending',
                    ])
                );
            }

            // 8️⃣ Pending / Unknown
            return redirect()->away(
                config('app.frontend_url') . '/guest/payment/reciept?' . http_build_query([
                    'order_id' => $order->order_number,
                    'txn_id'   => $txnId,
                    'amount'   => $payload['TXNAMOUNT'] ?? null,
                    'status'   => 'pending',
                ])
            );
        } catch (\Throwable $e) {

            Log::critical('Guest Paytm callback crashed', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
                'payload'  => $request->all(),
            ]);

            return $this->respondWithError(
                'Unexpected error occurred.',
                $orderId,
                $request,
                'exception'
            );
        }
    }





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



    // // 11092025
    // public function PaymentStatus(Request $request)
    // {
    //     Log::info('fetching payment Status');
    //     $authId = $request->header('auth-id'); // Get auth-id from headers
    //     Log::alert($authId);

    //     //  Authenticate guest using token guard
    //     try {
    //         // $guest = Guest::findOrFail($authId);
    //         $guest = Guest::with('accessories')->find($authId);
    //         Log::alert($guest);

    //         // Format the response for frontend
    //         $accessories = $guest->accessories->map(function ($item) {
    //             return [
    //                 'name'  => $item->name,
    //                 'qty'   => $item->qty,
    //                 'price' => $item->price,
    //                 'from_date' => $item->from_date,
    //                 'to_date' => $item->to_date,
    //                 'total_amount' => $item->total_amount,
    //                 'is_default' => $item->is_default,
    //             ];
    //         });

    //         Log::alert($accessories);
    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    //     }


    //     $order = Order::with(['transaction', 'guest'])
    //         ->where('order_id', $request->order_id)
    //         // ->where('guest_id', $guest->id) // ensure guest owns the order
    //         ->first();

    //     // Log::info($order);

    //     if (!$order) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Order not found'
    //         ], 404);
    //     }

    //     // if ($order->guest_id != $authId) {
    //     //     return response()->json([
    //     //         'success' => false,
    //     //         'message' => 'Unauthorized'
    //     //     ], 401);
    //     // }

    //     // Log::info('amount fetched', $feeData);
    //     $response = [
    //         'success' => true,
    //         'status'  => $order->status,
    //         'txn_id'  => $order->transaction->txn_id ?? null,
    //         'amount'  => $order->transaction->txn_amount ?? null,
    //         'order_id' => $order->order_id,
    //         'guest'   => [
    //             'sc_n' => $order->guest->scholar_number ?? null,
    //             'name'  => $order->guest->name ?? null,
    //             'email' => $order->guest->email ?? null,
    //             'mobile' => $order->guest->mobile ?? null,
    //             'gender' => $order->guest->gender ?? null,
    //             'fathers_name' => $order->guest->fathers_name ?? null,
    //             'mothers_name' => $order->guest->mothers_name ?? null,
    //             'parent_contact' => $order->guest->parent_contact ?? null,
    //             'guardian_name' => $order->guest->local_guardian_name ?? null,
    //             'guardian_contact' => $order->guest->guardian_contact ?? null,
    //             'emergency_contact' => $order->guest->emergency_contact ?? null,
    //             'stay_duration' => $order->guest->month ?? null,
    //             'course' => $order->guest->course ?? null,

    //         ],
    //          'accessories' => $accessories,
    //     ];

    //     Log::info($response);

    //     return response()->json($response);
    // }

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

            // Log::alert($accessories);
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


    public function accountSubscribePay(Request $request)
    {
        try {
            $request->validate([
                'resident_id' => 'required|exists:residents,id',
                'transaction_id' => 'required|unique:payments,transaction_id',
                'payment_method' => 'required|in:Cash,UPI,Bank Transfer,Card',
                'subscription_id' => 'required|exists:subscriptions,id',
                'amount' => 'required|numeric|min:1'
            ]);

            DB::beginTransaction();

            $subscription = Subscription::findOrFail($request->subscription_id);

            if ($subscription->resident_id != $request->resident_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resident ID does not match the subscription.',
                    'data' => null,
                    'errors' => ['resident_id' => ['Mismatch with subscription']]
                ], 400);
            }

            if ($subscription->status === 'Active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscription is already active.',
                    'data' => null,
                    'errors' => null
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
                    'errors' => null
                ], 400);
            }

            $newRemainingAmount = max($remainingBalance - $request->amount, 0);
            $paymentStatus = ($newRemainingAmount == 0) ? 'Completed' : 'Pending';

            Payment::create([
                'resident_id' => $request->resident_id,
                'fee_head_id' => $subscription->fee_head_id,
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
                'message' => 'Resource not found',
                'data' => null,
                'errors' => null
            ], 404);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process payment.',
                'data' => null,
                'errors' => ['exception' => [$e->getMessage()]]
            ], 500);
        }
    }




    public function payAsResident(Request $request)
    {
        try {
            $request->validate([
                'resident_id' => 'required|exists:residents,id',
                'fee_head_id' => 'required|exists:fees,fee_head_id',
                'transaction_id' => 'nullable|unique:payments,transaction_id',
                'amount' => 'required|numeric|min:1',
                'payment_method' => 'required|in:Cash,UPI,Bank Transfer,Card,Other',
                'remarks' => 'nullable|string'
            ]);

            $resident = Resident::findOrFail($request->resident_id);
            $fee = Fee::where('fee_head_id', $request->fee_head_id)->firstOrFail();

            if ($request->amount < $fee->amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid amount. Please pay the correct fee.',
                    'data' => null,
                    'errors' => null
                ], 400);
            }

            Payment::create([
                'resident_id' => $resident->id,
                'fee_head_id' => $fee->fee_head_id,
                'amount' => $request->amount,
                'transaction_id' => $request->transaction_id,
                'payment_method' => $request->payment_method,
                'payment_status' => 'Completed',
                'created_by' => null,
                'remarks' => $request->remarks
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful.',
                'data' => null,
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
                'message' => 'Fee or Resident not found',
                'data' => null,
                'errors' => null
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'data' => null,
                'errors' => ['exception' => [$e->getMessage()]]
            ], 500);
        }
    }




    // public function getPendingPayments()
    // {
    //     $resident_id = Helper::get_resident_details(request()->header('auth-id'))->id;
    //     try {
    //         $pendingPayments = Invoice::where('resident_id', $resident_id)
    //             ->with('resident')
    //             ->where('remaining_amount', '>', 0)
    //             ->get();

    //         // Log::info('Pending Payments: ' . $pendingPayments);

    //         if ($pendingPayments->isEmpty()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'No pending payments found.',
    //                 'data' => [],
    //                 'errors' => null
    //             ]);
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Pending payments retrieved successfully.',
    //             'data' => $pendingPayments,
    //             'errors' => null
    //         ]);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An unexpected error occurred.',
    //             'data' => null,
    //             'errors' => ['exception' => [$e->getMessage()]]
    //         ], 500);
    //     }
    // }

    public function getPendingPayments()
    {
        $resident_id = Helper::get_resident_details(request()->header('auth-id'))->id;

        try {

            // Load all invoices + orders only ONCE
            $invoices = Invoice::where('resident_id', $resident_id)
                ->with(['resident', 'orders'])
                ->get();

            if ($invoices->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No invoices found.',
                    'data'    => [],
                    'summary' => [],
                    'errors'  => null
                ]);
            }

            /** ---------------------------------------------------------
             *  PREP DATA IN ONE PASS ONLY (SMART & FAST)
             * --------------------------------------------------------*/
            $pendingInvoices  = collect();     // invoices where remaining_amount > 0
            $orderPayments    = collect();     // all pivot payments (amount + date)
            $datesUpcoming    = collect();     // future due dates
            $datesMissed      = collect();     // past due dates

            $today = now()->startOfDay();

            foreach ($invoices as $inv) {

                // 1️⃣ pending invoices
                if ($inv->remaining_amount > 0) {
                    $pendingInvoices->push($inv);

                    $due = \Carbon\Carbon::parse($inv->due_date);

                    if ($due->gte($today)) {
                        $datesUpcoming->push($due);
                    } else {
                        $datesMissed->push($due);
                    }
                }

                // 2️⃣ collect all pivot payments
                foreach ($inv->orders as $order) {

                    if (!empty($order->pivot->paid_at)) {
                        $orderPayments->push([
                            'amount' => $order->pivot->amount_paid,
                            'date'   => \Carbon\Carbon::parse($order->pivot->paid_at)
                        ]);
                    }
                }
            }

            // If NO pending invoices
            if ($pendingInvoices->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No pending payments found.',
                    'data'    => [],
                    'summary' => [],
                    'errors'  => null
                ]);
            }

            /** ---------------------------------------------------------
             *  LAST PAYMENT (amount + date)
             * --------------------------------------------------------*/
            $lastTransaction = null;

            if ($orderPayments->isNotEmpty()) {

                // find latest payment
                $latest = $orderPayments->sortByDesc('date')->first();

                $lastTransaction =
                    "₹" . number_format($latest['amount'], 0)
                    . " on " . $latest['date']->format('d M Y');
            }

            /** ---------------------------------------------------------
             *  UPCOMING or MISSED due date
             * --------------------------------------------------------*/
            $dueSummary = null;

            if ($datesUpcoming->isNotEmpty()) {
                $dueSummary = "Upcoming: " . $datesUpcoming->sort()->first()->format('d M Y');
            } elseif ($datesMissed->isNotEmpty()) {
                $dueSummary = "Missed: " . $datesMissed->sortDesc()->first()->format('d M Y');
            }


            /** ---------------------------------------------------------
             *  FINAL SUMMARY
             * --------------------------------------------------------*/
            $summary = [
                'total_transactions'      => $invoices->count(),
                'pending_transactions'    => $pendingInvoices->count(),
                'last_transaction'        => $lastTransaction,
                'due_date' => $dueSummary,
            ];

            $formatted = $pendingInvoices->map(function ($inv) {
                return [
                    'id'      => $inv->id,
                    'resident_id'      => $inv->resident_id,
                    'invoice_number'  => $inv->invoice_number,
                    'type'  => $inv->type,
                    'description'  => $inv->description,
                    'resident_name'   => $inv->resident->name ?? '',
                    'total_amount'    => $inv->total_amount,
                    'paid_amount'     => $inv->paid_amount,
                    'remaining_amount' => $inv->remaining_amount,
                    'status' => $inv->status,
                    'due_date'        => \Carbon\Carbon::parse($inv->due_date)->format('d M Y'),

                    // Only send filtered invoice items
                    'invoice_items'   => $inv->orders->map(function ($order) {
                        return [
                            'item_name'  => $order->item_name ?? $order->description,
                            'price'      => $order->pivot->amount_paid ?? $order->price,
                            'paid_at'    => optional($order->pivot->paid_at)
                                ? \Carbon\Carbon::parse($order->pivot->paid_at)->format('d M Y')
                                : null
                        ];
                    }),

                    'status'          => $inv->status,
                ];
            });



            return response()->json([
                'success' => true,
                'message' => 'Pending payments retrieved successfully.',
                'summary' => $summary,
                // 'data'    => $pendingInvoices->values(),
                'data'    => $formatted,
                'errors'  => null
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'data'    => null,
                'errors'  => ['exception' => [$e->getMessage()]]
            ], 500);
        }
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


    // public function getAccessoryPendingPayments($resident_id)
    // {
    //     try {
    //         $validator = Validator::make(['resident_id' => $resident_id], [
    //             'resident_id' => 'required|exists:residents,id',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Validation failed',
    //                 'data' => null,
    //                 'errors' => $validator->errors()
    //             ], 422);
    //         }

    //         $latestPaymentIds = Payment::where('resident_id', $resident_id)
    //             ->whereNotNull('student_accessory_id')
    //             ->select(DB::raw('MAX(id) as id'))
    //             ->groupBy('student_accessory_id')
    //             ->pluck('id');

    //         $latestPayments = Payment::with([
    //             'resident.user',
    //             'resident.guest',
    //             'studentAccessory.accessory'
    //         ])
    //             ->whereIn('id', $latestPaymentIds)
    //             ->where('remaining_amount', '>', 0)
    //             ->get();

    //         if ($latestPayments->isEmpty()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'No pending payments found.',
    //                 'data' => [],
    //                 'errors' => null
    //             ]);
    //         }

    //         $formattedPayments = $latestPayments->map(function ($payment) {
    //             return [
    //                 'payment_id' => $payment->id,
    //                 'amount' => $payment->amount,
    //                 'remaining_amount' => $payment->remaining_amount,
    //                 'student_accessory_id' => $payment->student_accessory_id,
    //                 'accessory_name' => $payment->studentAccessory->accessory->name ?? 'N/A',
    //                 'resident_name' => $payment->resident->user->name ?? 'N/A',
    //                 'scholar_no' => $payment->resident->guest->scholar_no ?? 'N/A',
    //             ];
    //         });

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Pending accessory payments retrieved successfully.',
    //             'data' => $formattedPayments,
    //             'errors' => null
    //         ]);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An unexpected error occurred.',
    //             'data' => null,
    //             'errors' => ['exception' => [$e->getMessage()]]
    //         ], 500);
    //     }
    // } not getting accessory name

    public function getRecentTransactions(Request $request)
    {
        // Log::info('recent transactons');
        try {
            $limit = $request->input('limit', 5); // default: latest 5 transactions

            $user = $request->user();

            if (!$user || !$user->resident) {
                return $this->apiResponse(false, 'Resident not found.', [], 404);
            }

            $resident = $user->resident;

            // 👇 EXACTLY your query, NO CHANGE
            $invoices = Invoice::where('resident_id', $resident->id)
                // ->with('resident', 'orders')   // orders + pivot auto loaded
                ->with(['resident', 'orders' => function ($q) {
                    $q->where('status', 'paid');    // ONLY paid orders
                }])
                ->orderBy('created_at', 'desc')
                ->where('status', 'paid')
                ->limit($limit)
                ->get();

            // Log::info('paid invoices' . json_encode($invoices));

            //         // Fetch all orders of this resident (if relation exists)
            // $orders = Order::whereHas('invoices', function ($q) use ($resident) {
            //         $q->where('resident_id', $resident->id);
            //     })
            //     ->get();

            // foreach ($invoices as $invoice) {

            //     // orders already loaded because of with('orders')
            //     $orders = $invoice->orders;
            //     Log::info('invoice order'. json_encode($orders));
            //     foreach ($orders as $order) {
            //         $orderName = $order->name;
            //         $amountPaid = $order->pivot->amount_paid;
            //         $paidAt = $order->pivot->paid_at;

            //         // you can use it as needed
            //     }
            // }

            // 👇 REFORMAT RESPONSE so orders + pivot appear clearly
            $formatted = $invoices->map(function ($invoice) {
                return [
                    // 'invoice_id'       => $invoice->id,
                    'invoice_number'   => $invoice->invoice_number,
                    'total_amount'     => $invoice->total_amount,
                    'amount_paid'      => $invoice->paid_amount,
                    'remaining_amount' => $invoice->remaining_amount,
                    'due_date'         => $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y')
                        : null,
                    'status'           => $invoice->status,
                    // 'items'            => $invoice->items,
                    // Only pick specific columns from items
                    'items' => collect($invoice['items'] ?? [])->map(function ($item) {
                        return [
                            // 'id'          => $item['id'],
                            'item_type'   => $item['item_type'],
                            'description' => $item['description'],
                            'price'       => $item['price'],
                            'total_amount' => $item['total_amount'],
                        ];
                    })->values()->all(),

                    'resident' => [
                        // 'id'   => $invoice->resident->id,
                        'name' => $invoice->resident->name,
                        'email' => $invoice->resident->email ?? $invoice->resident->profile->email,
                        'hostel' => $invoice->resident->hostel->name . ' , ' . $invoice->resident->room->room_number,
                    ],

                    // 👇 HERE orders + pivot are loaded and structured cleanly
                    'orders' => $invoice->orders->map(function ($order) {
                        return [
                            'order_number'   => $order->order_number,
                            'order_name' => $order->name,

                            'amount_paid' => $order->pivot->amount_paid,
                            'paid_at' => $order->pivot->paid_at
                                ? \Carbon\Carbon::parse($order->pivot->paid_at)->format('d M Y, h:i A')
                                : null,
                            // 👇 FULL TRANSACTION DETAILS
                            'transaction' => $order->transaction ? [
                                'transaction_id' => $order->transaction->transaction_id,
                                'payment_mode'   => $order->transaction->payment_mode,
                                'status' => $order->transaction->status,
                                'bank_name'   => $order->transaction->bank_name,
                                'txn_amount'        => $order->transaction->txn_amount,
                                'transaction_at' => $order->transaction->created_at
                                    ? \Carbon\Carbon::parse($order->transaction->created_at)->format('d M Y, h:i A')
                                    : null,
                            ] : null,
                        ];
                    })->toArray()
                ];
            });

            // ✔ Correct logging — example: logging FIRST invoice's resident name if exists
            if ($formatted->isNotEmpty()) {
                // Log::info('invoices resident: ' . json_encode($formatted[0]['orders']));
            }

            $transactions = $formatted->map(function ($item) {
                $orders = collect($item['orders']);

                return [
                    'name'              => $item['resident']['name'],
                    'email'              => $item['resident']['email'],
                    'hostel'              => $item['resident']['hostel'],
                    'invoice_number'    => $item['invoice_number'],
                    'total_amount'      => $item['total_amount'],
                    'paid_amount'       => $item['amount_paid'],
                    'remaining_amount'  => $item['remaining_amount'],
                    'due_date'            => $item['due_date'],
                    'status' => strtoupper($item['status']),

                    'items'            => $item['items'],

                    // 'order'    => $item['orders'],

                    // // 👇 Merge all orders into one flat list
                    // 'orders'            => collect($item['orders'])->map(function ($o) {
                    //     return [
                    //         'order_number'  => $o['order_number'],
                    //         'order_name'    => $o['order_name'],
                    //         'amount_paid'   => $o['amount_paid'],
                    //         'paid_at'       => $o['paid_at'],
                    //     ];
                    // })->values()->all()
                    'order_number' => collect($item['orders'])
                        ->pluck('order_number')
                        ->implode(', '),
                    'paid_at' => collect($item['orders'])
                        ->pluck('paid_at')
                        ->implode(', '),

                    // 🔥 Transaction IDs
                    'transaction_id' => $orders->pluck('transaction.transaction_id')->implode(', '),

                    // 🔥 Payment Modes
                    'payment_mode' => $orders->pluck('transaction.payment_mode')->implode(', '),

                    // 🔥 Payment Status
                    'payment_status' => $orders->pluck('transaction.payment_status')->implode(', '),

                    // 🔥 Gateways (ex: razorpay / stripe)
                    'gateway' => $orders->pluck('transaction.gateway')->implode(', '),
                ];
            });
            return $this->apiResponse(
                true,
                'Recent transactions retrieved successfully.',
                [
                    'summary' => $transactions->count(),
                    'items' => $transactions
                ],
                200
            );
        } catch (Throwable $e) {
            Log::error('Recent Transaction Fetch Error', [
                'error' => $e->getMessage()
            ]);

            return $this->apiResponse(
                false,
                'Failed to retrieve recent transactions.',
                [],
                500,
                ['exception' => $e->getMessage()]
            );
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
            $payments = Invoice::with('resident.user', 'resident.guest')
                ->whereIn('id', $latestInvoiceIds)
                ->whereHas('resident.user', function ($query) use ($user) {
                    $query->where('university_id', $user->university_id);
                })
                // ->where('remaining_amount', '>', 0)
                ->get();
            // Log::info('Pending Payments: ' . $payments);
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
                    'scholar_no'      => optional($payment->resident)->scholar_no,
                    'subscription_id'  => $payment->subscription_id,
                    'total_amount'     => $payment->total_amount,
                    'amount_paid'      => $payment->paid_amount,
                    'remaining_amount' => $payment->remaining_amount,
                    // 'payment_method'   => $payment->payment_method,
                    'payment_status'   => $payment->status,
                    'due_date'         => $payment->due_date,
                    'created_at'       => $payment->created_at->toDateTimeString(),
                    'guest'            => $payment->resident->guest
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
            // $payments = DB::table('invoices as payments')
            //     ->select(
            //         'payments.id as payment_id',
            //         'payments.invoice_number',
            //         'payments.total_amount',
            //         'payments.paid_amount',
            //         'payments.remaining_amount',
            //         // 'payments.payment_method',
            //         'payments.status',
            //         'payments.due_date',
            //         'payments.remarks',
            //         'payments.created_at',
            //         'fees.name as fee_head_name',
            //         'accessory_heads.name as accessory_name',
            //         'subscriptions.subscription_type as subscription_name'
            //     )
            //     ->leftJoin('fees', 'payments.fee_head_id', '=', 'fees.id')
            //     ->leftJoin('subscriptions', 'payments.subscription_id', '=', 'subscriptions.id')
            //     // Joining sequence: payments -> student_accessory -> accessory -> accessory_heads
            //     ->leftJoin('student_accessory', 'payments.student_accessory_id', '=', 'student_accessory.id')
            //     ->leftJoin('accessory', 'student_accessory.accessory_head_id', '=', 'accessory.id')
            //     ->leftJoin('accessory_heads', 'accessory.accessory_head_id', '=', 'accessory_heads.id')
            //     ->where('payments.resident_id', $id)
            //     ->orderBy('payments.created_at', 'desc')
            //     ->get();

            $payments = Invoice::with(['fee', 'subscription', 'studentAccessory.accessory.accessoryHead'])
                ->where('resident_id', $id)
                ->orderByDesc('created_at')
                ->get();
            // Log::info('Payments fetched: ' . $payments);
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





    public function summary(Request $request)
    {
        // Log::info('payment Summary', $request->all());
        $invoiceIds = explode(',', $request->query('invoices', ''));
        $invoices = Invoice::with('resident')->whereIn('id', $invoiceIds)->get();

        if ($invoices->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No invoices found.'
            ]);
        }

        $data = $invoices->map(function ($inv) {
            return [
                'id' => $inv->id,
                'invoice_number' => $inv->invoice_number,
                'resident_name' => $inv->resident->name ?? '-',
                'remaining_amount' => $inv->remaining_amount,
                'remarks' => $inv->remarks
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function getResidentInvoices($resident_id)
    {
        // Log::info('Fetching invoices for resident ID: ' . $resident_id);
        $invoices = Invoice::where('resident_id', $resident_id)
            // ->where('remaining_amount', '>', 0)
            ->get();
        // Log::info('Resident Invoices: ' . $invoices);
        return response()->json([
            'success' => true,
            'data' => $invoices
        ]);
    }

    public function getResidentInvoicesTransactions($resident_id, $invoice_id)
    {
        // Log::info('Fetching transactions for resident ID: ' . $resident_id . ' and invoice ID: ' . $invoice_id);
        // Step 1: Get invoice record first
        $invoice = Invoice::where('id', $invoice_id)
            ->where('resident_id', $resident_id)
            ->first();

        if (!$invoice) {
            return collect(); // No matching invoice for this resident
        }

        // Step 2: Get transactions for that invoice via order JSON
        $transactions = Transaction::with([
            'order.invoices' => function ($query) {
                $query->select(
                    'invoices.id as invoice_id',
                    'invoices.invoice_number',
                    'invoices.resident_id',
                    'invoices.total_amount',
                    'invoices.paid_amount',
                    'invoices.remaining_amount',
                    'invoices.due_date',
                    'invoices.status'
                );
            }
        ])
            ->whereHas('order', function ($query) use ($invoice) {
                $query->whereJsonContains('invoice_number', $invoice->invoice_number);
            })
            ->select(
                'transactions.id as transaction_id',
                'transactions.order_id',
                'transactions.txn_id',
                'transactions.txn_amount',
                'transactions.payment_mode',
                'transactions.status',
                'transactions.created_at',
                'transactions.response_payload'
            )
            ->orderByDesc('transactions.created_at')
            ->get();


        // Log::info('Transactions: ' . $transactions);
        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }


    // public function confirmPayment(Request $request)
    // {
    //     Log::info('payment confirm', $request->all());
    //     $invoices = $request->input('invoices', []);

    //     if (empty($invoices)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No invoices selected.'
    //         ], 400);
    //     }

    //     // Example: calculate total, validate invoices, check remaining_amount etc.
    //     $total = collect($invoices)->sum('amount');

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Invoices confirmed.',
    //         'data' => [
    //             'invoices' => $invoices,
    //             'total' => $total
    //         ]
    //     ]);
    // }

    public function confirmPayment(Request $request)
    {
        // Log::info('payment confirm', $request->all());
        $invoices = $request->input('invoices', []);

        if (empty($invoices)) {
            return response()->json([
                'success' => false,
                'message' => 'No invoices selected.'
            ], 400);
        }

        // Extract invoice numbers
        $invoiceNumbers = collect($invoices)->pluck('invoiceNumber')->toArray();

        // Prepare invoice JSON (if needed for further processing)
        $invoiceJson = Order::prepareInvoiceNumber($invoiceNumbers);
        // Log::info('invoice numbers'. $invoiceJson);

        // Log::info($invoiceNumbers);

        // Get resident and user ID
        $resident_id = $invoices[0]['resId'];
        $resident = Resident::findOrFail($resident_id);
        $userId = $resident->user_id;

        // Check for existing draft order with overlapping invoice numbers
        $existingDraftOrder = Order::where('user_id', $userId)
            ->where('status', 'draft')
            ->where(function ($query) use ($invoiceNumbers) {
                foreach ($invoiceNumbers as $invNum) {
                    $query->orWhereJsonContains('metadata', [['invoiceNumber' => $invNum]]);
                }
            })
            ->latest()
            ->first();

        if ($existingDraftOrder) {
            // Merge new invoices with existing ones, avoiding duplicates
            $existingMetadata = collect($existingDraftOrder->metadata);
            $newMetadata = collect($invoices);

            // $mergedMetadata = $existingMetadata->merge($newMetadata)
            //     ->unique('invoiceNumber')
            //     ->values();
            $mergedMetadata = collect($invoices)->unique('invoiceNumber')->values();


            $total = $mergedMetadata->sum('amount');

            $existingDraftOrder->update([
                'amount' => $total,
                'invoice_number' => $invoiceJson,
                'metadata' => $mergedMetadata
            ]);

            $order = $existingDraftOrder;
        } else {
            // No draft found, create new one
            $total = collect($invoices)->sum('amount');
            $orderId = Order::generateOrderId('resident');

            $order = Order::create([
                'order_number' => $orderId,
                'invoice_number' => $invoiceJson,
                'user_id' => $userId,
                'origin_url' => 'resident/payment',
                'amount' => $total,
                'purpose' => 'invoice_payment',
                'status' => 'draft',
                'metadata' => $invoices
            ]);
        }



        // $total = collect($invoices)->sum('amount');

        // // Generate order ID
        // $orderId = Order::generateOrderId('resident');

        // $resident_id = $invoices[0]['resId'];
        // $resident = Resident::findOrFail($resident_id);
        // if ($resident_id); {
        //     $resident = Resident::findOrFail($resident_id);
        //     $userId =  $resident->user_id;
        // }
        // // Create order with pending status
        // $order = Order::create([
        //     'order_number'   => $orderId,
        //     // 'resident_id'    => $invoices[0]['resId'] ?? null,
        //     'user_id' => $userId,
        //     'amount'         => $total,
        //     'purpose'        => 'invoice_payment',
        //     'status'         => 'pending',
        //     'metadata'       => $invoices, // store full invoice payload
        // ]);

        return response()->json([
            'success' => true,
            'message' => 'Order processed successfully.',
            'data' => [
                'order_id'   => $order->id,
                'reference'   => $order->reference_id,
                'order_no'   => $order->order_number,
                'total'      => $order->amount,
                'invoices'   => $order->metadata
            ]
        ]);
    }

    // public function confirmPay($order)
    // {
    //     // Log::info('payment confirm to pay', json_encode($order));
    //           Log::info('payment confirm to pay');
    //     $order = Order::with('resident', 'invoices')->where('reference_id', $order)->first();

    //     Log::info('payment confirm to pay'. json_encode($order));

    //     if (!$order) {
    //         return response()->json(['success' => false, 'message' => 'Order not found'], 404);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'data' => $order
    //     ]);
    // }

    public function confirmPay($orderRef)
    {
        try {
            // Load order with resident and invoices/items
            $order = Order::with([
                'resident',        // linked via user_id
                'invoices.items'   // each invoice can have multiple items
            ])->where('reference_id', $orderRef)->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.'
                ], 404);
            }

            // Determine invoices to show
            $invoices = collect();
            if ($order->status === 'draft' && !empty($order->invoice_number)) {
                $invoices = Invoice::with('items')
                    ->whereIn('invoice_number', $order->invoice_number)
                    ->get();
            } else {
                $invoices = $order->invoices()->with('items')->get();
            }
            // Log::info('Invoices fetched for order', [
            //     'order_ref' => $orderRef,
            //     'invoice_count' => $invoices
            // ]);

            // Calculate total amount
            $totalAmount = $invoices->sum('total_amount');

            // Format invoice data for response
            $invoiceData = $invoices->map(function ($invoice) {
                return [
                    'invoice_number' => $invoice->invoice_number,
                    'amount'         => $invoice->total_amount ?? $invoice->amount,
                    'remark'         => $invoice->remark,
                    'items'          => $invoice->items->map(function ($item) {
                        return [
                            'name'   => $item->description ?? $item->name,
                            'qty'    => $item->qty ?? 1,
                            'rate'   => $item->price ?? $item->rate,
                            'amount' => $item->total_amount ?? $item->amount,
                        ];
                    })
                ];
            });

            // Log for debugging
            // Log::info('Payment confirmation loaded', [
            //     'order_ref' => $orderRef,
            //     'invoices'  => $invoiceData
            // ]);

            // Return structured JSON response
            return response()->json([
                'success' => true,
                'message' => 'Order confirmation loaded.',
                'data' => [
                    'order_number' => $order->order_number,
                    'amount'       => $totalAmount,
                    'status'       => $order->status,
                    'purpose'      => $order->purpose,
                    'resident'     => $order->resident, // linked via user_id
                    'invoices'     => $invoiceData
                ]
            ]);
        } catch (\Throwable $e) {
            Log::error('Error fetching order confirmation', [
                'order_ref' => $orderRef,
                'error'     => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while loading order confirmation.'
            ], 500);
        }
    }





    // public function initiateResidentPayment(Request $request)
    // {
    //     Log::info($request->all());
    //     $userId = $request->header('auth-id') ?? null;
    //     Log::info($userId);
    //     // ✅ Validate first
    //     $validatedData = $request->validate([
    //         'resident_id' => 'required|exists:residents,id',
    //         'amount'   => 'required|numeric|min:1',
    //         // 'user_id' => 'nullable|exists:users,id',
    //         'invoice_number' => 'nullable|exists:invoices,invoice_number',
    //         'remark' => 'nullable',
    //     ]);

    //     $user = Helper::get_auth_admin_user($request);
    //     $userType = $user ? 'resident' : 'guest';
    //     // Log::info('userType: ' . json_encode($userType));


    //     // Try to get invoice_number from request
    //     $invoiceIds = [];
    //     $invoiceInput = $validatedData['invoice_number'] ?? null;

    //     Log::info('invoice Number', json_encode($invoiceInput));
    //     if ($invoiceInput) {
    //         // Normalize to array
    //         $invoiceArray = is_array($invoiceInput) ? $invoiceInput : [$invoiceInput];

    //         // Fetch valid invoice numbers from DB
    //         $validInvoices = \App\Models\Invoice::where('resident_id', $validatedData['resident_id'])
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



    //     // metadata
    //     $metaData = $request->input('metadata');
    //     if (!$metaData || !is_array($metaData)) {
    //         $metaData = $validatedData;
    //     }

    //     // Generate order ID
    //     $orderId = Order::generateOrderId($userType);
    //     //  Log::info('orderId: ' . json_encode($orderId));

    //     // Create the order
    //     $order = Order::create([
    //         'order_number'       => $orderId,
    //         'invoice_number' => $validatedData['invoice_number'] ?? rand(1000, 9999),
    //         'user_id'        => $userId ?? null,
    //         'resident_id'    => $validatedData['resident_id'] ?? null,
    //         'amount'         => $validatedData['amount'],
    //         'purpose'        => $validatedData['remark'] ?? 'general',
    //         'origin_url'     => $request->input('origin_url') ?? null,
    //         'redirect_url'   => $request->input('redirect_url') ?? null,
    //         'callback_route' => $request->input('callback_route') ?? route('resident.payment.callback'),
    //         'status'         => 'pending',
    //         'metadata'       => $metaData ?? [],
    //     ]);

    //     $pivotData = [];

    //     foreach ($invoiceIds as $invoiceId) {
    //         $pivotData[$invoiceId] = [
    //             'amount_paid' => 0,
    //             'paid_at' => null,
    //         ];
    //     }

    //     $order->invoices()->attach($pivotData);

    //     Log::info('order Details: ' . json_encode($order));
    //     $callbackUrl = "resident/payment/callback";
    //     // ✅ Call Paytm Service
    //     $result = $this->paytmService->initiateTransaction(
    //         $order->order_id,   // make sure you use the unique Paytm order_id field
    //         $userId,
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

    public function initiateResidentPayment(Request $request)
    {

        try {
            // Log::info('Initiate Payment Request', $request->all());

            $userId = $request->header('auth-id');
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: user missing.'
                ], 401);
            }

            // Validate
            $validatedData = $request->validate([
                'order_no' => 'required|string|exists:orders,order_number',
            ]);

            // Log::info('validated');
            // Load the draft order
            $order = Order::with('invoices')->where('order_number', $validatedData['order_no'])->first();
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.'
                ], 404);
            }

            // Log::info('Order Found' . json_encode($order));
            // Only block if order is already completed or failed
            if (in_array($order->status, ['completed', 'failed', 'paid'])) {
                // Log::info("Order already processed or completed." . $order->status);
                return response()->json([
                    'success' => false,
                    'message' => 'Order already processed or completed.'
                ], 400);
            }

            // Attach invoices if not attached yet
            if ($order->invoices->isEmpty() && !empty($order->invoice_number)) {
                $invoices = Invoice::whereIn('invoice_number', $order->invoice_number)->get();
                $pivotData = [];
                // Log::info("Invoices", ['data' => $invoices]);
                foreach ($invoices as $inv) {
                    $pivotData[$inv->id] = [
                        'amount_paid' => 0,
                        'paid_at'     => null
                    ];
                }
                $order->invoices()->attach($pivotData);
            }

            // Update order status to pending before payment
            // If the order is draft, set it to pending before payment
            if ($order->status === 'draft') {
                $order->update(['status' => 'pending']);
            }


            // Log::info('order Details: ' . json_encode($order));

            // Initiate Paytm (or other gateway) transaction
            $callbackUrl = $request->input('callback_url') ?? route('resident.payment.callback');
            // ✅ Call Paytm Service
            $result = $this->paytmService->initiateTransaction(
                $order->order_number,   // make sure you use the unique Paytm order_id field
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Payment initiation error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate payment. ' . $e->getMessage()
            ], 500);
        }
    }


    // public function resPayCallback(Request $request)
    // {
    //     Log::info("Paytm callback received for resident acc", $request->all());
    //     // // Pass the payload (Request object) directly
    //     // $result = $this->paytmService->verifyCallback($request);
    //     $orderId = null; // ✅ Declare early

    //     try {

    //         // Pass the payload (Request object) directly
    //         $result = $this->paytmService->verifyCallback($request);
    //         Log::info('recived verify result', $result);

    //         if (!$result['valid']) {
    //             Log::warning('Invalid Paytm signature', ['payload' => $result['data']]);
    //             $orderId = $result['data']['ORDERID'] ?? null;

    //             return $this->respondWithError(
    //                 'Invalid payment response. Please try again.',
    //                 $orderId,
    //                 $request
    //             );
    //         }

    //         $orderId = $result['data']['ORDERID'] ?? null;
    //         $txnId = $result['data']['TXNID'] ?? null;
    //         $order = Order::where('order_number', $orderId)->first();

    //         if (!$order) {
    //             Log::error('Order not found', ['order_id' => $orderId]);
    //             return $this->respondWithError('Order not found. Please contact support.', $orderId, $request);
    //         }

    //         $status = $result['data']['STATUS'] ?? '';

    //         Log::info('payment_status' . $status);

    //         $order->update([
    //             'status' => $status,
    //             'message' => $result['data']['RESPMSG'] ?? null,
    //             'payment_method' => $result['data']['PAYMENTMODE'] ?? null,
    //         ]);

    //         Log::info('Order Updated successfully', $order->toArray());

    //         // if ($status === 'TXN_FAILURE') {
    //         //     Log::info('Transaction failed', ['order_id' => $order->id]);
    //         //     return $this->respondWithError('Transaction failed. Please retry or check with your bank.', $order->id, $request);
    //         // }

    //         if ($status === 'TXN_FAILURE') {
    //             Log::info('Transaction failed', [
    //                 'order_id' => $order->id,
    //                 'reason' => $result['data']['RESPMSG'] ?? 'Unknown failure'
    //             ]);

    //             return $this->respondWithError(
    //                 $result['data']['RESPMSG'] ?? 'Transaction failed. Please retry or check with your bank.',
    //                 $order->id,
    //                 $request
    //             );
    //         }

    //         $transaction = Transaction::create([
    //             'order_id' => $order->id,
    //             'txn_id' => $txnId ?? $result['data']['TXNID'] ?? null,
    //             'status' => $status,
    //             'bank_txn_id' => $result['data']['BANKTXNID'] ?? null,
    //             'txn_amount' => $result['data']['TXNAMOUNT'] ?? null,
    //             'payment_mode' => $result['data']['PAYMENTMODE'] ?? null,
    //             'bank_name' => $result['data']['BANKNAME'] ?? null,
    //             'currency' => $result['data']['CURRENCY'] ?? null,
    //             'm_id' => $result['data']['MID'] ?? null,
    //             'response_code' => $result['data']['RESPCODE'] ?? null,
    //             'response_message' => $result['data']['RESPMSG'] ?? null,
    //             'response_payload' => json_encode($result['data']),
    //         ]);

    //         // $order->update([
    //         //     'status' => $status,
    //         //     'message' => $result['data']['RESPMSG'] ?? null,
    //         //     'payment_method' => $result['data']['PAYMENTMODE'] ?? null,

    //         // ]);

    //         Log::info('Transaction recorded successfully', $transaction->toArray());

    //         Log::info('order_id' . $orderId);
    //         // Log::info('redirect_url' . $$order->redirect_url);
    //         // Log::info('user_data', json_decode($order->user_data, true));

    //         $retryUrl = url("/api/resident/payment");

    //         $responseData = [
    //             'payment_status' => $status,
    //             'order_id' => $order->order_id,
    //             'transaction_id' => $transaction->txn_id,
    //             'amount' => $transaction->txn_amount,
    //             'payment_mode' => $transaction->payment_mode,
    //             'redirect_url' => $order->redirect_url ?? $retryUrl,
    //             'user_data' => json_decode($order->user_data, true),
    //         ];

    //         // Log::info('response after transaction' . $responseData);

    //         Log::info('Response data before redirect', ['responseData' => $responseData]);

    //         // ✅ Or replace with:
    //         Log::info('Redirecting after successful transaction');

    //         $message = 'Payment successful';

    //         if ($transaction->status === 'TXN_SUCCESS') {
    //             try {
    //                 Log::info("Processing successful payment for order_id: {$orderId}");



    //                 $txnId = optional($order->transaction)->txn_id ?? $txnId ?? rand(1000, 9999);


    //                 Log::info("sending new request fo accessory" . json_encode($txnId, $order->payment_mode, $order->user_id));
    //                 // Delegate directly to GuestController::guestPayment

    //                 $userId = $order->user_id;
    //                 $residentId = $order->resident_id;
    //                 $invoiceNumber = $order->invoice_number;

    //                 Log::info("User Id :" . json_encode($userId));
    //                 Log::info("Resident Id :" . json_encode($residentId));
    //                 Log::info("Invoice number :" . json_encode($invoiceNumber));

    //                 $StudentAccessoryController = app(StudentAccessoryController::class);
    //                 $StudentAccessoryController->payAccessory(
    //                     new Request([

    //                         'user_id'       => $userId,
    //                         'transaction_id' => $txnId,
    //                         'payment_method' => $order->payment_mode ?? 'Other', // Cash,UPI,Bank Transfer,Card,Other',
    //                         'amount'        =>  $order->amount,

    //                     ]),
    //                     $invoiceNumber,
    //                     $residentId
    //                     // or whatever value represents the invoice ID
    //                 );

    //                 // $newRequest = new Request([
    //                 //     'transaction_id' => $txnId,
    //                 //     'payment_method' => $order->payment_mode ?? 'Other',
    //                 //     'amount'         => $order->amount,
    //                 // ]);

    //                 // // $newRequest->headers->set('auth-id', $userId);
    //                 // $StudentAccessoryController = app(StudentAccessoryController::class);
    //                 // $StudentAccessoryController->payAccessory($newRequest, $order->invoice_number, $userId);


    //                 Log::error("PaymentHandler processed");

    //                 // $handler = app(PaymentHandler::class);
    //                 // $finalResult = $handler->handle($order);


    //             } catch (Exception $ex) {
    //                 Log::error("PaymentHandler failed for order_id {$orderId}", [
    //                     'error' => $ex->getMessage(),
    //                     'trace' => $ex->getTraceAsString()
    //                 ]);
    //             }
    //         } else {
    //             Log::warning("Payment failed or pending for order_id: {$orderId}", [
    //                 'status' => $transaction->status
    //             ]);
    //         }

    //         Log::info('Final Result');

    //         // return redirect()->away(config('app.frontend_url') . '/resident/payment-success?' . http_build_query([
    //         // return redirect()->away('resident/pay/status?' . http_build_query([
    //         return redirect()->away(config('services.auth.url') . '/resident/payment/pay/status?' . http_build_query([
    //             'order_id' => $result['data']['ORDERID'] ?? null,
    //             'txn_id'   => $result['data']['TXNID'] ?? null,
    //             'amount'  => $result['data']['TXNAMOUNT'] ?? null,
    //             'status'  => 'success',
    //         ]));

    //         // return redirect()->to("payment/status?order_id=" . $orderId);


    //     } catch (Throwable $e) {
    //         Log::debug('Error occurred in respondWithError()', ['orderId' => $orderId]);

    //         Log::critical('Unexpected error during Paytm callback', [
    //             'exception' => $e->getMessage(),
    //             'order_id' => $orderId,
    //             'payload' => $request->all(),
    //         ]);


    //         return $this->respondWithError('Something went wrong. Please try again later.', null, $request);
    //     }


    //     if ($result['valid'] && ($result['data']['STATUS'] ?? '') === 'TXN_SUCCESS') {
    //         // Redirect to frontend success page
    //         return redirect()->away(config('app.frontend_url') . '/guest/payment-success?' . http_build_query([
    //             'order_id' => $result['data']['ORDERID'] ?? null,
    //             'txn_id'   => $result['data']['TXNID'] ?? null,
    //             'amount'  => $result['data']['TXNAMOUNT'] ?? null,
    //             'status'  => 'success',
    //         ]));
    //     }

    //     // Redirect to frontend failure page
    //     return redirect()->away(config('app.frontend_url') . '/payment-failure?' . http_build_query([
    //         'order_id' => $result['data']['ORDERID'] ?? null,
    //         'txn_id'   => $result['data']['TXNID'] ?? null,
    //         'amount'  => $result['data']['TXNAMOUNT'] ?? null,
    //         'status'  => 'failed',
    //         'message' => $result['error'] ?? 'Checksum verification failed',
    //     ]));
    // }


    public function resPayCallback(Request $request)
    {
        // Log::info("Paytm callback received for resident account", $request->all());
        $orderId = null;

        try {
            // Verify Paytm payload
            $result = $this->paytmService->verifyCallback($request);
            // Log::info('Verification result', $result);

            $orderId = $result['data']['ORDERID'] ?? null;

            if (!$result['valid']) {
                Log::warning('Invalid Paytm signature', ['payload' => $result['data'] ?? []]);
                return $this->respondsWithError('Invalid payment response. Please try again.', $orderId, $request);
            }

            $status = $result['data']['STATUS'] ?? '';
            $txnId  = $result['data']['TXNID'] ?? null;

            $order = Order::where('order_number', $orderId)->first();

            if (!$order) {
                Log::error('Order not found', ['order_id' => $orderId]);
                return $this->respondsWithError('Order not found. Please contact support.', $orderId, $request);
            }

            // Update order status
            $order->update([
                'status' => 'paid',
                'message' => $result['data']['RESPMSG'] ?? null,
                'payment_method' => $result['data']['PAYMENTMODE'] ?? null,
            ]);
            // Log::info('Order updated successfully', $order->toArray());

            // Record transaction
            $transaction = Transaction::create([
                'order_id' => $order->id,
                'txn_id' => $txnId,
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
            // Log::info('Transaction recorded', $transaction->toArray());

            // Handle payment success
            if ($status === 'TXN_SUCCESS') {
                $this->processSuccessfulPayment($order, $transaction);
                $redirectUrl = $order->redirect_url ?? config('services.auth.url') . '/resident/payment/pay/status';
                return redirect()->away($redirectUrl . '?' . http_build_query([
                    'order_id' => $order->order_number,
                    'txn_id'   => $txnId,
                    'amount'   => $order->amount,
                    'status'   => 'success',
                ]));
            }

            // Handle payment failure
            if ($status === 'TXN_FAILURE') {
                Log::warning('Transaction failed', [
                    'order_id' => $order->id,
                    'reason' => $result['data']['RESPMSG'] ?? 'Unknown',
                ]);
                // return $this->respondsWithError($result['data']['RESPMSG'] ?? 'Transaction failed. Please retry.', $order->id, $request);
                // $this->processSuccessfulPayment($order, $transaction);
                $redirectUrl = $order->redirect_url ?? config('services.auth.url') . '/resident/payment/pay/status';
                return redirect()->away($redirectUrl . '?' . http_build_query([
                    'order_id' => $order->order_number,
                    'txn_id'   => null,
                    'amount'   => $order->amount,
                    'status'   => 'payment failed',
                ]));
            }

            // Pending or unknown status
            // Log::info('Transaction pending or unknown', ['order_id' => $order->id, 'status' => $status]);
            return $this->respondsWithError('Transaction is pending. Please check your payment status later.', $order->id, $request);
        } catch (\Throwable $e) {
            Log::critical('Unexpected error during Paytm callback', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'order_id' => $orderId,
                'payload' => $request->all(),
            ]);
            return $this->respondsWithError('Something went wrong. Please try again later.', $orderId, $request);
        }
    }

    /**
     * Process successful payment: delegate accessories, notify controllers, etc.
     */
    // protected function processSuccessfulPayment(Order $order, Transaction $transaction)
    // {
    //     try {
    //         Log::info("Processing successful payment for order_id: {$order->order_number}");

    //         $StudentAccessoryController = app(StudentAccessoryController::class);
    //         $StudentAccessoryController->payAccessory(
    //             new Request([
    //                 'user_id'        => $order->user_id,
    //                 'transaction_id' => $transaction->txn_id,
    //                 'payment_method' => $order->payment_method ?? 'Other',
    //                 'amount'         => $order->amount,
    //             ]),
    //             $order->invoice_number,
    //             $order->resident_id
    //         );

    //         Log::info("Accessory payment processed successfully for order_id: {$order->order_number}");
    //     } catch (\Throwable $ex) {
    //         Log::error("Failed to process successful payment for order_id {$order->order_number}", [
    //             'error' => $ex->getMessage(),
    //             'trace' => $ex->getTraceAsString()
    //         ]);
    //     }
    // }


    protected function processSuccessfulPayment(Order $order, Transaction $transaction)
    {
        try {
            // Log::info("Processing successful payment for order_id: {$order->order_number}");

            // ✅ Update related invoices: mark as paid or adjust remaining_amount
            $invoices = $order->invoices()->withPivot('amount_paid')->get();
            foreach ($invoices as $invoice) {
                $amountToPay = $invoice->pivot->amount_paid ?? 0;
                $invoice->pivot->amount_paid = $invoice->total_amount; // full payment
                $invoice->pivot->paid_at = now();
                $invoice->pivot->save();

                $invoice->update([
                    'paid_amount' => $invoice->total_amount,
                    'remaining_amount' => 0,
                    'status' => 'paid',
                ]);

                // Log::info("Invoice {$invoice->invoice_number} updated as paid");
            }

            // // ✅ Delegate accessory payments
            // $StudentAccessoryController = app(StudentAccessoryController::class);
            // $StudentAccessoryController->payAccessory(
            //     new Request([
            //         'user_id'        => $order->user_id,
            //         'transaction_id' => $transaction->txn_id,
            //         'payment_method' => $order->payment_method ?? 'Other',
            //         'amount'         => $order->amount,
            //     ]),
            //     $order->invoice_number,
            //     $order->resident_id
            // );

            // Log::info("Accessory payment processed successfully for order_id: {$order->order_number}");
        } catch (\Throwable $ex) {
            Log::error("Failed to process successful payment for order_id {$order->order_number}", [
                'error' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString()
            ]);

            // Optionally: notify admin or trigger fallback handling
        }
    }

    protected function processSuccessfulPaymentOffline(Order $order, Transaction $transaction)
    {
        DB::beginTransaction();
        try {
            // Log::info("💰 Processing successful payment for order_id: {$order->order_number}");

            // ✅ Fetch related invoices
            $invoices = $order->invoices()->withPivot('amount_paid', 'paid_at')->get();

            foreach ($invoices as $invoice) {
                $txnAmount = $transaction->txn_amount ?? 0;
                $alreadyPaid = $invoice->paid_amount ?? 0;
                $totalAmount = $invoice->total_amount ?? 0;

                // ✅ Calculate new totals
                $newPaidAmount = $alreadyPaid + $txnAmount;
                $remaining = max(0, $totalAmount - $newPaidAmount);

                // ✅ Determine status
                $status = $remaining <= 0 ? 'paid' : 'partial';

                // ✅ Update pivot table
                $order->invoices()->updateExistingPivot($invoice->id, [
                    'amount_paid' => $invoice->pivot->amount_paid + $txnAmount,
                    'paid_at'     => now(),
                ]);

                $invoice->update([
                    'paid_amount' => $newPaidAmount,
                    'remaining_amount' => $remaining,
                    'status' => $status,
                ]);

                // Log::info("✅ Invoice {$invoice->invoice_number} updated — paid: {$newPaidAmount}, remaining: {$remaining}");
            }

            // ✅ Mark order as fully paid if all invoices are paid
            $allPaid = $order->invoices()->where('status', '!=', 'paid')->count() === 0;
            $orderStatus = 'paid';
            $order->update(['status' => $orderStatus]);

            DB::commit();
            // Log::info("🎉 Payment processing completed for order {$order->order_number}");
        } catch (\Throwable $ex) {
            DB::rollBack();
            Log::error("❌ Failed to process payment for order {$order->order_number}", [
                'error' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString(),
            ]);
        }
    }


    protected function respondsWithError(string $message, ?int $orderId = null, ?Request $request = null)
    {
        Log::error('Payment callback error', [
            'order_id' => $orderId,
            'message'  => $message,
            'payload'  => $request ? $request->all() : null,
        ]);

        // If frontend URL is available, redirect user there
        $redirectUrl = config('services.auth.url') . '/resident/payment/pay/status';
        $query = [
            'order_id' => $orderId,
            'status'   => 'failed',
            'message'  => $message,
        ];

        // You can choose JSON response for API or redirect for browser
        if ($request && $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'order_id' => $orderId,
            ], 400);
        }

        // Default: redirect to frontend failure page
        return redirect()->away($redirectUrl . '?' . http_build_query($query));
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

    // public function ResidentPaymentStatus(Request $request)
    // {
    //     Log::info('📥 Fetching Resident Payment Receipt');

    //     Log::info('Request ' . json_encode($request->all()));
    //     // Step 1: Validate request
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

    //     // Step 2: Authenticate resident via user_id
    //     $userId = $request->header('auth-id');
    //     if (!$userId) {
    //         Log::warning('⚠️ Missing auth-id header');
    //         return response()->json(['success' => false, 'message' => 'Missing auth-id header'], 400);
    //     }

    //     try {
    //         $resident = Resident::with('accessories')->where('user_id', $userId)->firstOrFail();
    //         Log::info('👤 Resident Authenticated', ['resident_id' => $resident]);

    //         // Step 3: Find matching order
    //         $order = Order::with(['transaction', 'invoices.items.accessory'])
    //             ->where('order_number', $request->order_id)
    //             ->where('user_id', $resident->id)
    //             ->whereHas('transaction', function ($query) use ($request) {
    //                 $query->where('txn_amount', $request->txnAmount);
    //             })
    //             ->first();

    //         Log::info('Order ' . json_encode($order));

    //         // ->where('resident_id', $resident->id)
    //         // ->where('invoice_number', $request->txnId) // assuming txnId maps to invoice_number
    //         // ->whereHas('transaction', function ($query) use ($request) {
    //         //     $query->where('txn_amount', $request->txnAmount);
    //         // })
    //         // ->first();

    //         // $order = Order::with(['transactions', 'invoices->items->accessory'])
    //         //     ->where('order_id', $request->order_id)
    //         //     ->where('resident_id', $resident->id)
    //         //     ->where('invoice_number', $request->txnId) // assuming txnId maps to invoice_number
    //         //     ->whereHas('transactions', function ($query) use ($request) {
    //         //         $query->where('txn_amount', $request->txnAmount);
    //         //     })
    //         //     ->first();

    //         // Log::info('Order with transactions ' .json_encode($order));
    //         if (!$order) {
    //             Log::warning('⚠️ No matching order found');
    //             return response()->json(['success' => false, 'message' => 'Order not found'], 404);
    //         }

    //         // Step 4: Map accessories
    //         // $accessories = $resident->accessories->map(function ($item) {
    //         //     return [
    //         //         'name'          => $item->description ?? 'N/A',
    //         //         'qty'           => $item->qty ?? 0,
    //         //         'price'         => $item->price ?? 0,
    //         //         'from_date'     => $item->from_date ?? null,
    //         //         'to_date'       => $item->to_date ?? null,
    //         //         'total_amount'  => $item->total_amount ?? 0,
    //         //         'is_default'    => $item->is_default ?? false,
    //         //     ];
    //         // });

    //         // Step 3: Validate transaction and amount
    //         if (
    //             $order->transaction->txn_amount != $request->txnAmount
    //             // ||
    //             // $order->transaction->txn_id != $request->txnId
    //         ) {
    //             Log::warning('⚠️ Transaction mismatch');
    //             return response()->json(['success' => false, 'message' => 'Transaction details mismatch'], 400);
    //         }

    //         // Step 4: Structure accessories from invoice items
    //         $accessories = collect();
    //         foreach ($order->invoices->items ?? [] as $item) {
    //             if ($item->item_type === 'accessory' && $item->accessory) {
    //                 $accessories->push([
    //                     'name'          => $item->accessory->name ?? $item->description,
    //                     'description'   => $item->description,
    //                     'qty'           => 1,
    //                     'price'         => $item->price,
    //                     'from_date'     => $item->from_date,
    //                     'to_date'       => $item->to_date,
    //                     'total_amount'  => $item->total_amount,
    //                     'is_default'    => (bool) ($item->accessory->is_default ?? false),
    //                     'accessory_meta' => [
    //                         'accessory_id' => $item->accessory->id,
    //                         'head_id'      => $item->accessory->accessory_head_id,
    //                         'is_active'    => (bool) $item->accessory->is_active,
    //                         'created_by'   => $item->accessory->created_by,
    //                         'created_at'   => $item->accessory->created_at,
    //                     ]
    //                 ]);
    //             }
    //         }

    //         Log::info('accessory ' . json_encode($accessories));


    //         // Step 5: Build structured response
    //         $response = [
    //             'success' => true,
    //             'status'    => $order->status,
    //             'txn_id'    => $order->transaction->txn_id ?? $request->txnId,
    //             'amount'    => $order->transaction->txn_amount ?? $request->txnAmount,
    //             'resident' => [
    //                 'name'       => $resident->name,
    //                 'scholar_no'       => $resident->scholar_no,
    //                 'email'       => $resident->email,
    //                 'mobile'       => $resident->number,
    //                 'gender'       => $resident->gender,
    //                 'bed_id'  => $resident->bed_id,
    //                 'status'  => $resident->status,
    //             ],
    //             'order' => [
    //                 'order_id'       => $order->order_id,
    //                 'invoice_number' => $order->invoice_number,
    //                 'amount'         => $order->amount,
    //                 'status'         => $order->status,
    //                 'message'        => $order->message,
    //                 'purpose'        => $order->purpose,
    //             ],
    //             'transaction' => [
    //                 'txn_id'           => $order->transaction->txn_id,
    //                 'status'           => $order->transaction->status,
    //                 'bank_name'        => $order->transaction->bank_name,
    //                 'payment_mode'     => $order->transaction->payment_mode,
    //                 'txn_amount'       => $order->transaction->txn_amount,
    //                 'currency'         => $order->transaction->currency,
    //                 'response_code'    => $order->transaction->response_code,
    //                 'response_message' => $order->transaction->response_message,
    //                 'merchant_id'      => $order->transaction->m_id,
    //                 'created_at'       => $order->transaction->created_at,
    //             ],
    //             'invoice' => [
    //                 'invoice_id'      => $order->invoices->id ?? null,
    //                 'invoice_number'  => $order->invoices->invoice_number ?? null,
    //                 'resident_id'     => $order->invoices->resident_id ?? null,
    //                 'invoice_date'    => $order->invoices->invoice_date ?? null,
    //                 'due_date'        => $order->invoices->due_date ?? null,
    //                 'total_amount'    => $order->invoices->total_amount ?? null,
    //                 'paid_amount'     => $order->invoices->paid_amount ?? null,
    //                 'remaining_amount' => $order->invoices->remaining_amount ?? null,
    //                 'remarks'         => $order->invoices->remarks ?? null,
    //                 'status'          => $order->invoices->status ?? null,
    //             ],
    //             'accessories' => $accessories->values()
    //         ];

    //         Log::info('✅ Structured Receipt Response', $response);
    //         return response()->json($response);

    //         // // Step 5: Build response
    //         // $response = [
    //         //     'success'   => true,
    //         //     'status'    => $order->status,
    //         //     'txn_id'    => $order->transaction->txn_id ?? $request->txnId,
    //         //     'amount'    => $order->transaction->txn_amount ?? $request->txnAmount,
    //         //     'order_id'  => $order->order_id,
    //         //     'invoice_number' => $order->invoice_number,
    //         //     'resident'  => [
    //         //         'name'             => $resident->name,
    //         //         'email'            => $resident->email,
    //         //         'number'           => $resident->number,
    //         //         'gender'           => $resident->gender,
    //         //         'scholar_no'       => $resident->scholar_no,
    //         //         'course'           => $resident->course,
    //         //         'fathers_name'     => $resident->fathers_name,
    //         //         'mothers_name'     => $resident->mothers_name,
    //         //         'parent_no'        => $resident->parent_no,
    //         //         'guardian_name'    => $resident->local_guardian_name,
    //         //         'guardian_no'      => $resident->guardian_no,
    //         //         'emergency_no'     => $resident->emergency_no,
    //         //         'stay_duration'    => $resident->months,
    //         //     ],
    //         //     'accessories' => $accessories,
    //         // ];

    //         // Log::info('✅ Receipt Response Ready', $response);
    //         // return response()->json($response);

    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         Log::error('❌ Resident not found', ['user_id' => $userId]);
    //         return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    //     } catch (\Exception $e) {
    //         Log::critical('🔥 Unexpected Error', ['error' => $e->getMessage()]);
    //         return response()->json(['success' => false, 'message' => 'Internal server error'], 500);
    //     }
    // }


    public function ResidentPaymentStatus(Request $request)
    {
        // Log::info('📥 Fetching Resident Payment Receipt');
        // Log::info('Request Received', $request->all());

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
            // Log::info('👤 Resident Authenticated', ['resident_id' => $resident->id]);

            // Step 3: Fetch order with transaction and invoice items
            $order = Order::with(['transaction', 'invoices.items.accessory'])
                ->where('order_number', $request->order_id)
                ->where('user_id', $userId)
                ->whereHas('transaction', function ($query) use ($request) {
                    $query->where('txn_amount', $request->txnAmount);
                })
                ->first();

            if (!$order) {
                Log::warning('⚠️ No matching order found');
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            // Step 4: Validate transaction amount
            if ($order->transaction->txn_amount != $request->txnAmount) {
                Log::warning('⚠️ Transaction mismatch');
                return response()->json(['success' => false, 'message' => 'Transaction details mismatch'], 400);
            }

            // Step 5: Structure invoice items
            $items = collect();
            $accessories = collect();

            foreach ($order->invoices as $invoice) {
                foreach ($invoice->items ?? [] as $item) {
                    $items->push([
                        'invoice_number' => $invoice->invoice_number,
                        'description'    => $item->description,
                        'remarks'        => $item->remarks,
                        'price'          => (float) $item->price,
                        'total_amount'   => (float) $item->total_amount,
                        'item_type'      => $item->item_type,
                        'accessory_info' => $item->accessory ? [
                            'accessory_id' => $item->accessory->id,
                            'head_id'      => $item->accessory->accessory_head_id,
                            'is_active'    => (bool) $item->accessory->is_active,
                            'created_by'   => $item->accessory->created_by,
                            'created_at'   => $item->accessory->created_at,
                        ] : null,
                    ]);

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
            }

            // Step 6: Build structured response
            $response = [
                'success' => true,
                'status'  => $order->status,
                'txn_id'  => $order->transaction->txn_id ?? $request->txnId,
                'amount'  => $order->transaction->txn_amount ?? $request->txnAmount,

                'resident' => [
                    'name'        => $resident->name,
                    'scholar_no'  => $resident->scholar_no,
                    'email'       => $resident->email,
                    'mobile'      => $resident->number,
                    'gender'      => $resident->gender,
                    'bed_id'      => $resident->bed_id,
                    'status'      => $resident->status,
                ],

                'order' => [
                    'order_number'        => $order->order_number,
                    'invoice_numbers' => $order->invoice_number,
                    'amount'          => $order->amount,
                    'status'          => $order->status,
                    'message'         => $order->message,
                    'purpose'         => $order->purpose,
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

                'invoices' => $order->invoices->map(function ($inv) {
                    return [
                        'invoice_id'       => $inv->id,
                        'invoice_number'   => $inv->invoice_number,
                        'resident_id'      => $inv->resident_id,
                        'invoice_date'     => $inv->invoice_date,
                        'due_date'         => $inv->due_date,
                        'total_amount'     => $inv->total_amount,
                        'paid_amount'      => $inv->paid_amount,
                        'remaining_amount' => $inv->remaining_amount,
                        'remarks'          => $inv->remarks,
                        'status'           => $inv->status,
                    ];
                }),

                'items'       => $items->values(),
                'accessories' => $accessories->values()
            ];

            // Log::info('✅ Structured Receipt Response', $response);
            return response()->json($response);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('❌ Resident not found', ['user_id' => $userId]);
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        } catch (\Exception $e) {
            Log::critical('🔥 Unexpected Error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Internal server error'], 500);
        }
    }

    public function submitPayment(Request $request)
    {
        // Log::info('📥 Submitting Resident Payment', ['data' => $request->all()]);

        try {
            // ✅ Validate request data
            $validated = $request->validate([
                'amount' => 'required|numeric',
                'transaction_id' => 'required|string',
                'transaction_date' => 'required|date',
                'narration' => 'nullable|string',
                'invoice_id' => 'required|integer|exists:invoices,id',
            ]);

            // ✅ Find Invoice
            $invoice = Invoice::find($validated['invoice_id']);
            if (!$invoice) {
                return response()->json(['success' => false, 'message' => 'Invoice not found'], 404);
            }

            $invoiceJson = Order::prepareInvoiceNumber($invoice->invoice_number);

            // ✅ Prepare metadata
            $metaData = $request->input('metadata');
            if (!$metaData || !is_array($metaData)) {
                $metaData = $validated;
            }

            // ✅ Create new order
            $orderId = Order::generateOrderId('resident');
            $order = Order::create([
                'order_number' => $orderId,
                'invoice_number' => $invoiceJson,
                'user_id' => $invoice->resident->user_id,
                'origin_url' => 'resident/payment',
                'amount' => $validated['amount'],
                'purpose' => 'invoice_payment',
                'status' => 'draft',
                'metadata' => $metaData
            ]);

            // ✅ Attach invoices if needed
            if ($order->invoices->isEmpty() && !empty($order->invoice_number)) {
                $invoiceNumbers = is_array($order->invoice_number)
                    ? $order->invoice_number
                    : [$order->invoice_number];

                $invoices = Invoice::whereIn('invoice_number', $invoiceNumbers)->get();
                // Log::info("Invoices attached", ['count' => $invoices->count()]);

                $pivotData = [];
                foreach ($invoices as $inv) {
                    $pivotData[$inv->id] = [
                        'amount_paid' => 0,
                        'paid_at'     => null
                    ];
                }
                $order->invoices()->attach($pivotData);
            }

            // ✅ Update order as paid
            $order->update([
                'status' => 'paid',
                'message' => $validated['narration'] ?? null,
                'payment_method' => 'Cash',
            ]);

            // ✅ Create transaction
            $transaction = Transaction::create([
                'order_id' => $order->id,
                'txn_id' => $validated['transaction_id'],
                'status' => 'TXN_SUCCESS',
                'bank_txn_id' => $validated['transaction_id'],
                'txn_amount' => $validated['amount'],
                'payment_mode' => 'Cash',
                'currency' => 'INR',
                'response_code' => 'Txn Success',
                'response_payload' => json_encode($metaData),
            ]);

            // ✅ Process payment success
            if ($transaction->status === 'TXN_SUCCESS') {
                $this->processSuccessfulPaymentOffline($order, $transaction);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment submitted and processed successfully.',
                    'order_number' => $order->order_number,
                    'txn_id' => $transaction->txn_id,
                    'amount' => $transaction->txn_amount,
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Payment not successful.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('❌ Payment submission failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
}
