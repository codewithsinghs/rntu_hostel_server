<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    // public function handle(Request $request, Closure $next, $role)
    // {
    //     $user = $request->user();

    //     // If not authenticated or does not have the required role, redirect to login
    //     if (!$user || !$user->roles->contains('name', $role)) {
    //         return redirect()->route('login');
    //     }

    //     return $next($request);
    // }

    // public function handle($request, Closure $next, ...$roles)
    // {
    //     if (!$request->user() || !$request->user()->hasAnyRole($roles)) {
    //         return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    //     }

    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next, ...$roles)
    {
          Log::info('ApiAuth incoming headers', $request->headers->all());
        $token = $request->bearerToken(); // token sent via Authorization header

        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = User::whereHas('tokens', function ($q) use ($token) {
            $q->where('token', hash('sha256', $token));
        })->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Check role
        if (!empty($roles) && !$user->hasAnyRole($roles)) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $request->merge(['api_user' => $user]);
        return $next($request);
    }
}
