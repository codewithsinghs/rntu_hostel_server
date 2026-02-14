<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * Generate OTP and store temporarily
     */
    public function generate(string $identifier, string $context = 'login'): string
    {
        $otp = rand(100000, 999999);
        $key = "otp:{$context}:{$identifier}";

        Cache::put($key, $otp, now()->addMinutes(5));

        // For demo - log it. In real case, send via SMS/Email service.
        Log::info("OTP for {$identifier} [{$context}]: {$otp}");

        return (string) $otp;
    }

    /**
     * Validate OTP
     */
    public function validate(string $identifier, string $otp, string $context = 'login'): bool
    {
        $key = "otp:{$context}:{$identifier}";
        $cachedOtp = Cache::get($key);

        if ($cachedOtp && $cachedOtp == $otp) {
            Cache::forget($key); // OTP one-time use
            return true;
        }

        return false;
    }
}
