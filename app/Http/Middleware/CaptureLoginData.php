<?php

namespace App\Http\Middleware;

use App\Models\LoginAttempt;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;
use Symfony\Component\HttpFoundation\Response;

class CaptureLoginData {

    public function handle(Request $request, Closure $next): Response {

        $response = $next($request);

        if (Auth::check() && $request->is('login')) {
            $position = Location::get($request->ip());
            
            LoginAttempt::create([
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'latitude' => $position->latitude ?? null,
                'longitude' => $position->longitude ?? null,
                'location' => $position->cityName ?? null,
            ]);
        }

        return $response;
    }
}
