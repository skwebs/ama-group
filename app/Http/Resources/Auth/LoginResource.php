<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\BaseApiResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'created_at' => $this->created_at->format('d-m-Y H:i:s'),
                'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
            ],
            'auth_token' => $this->toekn,
        ];
    }
}
