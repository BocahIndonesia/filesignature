<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Extension;
use App\Http\Resources\{ExtensionResource, BaseCollection};
use App\Mine\MyController;

class ExtensionController extends MyController
{
    protected $model= Extension::class;
    protected $resource= ExtensionResource::class;
    protected $validation= [
        'name'=> 'required|string'
    ];

    public function list(Request $request){
        $this->validatePagination($request);
        return new BaseCollection(Extension::with('mime', 'signatures')->paginate($request->get('pagination')));
    }
}
