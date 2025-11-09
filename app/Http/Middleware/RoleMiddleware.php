<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($role === 'admin' && $user->role_id != 1) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        if ($role === 'student' && $user->role_id != 2) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}
