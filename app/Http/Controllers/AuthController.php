<?php

namespace App\Http\Controllers;

use App\Models\User;
// use App\Services\OtpService;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        // Determine panel based on user role
        if ($user->hasRole('super_admin')) {
            $panel = 'super_admin_panel';
        } elseif ($user->hasRole('admin')) {
            $panel = 'admin_panel';
        } elseif ($user->hasRole('resident')) {
            $panel = 'resident_panel';
        } else {
            $panel = 'default';
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'redirect_panel' => $panel,
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole('super_admin')) {
            return redirect('/superadmin');
        } elseif ($user->hasRole('admin')) {
            return redirect('/admin/dashboard');
        } elseif ($user->hasRole('resident')) {
            return redirect('/resident/dashboard');
        } else {
            Auth::logout();
            return redirect('/login')->withErrors(['error' => 'Unauthorized role.']);
        }
    }



    public function showLoginForm()
    {
        return view('auth.login'); // this should match the blade filename
    }

    // public function login (Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if (Auth::attempt($credentials)) {
    //         $request->session()->regenerate();

    //         $user = Auth::user();
    //         if ($user->hasRole('super_admin')) {
    //             return redirect('/super-admin/dashboard');
    //         } elseif ($user->hasRole('admin')) {
    //             return redirect('/admin/dashboard');
    //         } elseif ($user->hasRole('resident')) {
    //             return redirect('/resident/dashboard');
    //         } else {
    //             Auth::logout();
    //             return redirect('/login')->withErrors(['Unauthorized role.']);
    //         }
    //     }

    //     return back()->withErrors(['email' => 'Invalid credentials.']);
    // }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // public function sendOtp(Request $request, OtpService $otpService)
    // {
    //     $request->validate(['email' => 'required|email']);
    //     $otpService->generate($request->email);
    //     return response()->json(['message' => 'OTP sent successfully.']);
    // }

    public function changePassword(Request $request, OtpService $otpService)
    {
        // Log::info('password change request', $request->all());
        $request->validate(['new_password' => 'required|min:6']);

        if ($request->filled('current_password')) {
            $authId = $request->header('auth-id'); // Get auth-id from headers
            $user = User::findOrFail($authId);

            // Log::info('password change request'. json_encode($user));

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['success' => false, 'message' => 'Incorrect current password.']);
            }
        } elseif ($request->filled('email') && $request->filled('otp')) {
            $user = User::where('email', $request->email)->first();
            if (!$user || !$otpService->validate($request->email, $request->otp)) {
                return response()->json(['success' => false, 'message' => 'Invalid OTP or email.']);
            }
            $otpService->clear($request->email);
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid request.']);
        }



        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => true, 'message' => 'Password changed successfully.']);
    }

    public function viewProfile(Request $request, $role)
    {
        $user = $request->user(); // Authenticated user

        // Optional: check if user has the role
        if (!$user->hasRole($role)) {
            abort(403, 'Unauthorized for this role');
        }

        // Only select fields needed for profile page
        $profile = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'gender' => $user->gender,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ];

        return view('profile.view', compact('profile', 'role'));
    }







    /**
     * Send OTP to user's email.
     */
    // public function sendOtp(Request $request, OtpService $otpService)
    // {
    //     $request->validate([
    //         'email' => 'required|email|exists:users,email',
    //     ]);

    //     try {
    //         $otpService->generate($request->email);
    //         return response()->json(['success' => true, 'message' => 'OTP sent successfully.']);
    //     } catch (\Exception $e) {
    //         Log::error('OTP generation failed: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Failed to send OTP. Please try again later.']);
    //     }
    // }

    /**
     * Change password using either current password or OTP.
     */
    // public function changePassword(Request $request, OtpService $otpService)
    // {
    //     Log::info('password change request', $request->all());
    //     $request->validate([
    //         'new_password' => 'required|string|min:6',
    //     ]);

    //     $user = null;

    //     // Method 1: Authenticated user with current password
    //     if ($request->filled('current_password')) {
    //         $user = auth()->user();

    //         if (!$user) {
    //             return response()->json(['success' => false, 'message' => 'User not authenticated.']);
    //         }

    //         if (!Hash::check($request->current_password, $user->password)) {
    //             return response()->json(['success' => false, 'message' => 'Incorrect current password.']);
    //         }
    //     }

    //     // Method 2: OTP-based password reset
    //     elseif ($request->filled('email') && $request->filled('otp')) {
    //         $user = User::where('email', $request->email)->first();

    //         if (!$user) {
    //             return response()->json(['success' => false, 'message' => 'User not found.']);
    //         }

    //         if (!$otpService->validate($request->email, $request->otp)) {
    //             return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.']);
    //         }

    //         $otpService->cle    ar($request->email);
    //     }

    //     // No valid method provided
    //     else {
    //         return response()->json(['success' => false, 'message' => 'Invalid request. Provide either current password or OTP.']);
    //     }

    //     try {
    //         $user->password = Hash::make($request->new_password);
    //         $user->save();

    //         return response()->json(['success' => true, 'message' => 'Password changed successfully.']);
    //     } catch (\Exception $e) {
    //         Log::error('Password change failed: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Failed to update password. Please try again.']);
    //     }
    // }

    //     public function changePassword(Request $request)
    // {
    //     Log::info('Entered changePassword', $request->all());

    //     // quick test: comment out OTP service calls
    //     // return response()->json(['success' => true, 'message' => 'Debugging...']);
    // }

}
