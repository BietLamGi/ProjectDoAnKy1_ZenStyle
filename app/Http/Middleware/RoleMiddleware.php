<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Chưa đăng nhập
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // RoleID của user hiện tại
        $roleId = (int) auth()->user()->RoleID;

        // Chuyển roles về integer
        $roles = array_map('intval', $roles);

        // Không có quyền
        if (!in_array($roleId, $roles, true)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}