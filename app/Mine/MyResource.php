<?php

namespace App\Mine;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyResource extends JsonResource
{
    public function with(Request $request){
        return [
            'message'=> 'resource'
        ];
    }
}
