<?php

namespace App\Traits;

trait ResponseHelperTrait
{
    public function successResponse($data = [], $message = 'Operation successful', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'errors'  => null,
        ], $code);
    }

    public function errorResponse($message = 'Something went wrong', $errors = [], $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => null,
            'errors'  => $errors,
        ], $code);
    }
}
