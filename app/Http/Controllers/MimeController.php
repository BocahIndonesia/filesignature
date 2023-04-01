<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mime;
use App\Http\Resources\{MimeResource};
use App\Mine\MyController;

class MimeController extends MyController
{
    protected $model= Mime::class;
    protected $resource= MimeResource::class;
    protected $validation= [
        'name'=> 'required|string'
    ];
}
