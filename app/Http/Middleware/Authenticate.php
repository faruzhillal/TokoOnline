<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Check if the request expects JSON
        if ($request->expectsJson()) {
            return null;
        }

        // Check if the request URI contains 'backend' or 'frontend'
        if ($request->is('backend/*')) {
            return route('backend.login');
        } elseif ($request->is('frontend/*')) { // Akan digunakan jika frontend telah tersedia
            return route('frontend.login');     // Akan digunakan jika frontend telah tersedia
        }

        // Default redirect if none of the above match
        return route('backend.login'); // Ganti ke frontend jika telah tersedia
    }
}