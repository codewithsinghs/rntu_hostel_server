<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\PermissionRegistrar;

class CheckUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Always refresh roles/permissions for real-time checks
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Not logged in
        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized. Please log in first.',
                'code'    => 401,
            ], 401);
        }

        // Auto-detect required permission from route if not provided
        if (!$permission) {
            $routeName = $request->route()->getName();

            if ($routeName) {
                // convention: route names = "resource.action"
                $permission = $routeName;
            } else {
                // fallback: generate permission key from URI + method
                $segments   = explode('/', trim($request->path(), '/'));
                $resource   = $segments[0] ?? 'unknown';
                $action     = strtolower($request->method()); // GET, POST, PUT, DELETE
                $permission = $resource . '.' . $action;
            }
        }

        // Check user permission (roles inherit permissions)
        if ($user->can($permission)) {
            return $next($request);
        }

        // Forbidden response
        return response()->json([
            'status'   => 'error',
            'message'  => 'Forbidden. You do not have the required permission.',
            'code'     => 403,
            'required' => $permission,
        ], 403);
    }
}
