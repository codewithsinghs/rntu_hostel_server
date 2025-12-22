<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DualAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     return $next($request);
    // }

    // public function handle(Request $request, Closure $next): Response
    // {
    //     // ✅ Normalize headers from query params if needed
    //     foreach (['token', 'auth-id', 'guest-id'] as $key) {
    //         if ($request->has($key)) {
    //             $request->headers->set($key, $request->input($key));
    //         }
    //     }

    //     // ✅ Check Sanctum (Bearer token) authentication
    //     if ($request->bearerToken()) {
    //         $user = Auth::guard('sanctum')->user();
    //         if ($user) {
    //             // Ensure auth-id is available for downstream logic
    //             $request->headers->set('auth-id', $user->id);
    //             return $next($request);
    //         }
    //     }

    //     // ✅ Fallback to admin token-based auth
    //     $token = $request->header('token');
    //     $adminId = $request->header('auth-id');
    //     if ($adminId && $token) {
    //         $error = null; // ✅ Declare before passing by reference

    //         if (Helper::is_token_valid_admin($adminId, $token, $error)) {
    //             return $next($request);
    //         } else {
    //             return response()->json($error, 401);
    //         }
    //     }

    //     // ✅ Fallback to guest token-based auth
    //     $guestId = $request->header('guest-id');
    //     if ($guestId && $token) {
    //         $error = null; // ✅ Declare before passing by reference

    //         if (Helper::is_token_valid($guestId, $token, $error)) {
    //             return $next($request);
    //         } else {
    //             return response()->json($error, 401);
    //         }
    //     }

    //     // ✅ If no valid method matched
    //     return response()->json([
    //         'success' => 0,
    //         'message' => 'Authentication failed: missing or invalid credentials'
    //     ], 401);
    // }

    // public function handle(Request $request, Closure $next): Response
    // {
    //     // 🔄 Normalize query params into headers
    //     foreach (['token', 'auth-id', 'guest-id'] as $key) {
    //         if ($request->has($key)) {
    //             $request->headers->set($key, $request->input($key));
    //         }
    //     }

    //     // 🔐 Attempt Sanctum authentication first
    //     if ($request->bearerToken()) {
    //         $user = Auth::guard('sanctum')->user();
    //         if ($user) {
    //             $request->headers->set('auth-id', $user->id); // For legacy compatibility
    //             Log::info("Sanctum auth successful for user ID: {$user->id}");
    //             return $next($request);
    //         } else {
    //             Log::warning("Sanctum bearer token provided but user not authenticated.");
    //         }
    //     }

    //     // 🔐 Fallback to admin token-based auth
    //     $token = $request->header('token');
    //     $adminId = $request->header('auth-id');
    //     if ($adminId && $token) {
    //         $error = null;
    //         if (Helper::is_token_valid_admin($adminId, $token, $error)) {
    //             Log::info("Admin token auth successful for admin ID: {$adminId}");
    //             return $next($request);
    //         } else {
    //             Log::warning("Admin token auth failed for admin ID: {$adminId}", ['error' => $error]);
    //             return response()->json($error, 401);
    //         }
    //     }

    //     // 🔐 Fallback to guest token-based auth
    //     $guestId = $request->header('guest-id');
    //     if ($guestId && $token) {
    //         $error = null;
    //         if (Helper::is_token_valid($guestId, $token, $error)) {
    //             Log::info("Guest token auth successful for guest ID: {$guestId}");
    //             return $next($request);
    //         } else {
    //             Log::warning("Guest token auth failed for guest ID: {$guestId}", ['error' => $error]);
    //             return response()->json($error, 401);
    //         }
    //     }

    //     // ❌ No valid auth method matched
    //     Log::error("Authentication failed: no valid credentials provided.", [
    //         'headers' => $request->headers->all(),
    //         'query' => $request->query()
    //     ]);

    //     return response()->json([
    //         'success' => 0,
    //         'message' => 'Authentication failed: missing or invalid credentials'
    //     ], 401);
    // }

    public function handle(Request $request, Closure $next): Response
    {
        // 🔄 Normalize query params into headers
        foreach (['token', 'auth-id', 'guest-id'] as $key) {
            if ($request->has($key)) {
                $request->headers->set($key, $request->input($key));
            }
        }

        $token = $request->bearerToken() ?? $request->header('token');
        $adminId = $request->header('auth-id');
        $guestId = $request->header('guest-id');

        // 🔐 Sanctum Bearer token authentication
        if ($request->bearerToken()) {
            Auth::shouldUse('sanctum');
            $user = Auth::guard('sanctum')->user();
            if ($user) {
                $request->setUserResolver(function () use ($user) {
                    return $user;
                });

                $request->headers->set('auth-id', $user->id); // For legacy compatibility
                // Log::info("Sanctum auth successful for user ID: {$user->id}");
                return $next($request);
            } else {
                // ⚠️ IMPORTANT: Force Sanctum guard

                Log::warning("Sanctum token provided but user not authenticated.");
            }
        }

        // 🔐 Fallback to legacy admin token
        if ($adminId && $token) {
            $error = null;
            if (Helper::is_token_valid_admin($adminId, $token, $error)) {
                // Log::info("Legacy admin token auth successful for admin ID: {$adminId}");
                return $next($request);
            } else {
                Log::warning("Legacy admin token auth failed for admin ID: {$adminId}", ['error' => $error]);
                return response()->json($error, 401);
            }
        }

        // 🔐 Fallback to legacy guest token
        if ($guestId && $token) {
            $error = null;
            if (Helper::is_token_valid($guestId, $token, $error)) {
                Log::info("Legacy guest token auth successful for guest ID: {$guestId}");
                return $next($request);
            } else {
                Log::warning("Legacy guest token auth failed for guest ID: {$guestId}", ['error' => $error]);
                return response()->json($error, 401);
            }
        }

        // ❌ No valid auth method matched
        Log::error("Authentication failed: no valid credentials provided.", [
            'headers' => $request->headers->all(),
            'query' => $request->query()
        ]);

        return response()->json([
            'success' => 0,
            'message' => 'Authentication failed: missing or invalid credentials'
        ], 401);
    }
}
