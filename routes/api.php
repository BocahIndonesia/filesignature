<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ManipulateAPIRequest;
use App\Http\Controllers\{ServiceController, ExtensionController, MimeController, SignatureController};

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

function basicRoute($controller, $prefix):void{
    Route::controller($controller)->prefix($prefix)->group(function(){
        Route::get('/count', 'count');
        Route::get('/', 'list');
        Route::post('/', 'create');
        Route::get('/{id}', 'find');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'delete');
        // Route::put('/{id}', 'replace'); //gk butuh ini
    });
}

Route::middleware(ManipulateAPIRequest::class)->prefix('v1')->group(function(){
    basicRoute(ExtensionController::class, 'extensions');
    basicRoute(MimeController::class, 'mimes');
    basicRoute(SignatureController::class, 'signatures');

    Route::controller(ServiceController::class)->prefix('services')->group(function(){
        Route::post('/scanner', 'scan');
        Route::post('/extension-validator', 'validateExtension');
        Route::post('/mime-validator', 'validateMime');
    });
});
