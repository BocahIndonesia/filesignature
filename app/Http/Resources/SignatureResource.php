<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Mine\MyResource;

class SignatureResource extends MyResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'offset'=> $this->offset,
            'hex'=> $this->hex
        ];
    }
}
