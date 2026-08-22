<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
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
            'email' => ['required', 'string', 'email', 'max:100', 'unique:User,Email'],
            'phone' => ['required', 'string', 'max:15'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required'     => 'Please enter your full name.',
            'email.required'    => 'Please enter your email.',
            'email.email'       => 'The email adress is invalid.',
            'email.unique'      => 'This email is already registered.',
            'phone.required'    => 'Please enter your phone number.',
            'phone.max'         => 'Phone number must be at most 15 characters long.',
            'password.required' => 'Please enter your password.',
            'password.min'      => 'Password must be at least 6 characters long.',
            'password.confirmed'=> 'Password do not match.',
        ]);

      $user = User::create([
    'Username'     => $validated['name'],
    'Email'        => $validated['email'],
    'Phone'        => $validated['phone'],
    'PasswordHash' => Hash::make($validated['password']),
    'RoleID'       => 4,
    'IsActive'     => 1,
]);

        $customer = Customer::where('Phone', $validated['phone'])->first();
        if ($customer) {
            $customer->UserID = $user->UserID;
            $customer->save();
        } else {
            // Nếu không tìm thấy khách hàng với số điện thoại đã nhập, tạo một khách hàng mới
            $customer = Customer::create([
                'UserID' => $user->UserID,
                'FullName' => $validated['name'],
                'Phone'  => $validated['phone'],
                'Email'  => $validated['email'],
                'LoyaltyPoints' => 0,
                'MembershipTier' => 'Normal'
            ]);
        }
        Auth::login($user);

        return redirect()->route('home')
            ->with('success', 'Account created successfully! Welcome to ZenStyle.');
    }
}
