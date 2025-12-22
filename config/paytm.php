<?php

return [

    'ENV' => env('PAYTM_ENVIRONMENT', 'PROD'), // PROD or TEST

    'MID' => env('PAYTM_MERCHANT_ID', ''),
    'MERCHANT_KEY' => env('PAYTM_MERCHANT_KEY', ''),
    'WEBSITE' => env('PAYTM_MERCHANT_WEBSITE', 'WEBSTAGING'),

    'CHANNEL' => env('PAYTM_CHANNEL', 'WEB'),
    'INDUSTRY_TYPE' => env('PAYTM_INDUSTRY_TYPE', 'Retail'),

    'CALLBACK_URL' => env('PAYTM_CALLBACK_URL', 'paytm/callback'),

];
