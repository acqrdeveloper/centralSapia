<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = null;
        if ($request->hasHeader('X-Api-Token')) {
            if ($request->header('X-Api-Token') != '') {
                $response = $next($request);
            } else {
                $response = response()->json(["status" => false, "data" => "Error, procedimiento no autorizado"], 401);;
            }
        } else {
            $response = response()->json(["status" => false, "data" => "Error, procedimiento no autorizado"], 401);;
        }
        return $response;
    }
}
