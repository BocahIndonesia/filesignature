<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManipulateAPIRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->headers->get('Accept')){
            $request->headers->set('Accept', 'application/json');
        }
        if(!$request->headers->get('Content-Type')){
            $request->headers->set('Content-Type', 'application/json');
        }
        return $next($request);
    }
}
