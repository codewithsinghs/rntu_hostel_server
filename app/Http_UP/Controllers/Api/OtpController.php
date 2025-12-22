<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use Throwable; // Ensure this is imported for the try-catch block

class OtpController extends Controller
{
    //

    /**
     * Send an OTP to either the user's mobile or email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    // public function send(Request $request)
    // {
    //     // 1. Validate the input (either phone or email must be present)
    //     $validator = Validator::make($request->all(), [
    //         'phone' => 'required_without:email|numeric|digits_between:10,12|exists:users,mobile',
    //         'email' => 'required_without:phone|email|exists:users,email',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     // 2. Find the user based on the provided input
    //     $user = User::where('mobile', $request->phone)
    //                   ->orWhere('email', $request->email)
    //                   ->first();

    //     // Safety check, although 'exists' rule should handle this
    //     if (!$user) {
    //         return response()->json(['message' => 'User not found.'], 404);
    //     }

    //     // // 3. Generate a 6-digit OTP
    //     // $otp = Str::random(6, '0123456789');
    //      // 3. Generate a 6-digit NUMERIC OTP using a secure method
    //         $otp = (string) random_int(100000, 999999);

    //     Log::info('otp'. $otp);
    //     // 4. Store the OTP in the cache with a 5-minute expiration
    //     $cacheKey = 'otp_' . ($user->mobile ?? $user->email);
    //     Cache::put($cacheKey, $otp, now()->addMinutes(5));


    //     // 5. Send the OTP (choose method smartly)
    //     if ($user->mobile) {
    //         // TODO: Implement your SMS sending logic here
    //         // Example:
    //         // (new SmsService())->send($user->mobile, "Your OTP is: " . $otp);
    //         // Log::info("OTP for {$user->mobile} is {$otp}");
    //     } elseif ($user->email) {
    //         // TODO: Implement your email sending logic here
    //         // Example:
    //         // Mail::to($user->email)->send(new OtpMail($otp));
    //     }

    //     // 6. Return a success response
    //     return response()->json(['message' => 'OTP sent successfully.', 'success' => true], 200);
    // }

    public function send(Request $request)
    {
        try {
            // 1. Validate the input (either phone or email must be present)
            $validator = Validator::make($request->all(), [
                'phone' => 'required_without:email|numeric|digits_between:10,12|exists:users,mobile',
                'email' => 'required_without:phone|email|exists:users,email',
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }
                return back()->withErrors($validator)->withInput()->with('active_form', 'otp-based');
            }

            // 2. Find the user based on the provided input
            $user = User::where('mobile', $request->phone)
                ->orWhere('email', $request->email)
                ->first();

            if (!$user) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'User not found.'], 404);
                }
                return back()->with('error', 'User not found.')->withInput()->with('active_form', 'otp-based');
            }

            // 3. Generate a 6-digit NUMERIC OTP using a secure method
            $otp = (string) random_int(100000, 999999);

            // 4. Store the OTP in the cache with a 5-minute expiration
            $cacheKey = 'otp_' . ($user->mobile ?? $user->email);
            Cache::put($cacheKey, $otp, now()->addMinutes(5));

            // 5. Send the OTP (choose method smartly)
            if ($user->mobile) {
                // TODO: Implement SMS sending via a service like Twilio or Vonage
                // (new SmsService())->send($user->mobile, "Your OTP is: " . $otp);
                Log::info("OTP for {$user->mobile} is {$otp}"); // For debugging
            } elseif ($user->email) {
                // TODO: Implement email sending via a mailable
                // Mail::to($user->email)->send(new OtpMail($otp));
                Log::info("OTP for {$user->email} is {$otp}"); // For debugging
            }

            // 6. Return a unified success response
            if ($request->expectsJson()) {
                return response()->json(['message' => 'OTP sent successfully.', 'success' => true], 200);
            }
            // return back()->with('success', 'OTP sent successfully!')->withInput()->with('active_form', 'otp-based');

            // *** ADD THIS LINE ***
            return back()->with('success', 'OTP sent successfully!')->with('otp_sent', true)->with('active_form', 'otp-based')->withInput();
        } catch (Throwable $e) {
            Log::error('OTP send failed: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['message' => 'An internal server error occurred.'], 500);
            }
            return back()->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

    /**
     * Verify the received OTP and log in the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    // public function verify(Request $request)
    // {
    //     Log::info($request->all());

    //      // 1. Get the 'otp' value from the request.
    //     // It could be a string or an array depending on the front-end.
    //     $otp = $request->input('otp');

    //     // 2. If it's an array, implode it into a single string.
    //     // This makes the logic robust to front-end variations.
    //     if (is_array($otp)) {
    //         $otp = implode('', $otp);
    //     }

    //     // 3. Overwrite the request's 'otp' value with the combined string.
    //     // This ensures the validator below gets the correct format.
    //     $request->merge(['otp' => $otp]);
    //     // --- END OF ENHANCEMENT ---
    //     // 1. Validate the incoming request
    //     $validator = Validator::make($request->all(), [
    //         'phone' => 'required_without:email|numeric|digits_between:10,12|exists:users,mobile',
    //         'email' => 'required_without:phone|email|exists:users,email',
    //         'otp'   => 'required|numeric|digits:6',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     // 2. Find the user and retrieve the stored OTP from the cache
    //     $user = User::where('mobile', $request->phone)
    //                   ->orWhere('email', $request->email)
    //                   ->first();

    //     $cacheKey = 'otp_' . ($user->mobile ?? $user->email);
    //     $storedOtp = Cache::get($cacheKey);

    //     // 3. Verify the OTP
    //     if (!$storedOtp || $storedOtp !== $request->otp) {
    //         // If OTP is invalid or expired
    //         return response()->json([
    //             'errors' => ['otp' => ['The OTP is invalid or has expired.']]
    //         ], 422);
    //     }

    //     // 4. Log in the user
    //     Auth::login($user);

    //     // 5. Clear the OTP from the cache for security
    //     Cache::forget($cacheKey);

    //     // 6. Return a success response with redirect URL
    //     return response()->json([
    //         'message' => 'Login successful!',
    //         'redirect' => route('dashboard') // Redirect to the dashboard
    //     ], 200);
    // }

    // public function verify(Request $request)
    // {
    //     try {
    //         $otp = $request->input('otp');

    //         // 2. If it's an array, implode it into a single string.
    //         // This makes the logic robust to front-end variations.
    //         if (is_array($otp)) {
    //             $otp = implode('', $otp);
    //         }

    //         // 3. Overwrite the request's 'otp' value with the combined string.
    //         // This ensures the validator below gets the correct format.
    //         $request->merge(['otp' => $otp]);

    //         // 1. Prepare OTP value for validation
    //         $otp = $request->input('otp');
    //         if (is_array($otp)) {
    //             $otp = implode('', $otp);
    //         }
    //         $request->merge(['otp' => $otp]);

    //         // 2. Validate the incoming request
    //         $validator = Validator::make($request->all(), [
    //             'phone' => 'required_without:email|numeric|digits_between:10,12|exists:users,mobile',
    //             'email' => 'required_without:phone|email|exists:users,email',
    //             'otp'   => 'required|numeric|digits:6',
    //         ]);

    //         if ($validator->fails()) {
    //             if ($request->expectsJson()) {
    //                 return response()->json(['errors' => $validator->errors()], 422);
    //             }
    //             // *** Handle traditional validation failure with redirect ***
    //             return back()->withErrors($validator)->withInput()->with('active_form', 'otp-based');
    //         }

    //         // 3. Find the user and retrieve the stored OTP from the cache
    //         $user = User::where('mobile', $request->phone)
    //             ->orWhere('email', $request->email)
    //             ->first();

    //         $cacheKey = 'otp_' . ($user->mobile ?? $user->email);
    //         $storedOtp = Cache::get($cacheKey);

    //         // 4. Verify the OTP
    //         if (!$storedOtp || $storedOtp !== $request->otp) {
    //             $errorMessage = 'The OTP is invalid or has expired.';
    //             if ($request->expectsJson()) {
    //                 return response()->json(['errors' => ['otp' => [$errorMessage]]], 422);
    //             }
    //             return back()->with('error', $errorMessage)->withInput()->with('active_form', 'otp-based');
    //         }

    //         // 5. Log in the user
    //         Auth::login($user);

    //         // 6. Clear the OTP from the cache for security
    //         Cache::forget($cacheKey);

    //         // 7. Return a unified success response
    //         if ($request->expectsJson()) {
    //             return response()->json([
    //                 'message' => 'Login successful!',
    //                 'redirect' => route('dashboard')
    //             ], 200);
    //         }
    //         return redirect()->route('dashboard')->with('success', 'You have been logged in!');
    //     } catch (Throwable $e) {
    //         Log::error('OTP verification failed: ' . $e->getMessage());
    //         if ($request->expectsJson()) {
    //             return response()->json(['message' => 'An internal server error occurred.'], 500);
    //         }
    //         return back()->with('error', 'An unexpected error occurred. Please try again.');
    //     }
    // }
    // app/Http/Controllers/OtpController.php

// app/Http/Controllers/OtpController.php

public function verify(Request $request)
{
    try {
        $otp = $request->input('otp');
        if (is_array($otp)) {
            $otp = implode('', $otp);
        }
        $request->merge(['otp' => $otp]);

        $validator = Validator::make($request->all(), [
            'phone' => 'required_without:email|numeric|digits_between:10,12|exists:users,mobile',
            'email' => 'required_without:phone|email|exists:users,email',
            'otp'   => 'required|numeric|digits:6',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput()->with('active_form', 'otp-based')->with('otp_sent', true);
        }

        $user = User::where('mobile', $request->phone)
                      ->orWhere('email', $request->email)
                      ->first();
                          
        $cacheKey = 'otp_' . ($user->mobile ?? $user->email);
        $storedOtp = Cache::get($cacheKey);

        if (!$storedOtp || $storedOtp !== $request->otp) {
            $errorMessage = 'The OTP is invalid or has expired.';
            if ($request->expectsJson()) {
                // Return a specific OTP error for AJAX
                return response()->json(['errors' => ['otp' => [$errorMessage]]], 422);
            }
            // *** FINAL FIX: Return a specific error for the 'otp' field for traditional form submission ***
            return back()->withErrors(['otp' => $errorMessage])->withInput()->with('active_form', 'otp-based')->with('otp_sent', true);
        }

        Auth::login($user);
        Cache::forget($cacheKey);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Login successful!',
                'redirect' => route('dashboard')
            ], 200);
        }
        return redirect()->route('dashboard')->with('success', 'You have been logged in!');

    } catch (Throwable $e) {
        Log::error('OTP verification failed: ' . $e->getMessage());
        if ($request->expectsJson()) {
            return response()->json(['message' => 'An internal server error occurred.'], 500);
        }
        return back()->with('error', 'An unexpected error occurred. Please try again.')->withInput();
    }
}
}
