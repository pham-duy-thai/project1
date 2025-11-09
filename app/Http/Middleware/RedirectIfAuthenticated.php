<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (Auth::check()) {
            $role = Auth::user()->role_id;
            return $role == 1
                ? redirect()->route('admin.dashboard')
                : redirect()->route('student.dashboard');
        }

        return $next($request);
    }
}
