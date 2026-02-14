<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public function generate($email)
    {
        $otp = rand(100000, 999999);
        Cache::put("otp_{$email}", $otp, now()->addMinutes(10));

        // Send OTP via email (or SMS)
        Mail::raw("Your OTP is: $otp", function ($message) use ($email) {
            $message->to($email)->subject('Your OTP Code');
        });

        return $otp;
    }

    public function validate($email, $otp)
    {
        return Cache::get("otp_{$email}") == $otp;
    }

    public function clear($email)
    {
        Cache::forget("otp_{$email}");
    }
}
