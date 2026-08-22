<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerFrontend
{
    public function handle(Request $request, Closure $next): Response
    {
        // Guest → được phép truy cập Customer Frontend
        if (!auth()->check()) {
            return $next($request);
        }

        // Đã đăng nhập → kiểm tra RoleID
        $roleId = (int) auth()->user()->RoleID;

        // Customer → được phép
        if ($roleId === 4) {
            return $next($request);
        }

        // Admin
        if ($roleId === 1) {
            return redirect()->route('admin.dashboard');
        }

        // Receptionist
        if ($roleId === 2) {
            return redirect()->route('receptionist.dashboard');
        }

        // Staff
        if ($roleId === 3) {
            return redirect()->route('staff.work-schedule.index');
        }

        // Role không hợp lệ
        abort(403);
    }
}