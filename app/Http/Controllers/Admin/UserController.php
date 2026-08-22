<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
        return view('admin.users.create');
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'Username' => [
            'required',
            'string',
            'max:50',
            'unique:User,Username',
        ],

        'Email' => [
            'nullable',
            'email',
            'max:100',
            'unique:User,Email',
        ],

        'Phone' => [
            'nullable',
            'string',
            'max:20',
            'unique:User,Phone',
        ],

        'Password' => [
            'required',
            'string',
            'min:6',
            'confirmed',
        ],

        // Chỉ cho tạo Receptionist hoặc Staff
        'RoleID' => [
            'required',
            'in:2,3',
        ],

        'DateBirth' => [
            'nullable',
            'date',
        ],
    ]);

    // Tự động xác định Position theo RoleID
    if ((int) $validated['RoleID'] === 2) {
        $position = 'Receptionist';
    } else {
        $position = 'Staff';
    }

    User::create([
        'Username' => $validated['Username'],

        'PasswordHash' => Hash::make($validated['Password']),

        'Email' => $validated['Email'] ?? null,

        'Phone' => $validated['Phone'] ?? null,

        'RoleID' => $validated['RoleID'],

        'IsActive' => true,

        'DateBirth' => $validated['DateBirth'] ?? null,

        'Position' => $position,
    ]);

    return redirect()
        ->route('users.index')
        ->with('success', 'Staff account created successfully.');
}

    public function show($id)
    {
        $user = User::with('role')->findOrFail($id);

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
            'Username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('User', 'Username')
                    ->ignore($user->UserID, 'UserID'),
            ],

            'Email' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('User', 'Email')
                    ->ignore($user->UserID, 'UserID'),
            ],

            'Phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('User', 'Phone')
                    ->ignore($user->UserID, 'UserID'),
            ],

            'Password' => [
                'nullable',
                'string',
                'min:6',
                'confirmed',
            ],

            'RoleID' => [
                'required',
                'in:1,2,3,4',
            ],

            'Position' => [
                'nullable',
                'in:Receptionist,Staff',
            ],

            'DateBirth' => [
                'nullable',
                'date',
            ],
        ]);

        $roleID = (int) $validated['RoleID'];

        // Admin / Customer không có Position
        if ($roleID === 1 || $roleID === 4) {
            $position = null;
        }

        // Receptionist
        elseif ($roleID === 2) {
            $position = 'Receptionist';
        }

        // Staff
        elseif ($roleID === 3) {
            $position = 'Staff';
        }

        $user->Username = $validated['Username'];
        $user->Email = $validated['Email'] ?? null;
        $user->Phone = $validated['Phone'] ?? null;
        $user->RoleID = $roleID;
        $user->DateBirth = $validated['DateBirth'] ?? null;
        $user->Position = $position;

        if (!empty($validated['Password'])) {
            $user->PasswordHash = Hash::make($validated['Password']);
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'User account updated successfully.');
    }

    public function destroy($id)
{
    $user = User::findOrFail($id);

    // Nếu là Admin thì phải đảm bảo hệ thống
    // luôn còn ít nhất 1 Admin
    if ((int) $user->RoleID === 1) {

        $adminCount = User::where('RoleID', 1)->count();

        if ($adminCount <= 1) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'The last administrator account cannot be deleted.'
                );
        }
    }

    // Staff / Receptionist hoặc Admin khi còn nhiều hơn 1 Admin
    $user->delete();

    return redirect()
        ->route('users.index')
        ->with(
            'success',
            'User account deleted successfully.'
        );
}
}