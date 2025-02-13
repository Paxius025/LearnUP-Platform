<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventBackAfterLogout
{
    public function handle(Request $request, Closure $next)
    {
        // If the user is not logged in and tries to go back, redirect to the homepage
        if (!Auth::check()) {
            return redirect('/');
        }

        $response = $next($request);
        
        // Prevent using the back button after logout
        return $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }
}