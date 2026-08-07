<?php

namespace App\Http\Middleware;

use App\Models\Business;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAgent
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-Api-Key');

        if (! $apiKey) {
            return response()->json(['message' => 'Missing API key.'], 401);
        }

        $business = Business::where('api_key', $apiKey)->first();

        if (! $business) {
            return response()->json(['message' => 'Invalid API key.'], 401);
        }

        $request->attributes->set('business', $business);

        return $next($request);
    }
}
