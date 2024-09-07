<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'email' => $this['user']->email,
            'id' => $this['user']->id,
            'name' => $this['user']->name,
            'token' => $this['token'],
        ];
    }
}
