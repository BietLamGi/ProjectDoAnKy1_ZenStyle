<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    //
    public function index()
{
    $user = Auth::user();

    $customer = Customer::where('UserID', $user->UserID)->first();

    return view('profile.index', compact('user', 'customer'));
}

    public function edit() {
        $user = auth()->user();
        $customer = Customer::where('UserID', $user->UserID)->first();
        return view('profile.edit', compact('user', 'customer'));
    }

    public function update(Request $request)
{
     $user = Auth::user();

    $customer = Customer::where('UserID', $user->UserID)->first();

    $request->validate([
        'username' => [
            'required',
            'string',
            'max:100',
        ],
    
        'fullname' => [
            'required',
            'string',
            'max:100'
        ],

        'email' => [
            'required',
            'email',
            'max:100',
            'unique:User,Email,' . $user->UserID . ',UserID',
        ],

        'phone' => [
            'required',
            'string',
            'max:20'
        ],

        'dob' => [
            'nullable',
            'date'
        ],
    ], [
        'username.required' => 'Please enter your username.',
        'fullname.required' => 'Please enter your full name.',
        'email.required' => 'Please enter your email.',
        'email.email' => 'Please enter a valid email address.',
        'phone.required' => 'Please enter your phone number.',
    ]);

    // update user
    $user->Username = $request->username;
    $user->Email = $request->email;

    $user->save();

    // Update Customer
if ($customer) {

    $customer->FullName = $request->fullname;
    $customer->Phone = $request->phone;
    $customer->Email = $request->email;

    $customer->save();

} else {

    Customer::create([
        'UserID' => $user->UserID,
        'FullName' => $request->fullname,
        'Phone' => $request->phone,
        'Email' => $request->email,
        'DOB' => $request->dob,
        'Allergies' => null,
        'Notes' => null,
        'LoyaltyPoints' => 0,
        'MembershipTier' => 'Normal',
    ]);
}

    return redirect()
        ->route('profile')
        ->with('success', 'Profile updated successfully.');
}

    

public function updatePassword(Request $request)
{
    $validated = $request->validate([
        'current_password' => ['required'],
        'new_password' => ['required', 'string', 'min:6', 'confirmed'],
    ], [
        'current_password.required' => 'Please enter your current password.',
        'new_password.required' => 'Please enter a new password.',
        'new_password.min' => 'Password must be at least 6 characters.',
        'new_password.confirmed' => 'Password confirmation does not match.',
    ]);

    $user = Auth::user();

    if (!Hash::check($validated['current_password'], $user->PasswordHash)) {
    return back()->with(
        'password_error',
        'Current password is incorrect.'
    );
}

$user->PasswordHash = Hash::make($validated['new_password']);
$user->save();

return back()->with(
    'password_success',
    'Password changed successfully.'
);
}
}
