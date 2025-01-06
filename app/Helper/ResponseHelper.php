<?php

namespace App\Helper;

class ResponseHelper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Function to return success response
     * @param string $status 
     * @param string $message 
     * @param array $data
     * @param integer $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($status = "success", $message = null, $data = [], $statusCode = 200)
    {
        return response()->json([
            "status" => $status,
            "message" => $message,
            "data" => $data,
        ], $statusCode);
    }

    /**
     * Function to return error response
     * @param string $status 
     * @param string $message 
     * @param integer $statusCode
     */
    public static function error($status = "error", $message = null, $statusCode = 200)
    {
        return response()->json([
            "status" => $status,
            "message" => $message,
        ], $statusCode);
    }
}
