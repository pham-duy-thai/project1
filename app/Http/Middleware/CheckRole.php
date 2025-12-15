<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }
        
        $user = Auth::user();
        
        // If no roles specified, just check if authenticated
        if (empty($roles)) {
            return $next($request);
        }
        
        // Check if user has one of the required roles
        foreach ($roles as $role) {
            if ($this->hasRole($user, $role)) {
                return $next($request);
            }
        }
        
        // User doesn't have required role
        return redirect()->route('dashboard')
            ->with('error', 'Bạn không có quyền truy cập trang này.');
    }
    
    /**
     * Check if user has a specific role
     */
    protected function hasRole($user, string $role): bool
    {
        return match(strtolower($role)) {
            'admin' => $user->isAdmin(),
            'student' => $user->isStudent(),
            default => false
        };
    }
}

