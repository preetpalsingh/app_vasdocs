<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenValidationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        
        if (!Auth::guard('api')->check()) {
            return response()->json(['message' => 'Unauthorizedddd'], 401);
        } 

        return $next($request);
    }
}