<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Signature;
use App\Http\Resources\{SignatureResource};
use App\Mine\MyController;

class SignatureController extends MyController
{
    protected $model= Signature::class;
    protected $resource= SignatureResource::class;
    protected $validation= [
        'offset'=> 'required|integer',
        'hex'=> 'required|regex:/^[a-f0-9]+$/i'
    ];
}
