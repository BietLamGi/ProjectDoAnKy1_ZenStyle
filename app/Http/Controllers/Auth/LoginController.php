<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $loggedIn = Auth::attempt([
            'Email' => $credentials['email'],
            'password' => $credentials['password'],
            'IsActive' => 1,
        ]);

        if (!$loggedIn) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email hoặc mật khẩu không đúng.');
        }

        $request->session()->regenerate();

        return redirect()
            ->route('home')
            ->with('success', 'Đăng nhập thành công.');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}