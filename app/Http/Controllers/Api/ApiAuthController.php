<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Models\Guest;
use App\Helpers\ApiResponse;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Register new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users,email',
            'phone'                 => 'required|string|max:15|unique:users,phone',
            'password'              => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', 422, $validator->errors()->toArray());
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return ApiResponse::success('Registration successful', ['user' => $user], 201);
    }

    /**
     * Login with password
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string', // email or phone
            'password'   => 'required|string'
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', 422, $validator->errors()->toArray());
        }

        $user = User::where('email', $request->identifier)
            ->orWhere('phone', $request->identifier)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ApiResponse::error('Invalid credentials', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success('Login successful', [
            'token' => $token,
            'user'  => $user,
        ]);
    }

    /**
     * Send OTP for login
     */
    public function sendLoginOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', 422, $validator->errors()->toArray());
        }

        $user = User::where('email', $request->identifier)
            ->orWhere('phone', $request->identifier)
            ->first();

        if (!$user) {
            return ApiResponse::error('User not found', 404);
        }

        $this->otpService->generate($request->identifier, 'login');

        return ApiResponse::success('OTP sent successfully');
    }

    /**
     * Verify OTP for login
     */
    public function verifyLoginOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string',
            'otp'        => 'required|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', 422, $validator->errors()->toArray());
        }

        $user = User::where('email', $request->identifier)
            ->orWhere('phone', $request->identifier)
            ->first();

        if (!$user) {
            return ApiResponse::error('User not found', 404);
        }

        if (!$this->otpService->validate($request->identifier, $request->otp, 'login')) {
            return ApiResponse::error('Invalid or expired OTP', 400);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success('Login successful', [
            'token' => $token,
            'user'  => $user,
        ]);
    }

    /**
     * Send OTP for password reset
     */
    public function sendPasswordOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', 422, $validator->errors()->toArray());
        }

        $user = User::where('email', $request->identifier)
            ->orWhere('phone', $request->identifier)
            ->first();

        if (!$user) {
            return ApiResponse::error('User not found', 404);
        }

        $this->otpService->generate($request->identifier, 'reset');

        return ApiResponse::success('OTP sent for password reset');
    }

    /**
     * Reset password using OTP
     */
    public function resetPasswordWithOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier'           => 'required|string',
            'otp'                  => 'required|string',
            'password'             => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', 422, $validator->errors()->toArray());
        }

        $user = User::where('email', $request->identifier)
            ->orWhere('phone', $request->identifier)
            ->first();

        if (!$user) {
            return ApiResponse::error('User not found', 404);
        }

        if (!$this->otpService->validate($request->identifier, $request->otp, 'reset')) {
            return ApiResponse::error('Invalid or expired OTP', 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return ApiResponse::success('Password reset successful');
    }

    /**
     * Profile
     */
    // public function profile(Request $request)
    // {
    //     return ApiResponse::success('Profile fetched', ['user' => $request->user()]);
    // }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return ApiResponse::success('Logged out successfully');
    }

    protected function resolveRedirect(string $role): string
    {
        $map = [
            'super_admin'         => '/super-admin/dashboard',
            'admin'               => '/admin/dashboard',
            'resident'            => '/resident/dashboard',
            'guest_professional'  => '/professional/guest',
            'guest'               => '/guest/dashboard',
        ];

        return $map[$role] ?? '/dashboard';
    }


    // public function apiLogin(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid credentials'
    //         ], 401);
    //     }




    //     // Create token using Sanctum
    //     $token = $user->createToken('api-token')->plainTextToken;

    //     // Get role (assuming single role per user)
    //     $role = $user->getRoleNames()->first();

    //     // Determine redirect URL based on role
    //     $redirectUrl = match ($role) {
    //         'admin' => '/admin/dashboard',
    //         'super_admin' => '/super-admin/dashboard',
    //         'accountant' => '/accountant/dashboard',
    //         'warden' => '/warden/dashboard',
    //         'security' => '/security/dashboard',
    //         'mess_manager' => '/mess-manager/dashboard',
    //         'gym_manager' => '/gym-manager/dashboard',
    //         'hod' => '/hod/dashboard',
    //         'resident' => '/resident/dashboard',
    //         'admission' => '/admission/dashboard',
    //         default => '/login',
    //     };

    //     return response()->json([
    //         'success' => true,
    //         'data' => [
    //             'user' => $user,
    //             'role' => $role,
    //             'token' => $token,
    //             'redirect_url' => $redirectUrl
    //         ]
    //     ]);
    // }
    // public function apiLogin(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid credentials'
    //         ], 401);
    //     }

    //     // Create Sanctum token
    //     $token = $user->createToken('api-token')->plainTextToken;

    //     // Get role
    //     $role = $user->getRoleNames()->first();

    //     // Redirect URL based on role
    //     $redirectUrl = match ($role) {
    //         'admin' => '/admin/dashboard',
    //         'super_admin' => '/super-admin/dashboard',
    //         'accountant' => '/accountant/dashboard',
    //         'warden' => '/warden/dashboard',
    //         'security' => '/security/dashboard',
    //         'mess_manager' => '/mess-manager/dashboard',
    //         'gym_manager' => '/gym-manager/dashboard',
    //         'hod' => '/hod/dashboard',
    //         'resident' => '/resident/dashboard',
    //         'admission' => '/admission/dashboard',
    //         default => '/login',
    //     };

    //     return response()->json([
    //         'success' => true,
    //         'data' => [
    //             'user' => $user,
    //             'role' => $role,
    //             'token' => $token,
    //             'redirect_url' => $redirectUrl
    //         ]
    //     ]);
    // }

    public function apiRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->assignRole('resident'); // default role

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => $user
        ]);
    }

    // public function apiLogin(Request $request)
    // {
    //     Log::info('user');
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid credentials'
    //         ], 401);
    //     }

    //     // Create token using Sanctum
    //     $token = $user->createToken('api-token')->plainTextToken;

    //     // Get role (assuming single role)
    //     $role = $user->getRoleNames()->first();

    //     // Determine redirect URL based on role
    //     $redirectUrl = match ($role) {
    //         'resident' => '/resident/dashboard',
    //         'admin' => '/admin/dashboard',
    //         default => '/login',
    //     };

    //     return response()->json([
    //         'success' => true,
    //         'data' => [
    //             'user' => $user,
    //             'role' => $role,
    //             'token' => $token,
    //             'redirect_url' => $redirectUrl
    //         ]
    //     ]);
    // }

    // Final Working Code Beloww
    // public function apiLogin(Request $request)
    // {
    //     // Log::info('Login attempt', $request->all());
    //     Log::info('Login attempt at apiauthcontroller');

    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required_without:mobile',
    //         'mobile' => 'required_without:password',
    //     ]);

    //     // Try authenticating as a User
    //     $user = User::where('email', $request->email)->first();

    //     if ($user && Hash::check($request->password, $user->password)) {

    //         // Log::info('its user');
    //         $token = $user->createToken('api-token')->plainTextToken;

    //         $role = $user->getRoleNames()->first();
    //         // $redirectUrl = match ($role) {
    //         //     'resident' => '/resident/dashboard',
    //         //     'admin' => '/admin/dashboard',
    //         //     'professional' => '/professional/home',
    //         //     default => '/login',
    //         // };

    //         // Dynamically build redirect path
    //         // $dynamicRedirect = "/{$role}/dashboard";
    //         // $redirectUrl = Route::has($role . '.dashboard') ? $dynamicRedirect : '/dashboard';

    //         // Try named route first
    //         if (Route::has($role . '.dashboard')) {
    //             $redirectUrl = route($role . '.dashboard');
    //         } elseif (Route::has($role . '/dashboard')) {
    //             $redirectUrl = url($role . '/dashboard');
    //         } else {
    //             $redirectUrl = url('/dashboard');
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'data' => [
    //                 'user' => $user,
    //                 'role' => $role,
    //                 'token' => $token,
    //                 'redirect_url' => $redirectUrl
    //             ]
    //         ]);
    //     }


    //     // Log::info('trying guest');
    //     // Try authenticating as a Guest (must match both email and mobile)
    //     $guest = Guest::where('email', $request->email)
    //         ->where('number', $request->mobile)
    //         ->first();

    //     // Log::info('trying guest' . json_encode($guest));

    //     if ($guest) {
    //         // Optional: check if guest is a professional
    //         $isProfessional = $guest->type === 'professional'; // adjust field name as needed
    //         $token = $guest->createToken('guest-token')->plainTextToken;
    //         $redirectUrl = $isProfessional ? '/professional/guest' : '/guest/welcome';

    //         return response()->json([
    //             'success' => true,
    //             'data' => [
    //                 'user' => $guest,
    //                 'role' => $isProfessional ? 'guest-professional' : 'guest',
    //                 'token' => $token,
    //                 'redirect_url' => $redirectUrl
    //             ]
    //         ]);
    //     }

    //     // No account found
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'No account found with provided credentials'
    //     ], 404);
    // }


    public function apiLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'nullable|required_without:mobile',
            'mobile'   => 'nullable|required_without:password',
        ]);

        //  Try USER login first

        $user = User::where('email', $request->email)->first();

        if ($user && $request->filled('password') && Hash::check($request->password, $user->password)) {

            $token = $user->createToken('auth_token')->plainTextToken;
            $role  = $user->getRoleNames()->first() ?? 'user';

            // Try named route first
            if (Route::has($role . '.dashboard')) {
                $redirectUrl = route($role . '.dashboard');
            } elseif (Route::has($role . '/dashboard')) {
                $redirectUrl = url($role . '/dashboard');
            } else {
                $redirectUrl = url('/dashboard');
            }

            $response = [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $role,
            ];

            if ($role === 'resident') {
                $response['resident'] = [
                    'scholar_no' => $actor->resident->scholar_no ?? null,
                    'number'     => $actor->resident->number ?? null,
                ];
            }

            return response()->json([
                'success'       => true,
                // 'token_type'    => 'Bearer',
                // 'access_token'  => $token,
                // 'auth_type'     => 'user',
                // 'role'          => $role,
                // 'redirect'      => $this->resolveRedirect($role),
                // 'profile'       => $user,
                'data' => [
                    'user' => $response,
                    'role' => $role,
                    'token' => $token,
                    'redirect_url' => $redirectUrl,
                    // 'redirect_url'      => $this->resolveRedirect($role),
                ]
            ]);
        }

        // Try GUEST login

        $guest = Guest::where('email', $request->email)
            ->where('number', $request->mobile)
            ->first();

        if ($guest) {

            $token = $guest->createToken('auth_token')->plainTextToken;

            $role = $guest->type === 'professional'
                ? 'guest_professional'
                : 'guest';

            $isProfessional = $guest->type === 'professional'; // adjust field name as needed

            $response = [
                'id'    => $guest->id,
                'name'  => $guest->name,
                'email' => $guest->email,
                'role'  => $role,
            ];

            if ($role === 'resident') {
                $response['resident'] = [
                    'scholar_no' => $actor->resident->scholar_no ?? null,
                    'number'     => $actor->resident->number ?? null,
                ];
            }
            
            return response()->json([
                'success'       => true,
                // 'token_type'    => 'Bearer',
                // 'access_token'  => $token,
                // 'auth_type'     => 'guest',
                // 'role'          => $role,
                // 'redirect'      => $this->resolveRedirect($role),
                // 'profile'       => $guest,
                'data' => [
                    'user' => $response,
                    'role' => $isProfessional ? 'guest-professional' : 'guest',
                    'token' => $token,
                    // 'redirect_url' => $redirectUrl
                    'redirect'      => $this->resolveRedirect($role),
                ]
            ]);
        }
        // Fail

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    //     protected function resolveUserRedirect(?string $role): string
    //     {
    //         if ($role && Route::has($role . '.dashboard')) {
    //             return route($role . '.dashboard');
    //         }

    //         if ($role && Route::has($role . '/dashboard')) {
    //             return url($role . '/dashboard');
    //         }

    //         return url('/dashboard');
    //     }

    //     Route::post('/api/login', [ApiAuthController::class, 'apiLogin'])
    //     ->middleware('throttle:5,1');
    //     Token Scope Awareness (Optional but Pro)
    // $guest->createToken('guest-token', ['guest'])->plainTextToken;
    // $user->createToken('api-token', ['user'])->plainTextToken;


    // Then protect routes:

    // Route::middleware(['auth:sanctum', 'ability:guest'])->group(function () {
    //     // guest routes
    // });

    protected function resolveGuestRedirect(string $role): string
    {
        return match ($role) {
            'guest_professional' => url('/professional/guest'),
            'guest'              => url('/guest/welcome'),
            default              => url('/guest'),
        };
    }


    // public function profile(Request $request)
    // {
    //     Log::info('finding user', $request->all());
    //     $user = $request->user();
    //     return response()->json([
    //         'success' => true,
    //         'data' => [
    //             'id' => $user->id,
    //             'name' => $user->name,
    //             'email' => $user->email,
    //             'role' => $user->getRoleNames()->first()
    //         ]
    //     ]);
    // }

    public function profile(Request $request)
    {
        Log::info('finding userpf', $request->all());

        $auth = $request->user(); // could be User or Guest

        // Determine model type
        if ($auth instanceof \App\Models\User) {
            $role = $auth->getRoleNames()->first(); // only for User
        } elseif ($auth instanceof \App\Models\Guest) {
            // Use a field or default role for Guest
            $role = $auth->type === 'professional' ? 'guest-professional' : 'guest';
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unknown user type'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $auth->id,
                'name' => $auth->name,
                'email' => $auth->email,
                'role' => $role
            ]
        ]);
    }

    /**
     * Show profile data
     */
    // public function show(Request $request)
    // {
    //     Log::info('here');
    //     try {
    //         // Get authenticated user or fallback to auth-id header
    //         $user = $request->user() ?? User::findOrFail($request->header('auth-id'));

    //         // Eager load resident relation
    //         $user->load('resident', 'roles');

    //         $role = $user->getRoleNames()->first(); // only for User
    //         // Log::info('role' . json_encode($role));

    //         // // Build limited response (same structure, trimmed data) 
    //         // $limited = [ 
    //         //     'id' => $user->id, 
    //         //     'name' => $user->name, 
    //         //     'email' => $user->email, 

    //         //     'role' => $role, 

    //         //     'resident' => [ 
    //         //         'address' => $user->resident->address ?? null, 
    //         //         'phone' => $user->resident->phone ?? null, 
    //         //     ], 
    //         // ];

    //         // Handle AJAX request
    //         if ($request->ajax()) {

    //             $role = $user->getRoleNames()->first();
    //             $user->role = $role; // dynamically append property  

    //             // Base response 
    //             $response = [
    //                 'id' => $user->id ?? null,
    //                 'name' => $user->name ?? null,
    //                 'email' => $user->email ?? null,
    //                 'gender' => $user->gender ?? null,
    //                 'mobile' => $user->mobile ?? null,
    //                 'role' => $role ?? null,
    //                 'profile_image' => $user->profile->image ?? null,
    //             ];

    //             // Include resident data ONLY if role is resident 
    //             if ($role === 'resident') {
    //                 $response['resident'] = [
    //                     'name' => $user->name ?? null,
    //                     'email' => $user->email ?? null,
    //                     'number' => $user->resident->number ?? null,
    //                     'scholar_no' => $user->resident->scholar_no ?? null,
    //                     'parent_no' => $user->resident->parent_no ?? null,
    //                     'guardian_no' => $user->resident->guardian_no ?? null,
    //                     'fathers_name' => $user->fathers_name ?? null,
    //                     'mothers_name' => $user->mothers_name ?? null,
    //                     'gender' => $user->gender ?? null,
    //                     'address' => $user->resident->address ?? null,
    //                 ];
    //             }

    //             // Build a limited response payload 
    //             // $user = [
    //             //     'id' => $user->id,
    //             //     'name' => $user->name,
    //             //     'email' => $user->email,
    //             //     'gender' =>    $user->gender,
    //             //     'mobile' =>    $user->mobile,
    //             //     'role' => $role,
    //             //     'resident' => [
    //             //         'name' => $user->name,
    //             //         'email' => $user->email,
    //             //         'number' =>    $user->resident->number,
    //             //         'scholar_no' =>    $user->resident->scholar_no,
    //             //         'parent_no' =>    $user->resident->parent_no,
    //             //         'guardian_no' =>    $user->resident->guardian_no,
    //             //         'fathers_name' =>    $user->fathers_name,
    //             //         'mothers_name' =>    $user->mothers_name,
    //             //         'gender' =>    $user->gender,
    //             //         'address' => $user->resident->address ?? null,

    //             //     ],
    //             //     'profile_image' => $user->profile->image,
    //             // ];

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Profile loaded successfully',
    //                 'data' => $response,
    //                 'errors' => null,
    //             ], 200);
    //         }

    //         // Handle non-AJAX (web form)
    //         return view('profile.show', compact('user'));
    //     } catch (Exception $e) {
    //         Log::error('Profile load failed: ' . $e->getMessage());

    //         if ($request->ajax()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Failed to load profile',
    //                 'data' => null,
    //                 'errors' => $e->getMessage(),
    //             ], 500);
    //         }

    //         return redirect()->back()->withErrors('Failed to load profile: ' . $e->getMessage());
    //     }
    // }

    // public function show(Request $request)
    // {
    //     Log::info('Profile fetch initiated');

    //     try {

    //         $authType = $request->header('auth-type') ?? 'user';

    //         // USER / RESIDENT PROFILE (Sanctum)

    //         if ($authType === 'user') {
    //             // Get authenticated user or fallback to auth-id header
    //             $user = $request->user() ?? User::findOrFail($request->header('auth-id'));

    //             // Eager load resident relation
    //             $user->load('resident', 'roles');

    //             $role = $user->getRoleNames()->first(); // only for User
    //             // Log::info('role' . json_encode($role));

    //             // Handle AJAX request
    //             // if ($request->ajax()) {

    //             $role = $user->getRoleNames()->first();
    //             $user->role = $role; // dynamically append property  

    //             // Base response 
    //             $response = [
    //                 'id' => $user->id ?? null,
    //                 'name' => $user->name ?? null,
    //                 'email' => $user->email ?? null,
    //                 'gender' => $user->gender ?? null,
    //                 'mobile' => $user->mobile ?? null,
    //                 'role' => $role ?? null,
    //                 'profile_image' => $user->profile->image ?? null,
    //             ];

    //             // Include resident data ONLY if role is resident 
    //             if ($role === 'resident') {
    //                 $response['resident'] = [
    //                     'name' => $user->name ?? null,
    //                     'email' => $user->email ?? null,
    //                     'number' => $user->resident->number ?? null,
    //                     'scholar_no' => $user->resident->scholar_no ?? null,
    //                     'parent_no' => $user->resident->parent_no ?? null,
    //                     'guardian_no' => $user->resident->guardian_no ?? null,
    //                     'fathers_name' => $user->fathers_name ?? null,
    //                     'mothers_name' => $user->mothers_name ?? null,
    //                     'gender' => $user->gender ?? null,
    //                     'address' => $user->resident->address ?? null,
    //                 ];
    //             }

    //         }

    //         // GUEST PROFILE (Token based, no user table)
    //         elseif ($authType === 'guest') {

    //         Log::info('Guest profile fetch initiated');
    //             $guestId = $request->header('auth-id');

    //             if (!$guestId) {
    //                 abort(401, 'Guest authentication failed');
    //             }

    //             $guest = Guest::findOrFail($guestId);

    //             $response = [
    //                 'id'     => $guest->id,
    //                 'name'   => $guest->name,
    //                 'email'  => $guest->email,
    //                 'gender' => $guest->gender,
    //                 'mobile' => $guest->number,
    //                 'role'   => 'guest',

    //                 'guest' => [
    //                     'scholar_no'        => $guest->scholar_no,
    //                     'faculty_id'        => $guest->faculty_id,
    //                     'department_id'     => $guest->department_id,
    //                     'course_id'         => $guest->course_id,
    //                     'room_preference'   => $guest->room_preference,
    //                     'food_preference'   => $guest->food_preference,
    //                     'status'            => $guest->status,
    //                     'is_verified'       => $guest->is_verified,
    //                 ]
    //             ];
    //         } else {
    //             abort(403, 'Invalid authentication type');
    //         }
    //         // AJAX JSON RESPONSE

    //         if ($request->ajax()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Profile loaded successfully',
    //                 'data' => $response,
    //                 'errors' => null,
    //             ], 200);
    //         }

    //         // Handle non-AJAX (web form)
    //         return view('profile.show', compact('user'));
    //     } catch (Exception $e) {
    //         Log::error('Profile load failed: ' . $e->getMessage());

    //         if ($request->ajax()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Failed to load profile',
    //                 'data' => null,
    //                 'errors' => $e->getMessage(),
    //             ], 500);
    //         }

    //         return redirect()->back()->withErrors('Failed to load profile: ' . $e->getMessage());
    //     }
    // }

    // public function show(Request $request)
    // {
    //     Log::info('Profile fetch initiated');

    //     try {

    //         // $authType = $request->header('auth-type') ?? 'user';
    //         $authType = $request->header('auth-type');

    //         if (!$authType) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Authentication type missing',
    //                 'data' => null,
    //                 'errors' => 'auth-type header is required (user | guest)'
    //             ], 400);
    //         }

    //         Log::info('Auth type: ' . $authType);
    //         $response = null;
    //         $user = null; // important to avoid undefined variable

    //         /* ==================================================
    //      | USER / RESIDENT PROFILE
    //      ==================================================*/
    //         if ($authType === 'user') {

    //             $user = $request->user()
    //                 ?? User::findOrFail($request->header('auth-id'));

    //             $user->load('resident', 'roles', 'profile');

    //             $role = $user->getRoleNames()->first();

    //             $response = [
    //                 'id'     => $user->id,
    //                 'name'   => $user->name,
    //                 'email'  => $user->email,
    //                 'gender' => $user->gender,
    //                 'mobile' => $user->mobile,
    //                 'role'   => $role,
    //                 'profile_image' => optional($user->profile)->image,
    //             ];

    //             if ($role === 'resident') {
    //                 $response['resident'] = [
    //                     'scholar_no'   => optional($user->resident)->scholar_no,
    //                     'number'       => optional($user->resident)->number,
    //                     'parent_no'    => optional($user->resident)->parent_no,
    //                     'guardian_no'  => optional($user->resident)->guardian_no,
    //                     'fathers_name' => $user->fathers_name,
    //                     'mothers_name' => $user->mothers_name,
    //                     'gender'       => $user->gender,
    //                     'address'      => optional($user->resident)->address,
    //                 ];
    //             }
    //         }

    //         /* ==================================================
    //      | GUEST PROFILE
    //      ==================================================*/ elseif ($authType === 'guest') {

    //             Log::info('Guest profile fetch initiated');

    //             $guestId = $request->header('auth-id');

    //             if (!$guestId) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Guest authentication failed',
    //                     'data' => null,
    //                     'errors' => 'Missing auth-id header'
    //                 ], 401);
    //             }

    //             $guest = Guest::findOrFail($guestId);

    //             $response = [
    //                 'id'     => $guest->id,
    //                 'name'   => $guest->name,
    //                 'email'  => $guest->email,
    //                 'gender' => $guest->gender,
    //                 'mobile' => $guest->number,
    //                 'role'   => 'guest',

    //                 'guest' => [
    //                     'scholar_no'      => $guest->scholar_no,
    //                     'faculty_id'      => $guest->faculty_id,
    //                     'department_id'   => $guest->department_id,
    //                     'course_id'       => $guest->course_id,
    //                     'room_preference' => $guest->room_preference,
    //                     'food_preference' => $guest->food_preference,
    //                     'status'          => $guest->status,
    //                     'is_verified'     => $guest->is_verified,
    //                     'is_postpaid'     => $guest->is_postpaid,
    //                 ],
    //             ];
    //         } else {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Invalid authentication type',
    //                 'data' => null,
    //                 'errors' => 'Unsupported auth-type'
    //             ], 403);
    //         }

    //         /* ==================================================
    //      | JSON RESPONSE (API-FIRST)
    //      ==================================================*/
    //         if ($request->expectsJson()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Profile loaded successfully',
    //                 'data'    => $response,
    //                 'errors'  => null,
    //             ], 200);
    //         }

    //         /* ==================================================
    //      | WEB VIEW (ONLY FOR USER)
    //      ==================================================*/
    //         if ($authType === 'user') {
    //             return view('profile.show', compact('user'));
    //         }

    //         abort(404);
    //     } catch (\Exception $e) {

    //         Log::error('Profile load failed: ' . $e->getMessage());

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to load profile',
    //             'data'    => null,
    //             'errors'  => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function show(Request $request)
    {
        // Log::info('Profile fetch initiated');

        try {

            $auth = $request->user(); // Sanctum resolved

            if (!$auth) {
                abort(401, 'Unauthenticated');
            }

            /* ==============================
         | USER PROFILE
         |==============================*/
            if ($auth instanceof \App\Models\User) {

                $auth->load(['resident', 'roles', 'profile']);

                $role = $auth->getRoleNames()->first();

                $response = [
                    'id'            => $auth->id,
                    'name'          => $auth->name,
                    'email'         => $auth->email,
                    'gender'        => $auth->gender,
                    'mobile'        => $auth->mobile,
                    'role'          => $role,
                    'auth_type'     => 'user',
                    'profile_image' => $auth->profile->image ?? null,
                ];

                if ($role === 'resident' && $auth->resident) {
                    $response['resident'] = [
                        'number'        => $auth->resident->number,
                        'scholar_no'    => $auth->resident->scholar_no,
                        'parent_no'     => $auth->resident->parent_no,
                        'guardian_no'   => $auth->resident->guardian_no,
                        'address'       => $auth->resident->address,
                    ];
                }
            }

            /* ==============================
         | GUEST PROFILE
         |==============================*/ elseif ($auth instanceof \App\Models\Guest) {

                $response = [
                    'id'        => $auth->id,
                    'name'      => $auth->name,
                    'email'     => $auth->email,
                    'gender'    => $auth->gender,
                    'mobile'    => $auth->number,
                    'role'      => 'guest',
                    'auth_type' => 'guest',

                    'guest' => [
                        'scholar_no'      => $auth->scholar_no,
                        'faculty_id'      => $auth->faculty_id,
                        'department_id'   => $auth->department_id,
                        'course_id'       => $auth->course_id,
                        'room_preference' => $auth->room_preference,
                        'food_preference' => $auth->food_preference,
                        'status'          => $auth->status,
                        'is_verified'     => $auth->is_verified,
                    ]
                ];
            } else {
                abort(403, 'Invalid authentication model');
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile loaded successfully',
                'data'    => $response,
                'errors'  => null,
            ]);
        } catch (\Throwable $e) {

            Log::error('Profile load failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load profile',
                'data'    => null,
                'errors'  => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }


    public function update(Request $request)
    {
        // Log::info('user update req'. json_encode($request->all()));
        try {

            /* ----------------------------------------------------------
         * IDENTIFY USER (API OR auth-id HEADER)
         * ---------------------------------------------------------- */
            $user = $request->user();
            // Log::info('user'. json_encode($user));

            if (!$user && $request->header('auth-id')) {
                $user = User::findOrFail((int) $request->header('auth-id'));
            }

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized request',
                    'data' => null,
                ], 401);
            }

            /* ----------------------------------------------------------
         * VALIDATION
         * ---------------------------------------------------------- */
            $validated = $request->validate([
                'name'            => 'required|string|max:255',
                'email'           => 'required|email|max:255|unique:users,email,' . $user->id,
                'gender'          => 'nullable|string|in:male,female,other,Male,Female,Other',
                'mobile'  => 'required|regex:/^[0-9+\-() ]{6,20}$/',

                // Optional password update
                'password'        => 'nullable|min:6|confirmed',

                // Optional image upload
                'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);


            /* ----------------------------------------------------------
         * SANITIZATION (TRIM STRING INPUTS)
         * ---------------------------------------------------------- */
            $clean = [];
            foreach ($validated as $key => $value) {
                $clean[$key] = is_string($value) ? trim($value) : $value;
            }

            /* ----------------------------------------------------------
         * UPDATE (EXPLICIT PER-FIELD UPDATE)
         * ---------------------------------------------------------- */
            DB::beginTransaction();

            try {

                $user->name    = $clean['name'];
                $user->email   = $clean['email'];
                $user->gender  = $clean['gender'] ?? $user->gender;
                $user->mobile  = $clean['mobile'] ?? $user->mobile;

                /* -------------------------------
         * IF PASSWORD PROVIDED → UPDATE
         * ------------------------------- */
                if (!empty($clean['password'])) {
                    $user->password = bcrypt($clean['password']);
                }

                /* -------------------------------
         * IF IMAGE PROVIDED → UPDATE
         * ------------------------------- */
                if ($request->hasFile('image')) {
                    // Delete old image safely
                    if ($user->image && Storage::exists('public/users/profile/' . $user->image)) {
                        Storage::delete('public/users/profile/' . $user->image);
                    }

                    $file     = $request->file('image');
                    $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

                    $file->storeAs('public/profile', $filename);
                    $user->image = $filename;
                }


                $user->save();

                // Log::info('user'. json_encode($user));
                // Fetch related resident
                $resident = $user->resident;

                if ($resident) {
                    // Log::info('Resident before update: ' . json_encode($resident));

                    $resident->name    = $clean['name'];
                    $resident->email   = $clean['email'];
                    $resident->gender  = $clean['gender'] ?? $user->gender;
                    $resident->number  = $clean['mobile'] ?? $user->mobile;

                    // Update resident fields
                    // $resident->address     = $request->address;
                    // $resident->city        = $request->city;
                    // $resident->state       = $request->state;
                    // $resident->pincode     = $request->pincode;
                    // $resident->updated_by  = auth()->id();
                    $resident->save();
                } else {
                    Log::warning("No resident found for user ID: {$user->id}");
                }

                DB::commit(); // 🎉 All good, commit!

            } catch (\Exception $e) {

                DB::rollBack(); // ❌ Error → rollback everything

                Log::error("Update failed: " . $e->getMessage());
                Log::error($e->getTraceAsString());

                // return response()->json([
                //     'status' => 'error',
                //     'message' => 'Something went wrong! Please try again.'
                // ], 500);
            }



            /* ----------------------------------------------------------
         * LOAD RELATIONS FOR RESPONSE
         * ---------------------------------------------------------- */
            $user->load(['resident', 'roles']);
            $user->role = $user->roles->pluck('name')->first() ?? null;

            // DB::commit();


            /* ----------------------------------------------------------
         * JSON RESPONSE FOR AJAX
         * ---------------------------------------------------------- */
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully',
                    'data' => $user
                ], 200);
            }

            /* ----------------------------------------------------------
         * WEB RESPONSE
         * ---------------------------------------------------------- */
            return redirect()
                ->route('profile.show')
                ->with('success', 'Profile updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {

            /* ------ VALIDATION ERRORS ------ */
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Profile update failed', [
                'error'   => $e->getMessage(),
                'user_id' => $user->id ?? null
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong',
                    'errors'  => $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->withErrors('Failed to update profile: ' . $e->getMessage());
        }
    }

    public function updateProfileImage(Request $request)
    {
        Log::info('request', $request->all());
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // $user = $request->user();
        // Log::info('user'. json_encode($user));

        $user = auth()->user();

        // Log::info('user'. json_encode($user));

        $resident = $user->resident;
        // $path = $request->file('photo')->store('profile_photos', 'public');

        // $user->photo = $path;
        // $user->save();

        // Store file in: storage/app/public/users/profile/ 
        $storedPath = $request->file('photo')->store('users/profile', 'public');
        // Extract only "profile/filename.ext" for DB 
        $filename = basename($storedPath); // e.g. abc123.jpg 
        $dbPath = "profile/" . $filename;
        // Save into related profile model 
        $resident->profile->image = $dbPath;
        $resident->profile->save();

        return response()->json([
            'status' => true,
            'message' => 'Photo updated successfully',
            'data' => $user
        ]);
    }
}
