<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    protected function success($message = 'Success', $data = [], $code = 200 ): JsonResponse {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data
        ], $code);
    }

    protected function error($message = 'Something went wrong', $errors = [], $code = 422 ): JsonResponse {
        return response()->json([
            'status'  => false,
            'message' => $message,
            'errors'  => $errors
        ], $code);
    }

  

    // public static function success($message, $data = [], $code = 200)
    // {
    //     return response()->json([
    //         'status'  => true,
    //         'message' => $message,
    //         'data'    => $data
    //     ], $code);
    // }

    // public static function error($message, $errors = [], $code = 422)
    // {
    //     return response()->json([
    //         'status'  => false,
    //         'message' => $message,
    //         'errors'  => $errors
    //     ], $code);
    // }


}
