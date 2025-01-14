<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    public static $wrap = null;

    public function withResponse($request, $response)
    {
        $response->header('X-API-Version', '2.0');
    }
}
