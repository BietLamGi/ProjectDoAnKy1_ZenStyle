<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Hiển thị form đăng ký tài khoản khách hàng.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Xử lý đăng ký tài khoản mới.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:User,Email'],            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required'     => 'Please enter your full name.',
            'email.required'    => 'Please enter your email.',
            'email.email'       => 'The email adress is invalid.',
            'email.unique'      => 'This email is already registered.',
            'password.required' => 'Please enter your password.',
            'password.min'      => 'Password must be at least 6 characters long.',
            'password.confirmed'=> 'Password do not match.',
        ]);

        $user = User::create([
    'Username'     => $validated['name'],
    'Email'        => $validated['email'],
    'PasswordHash' => Hash::make($validated['password']),
    'RoleID'       => 4,   // nếu Customer có RoleID = 4
    'IsActive'     => 1,
]);

        Auth::login($user);

        return redirect()->route('home')
            ->with('success', 'Account created successfully! Welcome to ZenStyle.');
    }
}
