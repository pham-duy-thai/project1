<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($role === 'admin' && $user->role_id != 1) {
            abort(403, 'Chỉ admin mới được truy cập.');
        }

        if ($role === 'student' && $user->role_id != 2) {
            abort(403, 'Chỉ sinh viên mới được truy cập.');
        }

        return $next($request);
    }
}
