<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Mine\MyResource;

class MimeResource extends MyResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'name'=> $this->name
        ];
    }
}
