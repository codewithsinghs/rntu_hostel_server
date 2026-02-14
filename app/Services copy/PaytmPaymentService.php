<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use paytm\paytmchecksum\PaytmChecksum;


class PaytmPaymentService
{
    // protected $config;
    protected $merchantId;
    protected $merchantKey;
    protected $website;
    protected $industryType;
    protected $channelId;
    protected $callbackUrl;

    public function __construct()
    {
        // $this->config = config('paytm');

        $this->merchantId = config('paytm.MID');
        $this->merchantKey = config('paytm.MERCHANT_KEY');
        $this->website = config('paytm.WEBSITE');
        $this->industryType = config('paytm.INDUSTRY_TYPE');
        $this->channelId = config('paytm.CHANNEL');
        $this->callbackUrl = config('paytm.CALLBACK_URL');
    }

    public function initiateTransaction($orderId, $customerId, $amount, $callback=null)
    {
        Log::info("Initiating Paytm transaction", [
            'orderId' => $orderId,
            'customerId' => $customerId,
            'amount' => $amount,
            'callbackUrl'  => $callback,
        ]);

       
        // $mid = "Resell00448805757124";
        // $key = "KXHUJH&Ywq9pUkkr";
        $mid = $this->merchantId;
        $key = $this->merchantKey;

        if (empty($callback) || $callback == null) {
            $callbackUrl = $this->callbackUrl;
        } else {
            $callbackUrl = $callback;
        }


        // $callbackUrl = $callback;

        $paytmParams = [
            "MID" => $mid,
            "ORDER_ID" => $orderId,
            "CUST_ID" => $customerId,
            "TXN_AMOUNT" => number_format($amount, 2, '.', ''),
            "CHANNEL_ID" => $this->channelId,
            "WEBSITE" => $this->website,
            "INDUSTRY_TYPE_ID" => $this->industryType,
            // "CALLBACK_URL" => $this->callbackUrl,
            // "CALLBACK_URL" => url('/paytm/callback')
            "CALLBACK_URL" => url($callbackUrl)
        ];
        Log::info("Paytm parameters prepared", $paytmParams);

        //  dd(die);

        // dd($params);
        // dd($this->merchantKey);
        // $checksum = PaytmChecksum::generateSignature($params, $this->merchantKey);
        // $params["CHECKSUMHASH"] = $checksum;
        $checksum = PaytmChecksum::generateSignature($paytmParams, $key);
        $paytmParams["CHECKSUMHASH"] = $checksum;
        // dd($params);
        // return $paytmParams;

        return [
            'txnUrl' => 'https://securegw.paytm.in/order/process', // or staging URL
            'body' => $paytmParams
        ];
    }

    // public function verifyCallback(array $payload)
    // {
    //     Log::info('verifying');
    //     $checksum = $payload['CHECKSUMHASH'] ?? '';
    //     unset($payload['CHECKSUMHASH']);

    //     $isValid = PaytmChecksum::verifySignature($payload, $this->merchantKey, $checksum);
    //     Log::info('verifying');
    //     return [
    //         'valid' => $isValid,
    //         'data' => $payload,
    //     ];
    // }

    // public function verifyCallback($payload)
    // {
    //     if ($payload instanceof \Illuminate\Http\Request) {
    //         $payload = $payload->all();
    //         Log::info('Received Request object for Paytm callback');
    //     } elseif (!is_array($payload)) {
    //         Log::error('Invalid payload type for Paytm callback', ['type' => gettype($payload)]);
    //         return [
    //             'valid' => false,
    //             'data' => [],
    //         ];
    //     } else {
    //         Log::info('Received array payload for Paytm callback');
    //     }

    //     $checksum = $payload['CHECKSUMHASH'] ?? '';
    //     unset($payload['CHECKSUMHASH']);

    //     Log::info('Verifying Paytm checksum', [
    //         'ORDERID' => $payload['ORDERID'] ?? 'N/A',
    //         'TXNID' => $payload['TXNID'] ?? 'N/A',
    //     ]);

    //     $isValid = PaytmChecksum::verifySignature($payload, $this->merchantKey, $checksum);

    //     if (!$isValid) {
    //         Log::warning('Checksum verification failed', ['payload' => $payload]);
    //     } else {
    //         Log::info('Checksum verification passed');
    //     }

    //     return [
    //         'valid' => $isValid,
    //         'data' => $payload,
    //     ];
    // }

    public function verifyCallback($payload): array
    {
        try {
            // Normalize input
            if ($payload instanceof \Illuminate\Http\Request) {
                Log::info('Received Paytm callback as Request object');
                $payload = $payload->all();
            } elseif (!is_array($payload)) {
                Log::error('Invalid payload type received for Paytm callback', [
                    'type' => gettype($payload),
                ]);
                throw new \InvalidArgumentException('Payload must be an array or Request');
            } else {
                //Log::info('Received Paytm callback as array');
            }

            // Extract and remove checksum
            $checksum = $payload['CHECKSUMHASH'] ?? '';
            unset($payload['CHECKSUMHASH']);

            // Log key identifiers
            // //Log::info('Attempting checksum verification', [
            //     'ORDERID' => $payload['ORDERID'] ?? 'N/A',
            //     'TXNID' => $payload['TXNID'] ?? 'N/A',
            // ]);

            // Verify signature
            $isValid = PaytmChecksum::verifySignature($payload, $this->merchantKey, $checksum);

            if (!$isValid) {
                Log::warning('Checksum verification failed', [
                    'payload' => $payload,
                ]);
            } else {
                // Log::info('Checksum verification successful');
            }

            return [
                'valid' => $isValid,
                'data' => $payload,
            ];

            // Log::info('Mocking Paytm callback response for testing purposes');
            // For testing Only
            // return [
            //     'valid' => true,
            //     'data' => array_merge($payload, [
            //         'ORDERID' => $payload['ORDERID'] ?? Null,
            //         'STATUS' => 'TXN_SUCCESS',
            //         'RESPMSG' => 'Txn Success',
            //         'RESPCODE' => '01',
            //         'TXNID' => $payload['TXNID'] ?? 'MOCKTXN123456789',
            //         'PAYMENTMODE' => 'UPI',
            //         'BANKNAME' => 'Mock Bank',
            //         'CURRENCY' => 'INR'
            //     ])
            // ];
            
        } catch (\InvalidArgumentException $e) {
            Log::error('Invalid argument in Paytm callback verification', [
                'message' => $e->getMessage(),
            ]);
            return [
                'valid' => false,
                'data' => [],
                'error' => 'Invalid input format',
            ];
        } catch (\Throwable $e) {
            Log::critical('Unexpected error during Paytm callback verification', [
                'exception' => $e,
            ]);
            return [
                'valid' => false,
                'data' => [],
                'error' => 'Internal server error',
            ];
        }
    }
}
