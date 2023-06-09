<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Mine\MyResource;

class UserResource extends MyResource
{
    public function toArray(Request $request): array
    {
        return [
            'name'=> $this->name,
            'email'=> $this->email,
            // 'token'=>$this->createToken('secret')->plainTextToken
        ];
    }
}
