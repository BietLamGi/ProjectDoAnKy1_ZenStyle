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
            'email.required' => 'Please enter your email.',
            'email.email' => 'Email not recognized.',
            'password.required' => 'Please enter your password.',
        ]);

        $loggedIn = Auth::attempt([
            'Email' => $credentials['email'],
            'password' => $credentials['password'],
            'IsActive' => 1,
        ]);

        if (!$loggedIn) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email or password is incorrect.');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // ROLE 1 = ADMIN
        if ((int) $user->RoleID === 1) {
            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'Welcome Admin!');
        }

        // ROLE 2 = RECEPTIONIST
        if ((int) $user->RoleID === 2) {
            return redirect()
                ->route('receptionist.dashboard')
                ->with('success', 'Login successful.');
        }

        // ROLE 3 = STAFF
        if ((int) $user->RoleID === 3) {
            return redirect()
                ->route('staff.dashboard')
                ->with('success', 'Login successful.');
        }

        // ROLE 4 = CUSTOMER
        return redirect()
            ->route('home')
            ->with('success', 'Login successful.');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}