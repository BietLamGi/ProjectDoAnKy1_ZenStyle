<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('UserID', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::whereIn('RoleName', [
            'Receptionist',
            'Service Staff'
        ])->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'FullName' => 'required|string|max:255',
        'Username' => 'required|string|max:100|unique:User,Username',
        'Email' => 'nullable|email|max:255',
        'Phone' => 'nullable|string|max:20',
        'Password' => 'required|string|min:6|confirmed',
        'RoleID' => 'required|in:1,2',
        'IsActive' => 'nullable|boolean',
        'DateBirth' => 'nullable|date',
        'Position' => 'nullable|string|max:100',
        'StaffType' => 'nullable|in:receptionist,service',
    ]);

    User::create([
        'FullName' => $validated['FullName'],
        'Username' => $validated['Username'],
        'PasswordHash' => Hash::make($validated['Password']),
        'Email' => $validated['Email'] ?? null,
        'Phone' => $validated['Phone'] ?? null,
        'RoleID' => $validated['RoleID'],
        'IsActive' => $request->boolean('IsActive'),
        'DateBirth' => $validated['DateBirth'] ?? null,
        'Position' => $validated['Position'] ?? null,
        'StaffType' => $validated['StaffType'] ?? null,
    ]);

    return redirect()
        ->route('users.index')
        ->with('success', 'Staff account created successfully.');
}

    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'Username' => 'required|string|max:100|unique:User,Username,' . $user->UserID . ',UserID',
            'Email' => 'nullable|email|max:255',
            'Phone' => 'nullable|string|max:20',
            'Password' => 'nullable|string|min:6|confirmed',
            'RoleID' => 'required|in:1,2',
            'IsActive' => 'nullable|boolean',
            'DateBirth' => 'nullable|date',
            'Position' => 'nullable|string|max:100',
            'StaffType' => 'nullable|in:receptionist,service',
        ]);

        $user->Username = $validated['Username'];
        $user->Email = $validated['Email'] ?? null;
        $user->Phone = $validated['Phone'] ?? null;
        $user->RoleID = $validated['RoleID'];
        $user->IsActive = $request->boolean('IsActive');
        $user->DateBirth = $validated['DateBirth'] ?? null;
        $user->Position = $validated['Position'] ?? null;
        $user->StaffType = $validated['StaffType'] ?? null;

        if (!empty($validated['Password'])) {
            $user->PasswordHash = Hash::make($validated['Password']);
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'Staff account updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            $adminCount = User::where('RoleID', 1)->count();

            if ($adminCount <= 1) {
                return redirect()
                    ->route('users.index')
                    ->with('error', 'The only administrator account cannot be deleted.');
            }
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Staff account deleted successfully.');
    }
}