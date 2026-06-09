<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next, string $minRole = 'viewer')
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // All three roles can access the admin area
        if (! $user->isAdmin()) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return $next($request);
    }
}
