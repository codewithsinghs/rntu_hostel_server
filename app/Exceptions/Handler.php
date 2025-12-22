<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }



    public function render($request, Throwable $exception)
    {
        // If API request
        if ($request->expectsJson()) {

            // Validation errors
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $exception->errors()
                ], 422);
            }

            // Authentication errors
            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated'
                ], 401);
            }

            // Database errors (hide SQL details)
            if ($exception instanceof QueryException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'A system error occurred, please try again later.'
                ], 500);
            }

            // Fallback for other errors
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage() ?: 'Something went wrong!'
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
