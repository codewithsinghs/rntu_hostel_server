<?php

// app/Services/PaymentHandler.php
namespace App\Services;

use Exception;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PaymentController;

class PaymentHandler
{
    public function handle(Order $order)
    {
        Log::info("Handling payment for order", [
            'order_id' => $order->order_id,
            'purpose'  => $order->purpose,
        ]);

        try {
            switch ($order->purpose) {
                case 'guest_payment':
                    // $this->processGuestPayment($order);
                        $this->processGuestPayment($order);
                    break;

                case 'admission':
                    $this->processAdmissionPayment($order);
                    break;

                case 'accessory':
                    $this->processAccessoryPayment($order);
                    break;

                default:
                    Log::warning("Unhandled payment purpose", [
                        'order_id' => $order->order_id,
                        'purpose'  => $order->purpose
                    ]);
            }
        } catch (Exception $e) {
            Log::error("PaymentHandler error", [
                'order_id' => $order->order_id,
                'purpose'  => $order->purpose ?? 'unknown',
                'error'    => $e->getMessage(),
            ]);
        }
    }

    protected function processGuestPayment(Order $order)
    {
        Log::info("Processing Guest Payment", [
            'order_id' => $order->order_id
        ]);

        try {
            $txnId = optional($order->transaction)->txn_id ?? rand(1000, 9999);

            // Delegate directly to GuestController::guestPayment
            $guestController = app(PaymentController::class);

            $guestController->guestPayment(new Request([
                'guest_id'       => $order->guest_id,
                'transaction_id' => $txnId,
                'payment_method' => $order->payment_mode ?? 'Other',
                'remarks'        => 'Paid via Paytm callback',
            ]));

            Log::info("Guest payment processed successfully", [
                'order_id' => $order->order_id,
                'txn_id'   => $txnId,
            ]);
            // Log::info("Guest payment processed", [
            //     'guest_id'     => $guest->id,
            //     'transaction'  => $transaction->txn_id,
            //     'total_amount' => $totalAmount,
            // ]);

            // return [
            //     'success' => true,
            //     'message' => 'Payment recorded successfully.',
            //     'data'    => [
            //         'guest'       => $guest,
            //         'paid_total'  => $totalAmount,
            //     ]
            // ];
        } catch (Exception $e) {
            Log::error("Guest payment failed", [
                'order_id' => $order->order_id,
                'error'    => $e->getMessage(),
            ]);
        }
    }




    protected function processAdmissionPayment(Order $order)
    {
        Log::info("Processing Admission Payment", [
            'order_id' => $order->order_id
        ]);
        // admission-specific logic...
    }

    protected function processAccessoryPayment(Order $order)
    {
        Log::info("Processing Accessory Payment", [
            'order_id' => $order->order_id
        ]);
        // accessory-specific logic...
    }



    // public function handle(Order $order): array
    // {
    //     Log::info("Handling payment for order", [
    //         'order_id' => $order->order_id,
    //         'purpose'  => $order->purpose,
    //     ]);

    //     try {
    //         switch ($order->purpose) {
    //             case 'guest_payment':
    //                 return $this->processGuestPayment($order);

    //             case 'admission':
    //                 return $this->processAdmissionPayment($order);

    //             case 'accessory':
    //                 return $this->processAccessoryPayment($order);

    //             default:
    //                 Log::warning("Unhandled payment purpose", [
    //                     'order_id' => $order->order_id,
    //                     'purpose'  => $order->purpose
    //                 ]);

    //                 return [
    //                     'success' => false,
    //                     'message' => "Unhandled payment purpose: {$order->purpose}",
    //                     'data'    => null,
    //                     'errors'  => ['purpose' => 'Unknown payment purpose'],
    //                 ];
    //         }
    //     } catch (Exception $e) {
    //         Log::error("PaymentHandler error", [
    //             'order_id' => $order->order_id,
    //             'purpose'  => $order->purpose ?? 'unknown',
    //             'error'    => $e->getMessage(),
    //         ]);

    //         return [
    //             'success' => false,
    //             'message' => "Error while processing payment.",
    //             'data'    => null,
    //             'errors'  => ['exception' => $e->getMessage()],
    //         ];
    //     }
    // }


    // protected function processGuestPayment(Order $order): array
    // {
    //     Log::info("Processing Guest Payment", [
    //         'order_id' => $order->order_id
    //     ]);

    //     try {
    //         $txnId = optional($order->transaction)->txn_id ?? rand(1000, 9999);

    //         // Delegate to PaymentController
    //         $guestController = app(PaymentController::class);

    //         $response = $guestController->guestPayment(new Request([
    //             'guest_id'       => $order->guest_id,
    //             'transaction_id' => $txnId,
    //             'payment_method' => $order->payment_mode ?? 'Other',
    //             'remarks'        => 'Paid via Paytm callback',
    //         ]));

    //         // If controller returns a JSON response, decode it
    //         if ($response instanceof \Illuminate\Http\JsonResponse) {
    //             $decoded = $response->getData(true);

    //             if ($decoded['success'] ?? false) {
    //                 Log::info("Guest payment processed successfully", [
    //                     'order_id' => $order->order_id,
    //                     'txn_id'   => $txnId,
    //                 ]);
    //             } else {
    //                 Log::warning("Guest payment reported failure", [
    //                     'order_id' => $order->order_id,
    //                     'txn_id'   => $txnId,
    //                     'response' => $decoded,
    //                 ]);
    //             }

    //             return $decoded;
    //         }

    //         // Fallback (if controller didn’t return JSON)
    //         return [
    //             'success' => true,
    //             'message' => 'Guest payment processed.',
    //             'data'    => null,
    //             'errors'  => null,
    //         ];
    //     } catch (Exception $e) {
    //         Log::error("Guest payment failed", [
    //             'order_id' => $order->order_id,
    //             'error'    => $e->getMessage(),
    //         ]);

    //         return [
    //             'success' => false,
    //             'message' => 'Guest payment failed.',
    //             'data'    => null,
    //             'errors'  => ['exception' => $e->getMessage()],
    //         ];
    //     }
    // }

    // protected function processAdmissionPayment(Order $order): array
    // {
    //     // TODO: implement similar to Guest
    //     return [
    //         'success' => true,
    //         'message' => 'Admission payment processed.',
    //         'data'    => null,
    //         'errors'  => null,
    //     ];
    // }

    // protected function processAccessoryPayment(Order $order): array
    // {
    //     // TODO: implement similar to Guest
    //     return [
    //         'success' => true,
    //         'message' => 'Accessory payment processed.',
    //         'data'    => null,
    //         'errors'  => null,
    //     ];
    // }
}
