<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($message, $data = [], $status = 200)
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data
        ], $status);
    }

    public static function error($message, $status = 400, $errors = [])
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'errors'  => $errors
        ], $status);
    }
}
