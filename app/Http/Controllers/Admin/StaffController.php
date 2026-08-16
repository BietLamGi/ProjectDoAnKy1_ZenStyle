<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('RoleID', '!=', 0)
            ->where('RoleID', '!=', 1);

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('FullName', 'like', "%{$search}%")
                    ->orWhere('Username', 'like', "%{$search}%")
                    ->orWhere('Phone', 'like', "%{$search}%")
                    ->orWhere('Email', 'like', "%{$search}%");
            });
        }

        $staff = $query
            ->orderBy('UserID', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.staff.index', compact('staff'));
    }

    public function show($id)
    {
        $staff = User::findOrFail($id);

        return view('admin.staff.show', compact('staff'));
    }
}