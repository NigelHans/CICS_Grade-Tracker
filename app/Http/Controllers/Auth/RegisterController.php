<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'regex:/^[A-Za-z0-9._%+-]+@g\.batstate-u\.edu\.ph$/'
            ],
            'sr_code' => 'required|string|unique:users,sr_code',
            'program' => 'required|string',
            'year' => 'required|string',
            'campus' => 'required|string',
            'password' => 'required|string|min:6|confirmed'
        ], [
            'email.regex' => 'You must use a valid BatState-U GSuite email.',
            'email.unique' => 'This email is already registered.',
            'sr_code.unique' => 'This SR-Code is already registered.',
            'password.confirmed' => 'Password confirmation does not match.'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'sr_code' => $validated['sr_code'],
            'program' => $validated['program'],
            'year' => $validated['year'],
            'campus' => $validated['campus'],
            'password' => Hash::make($validated['password']),
            'role' => 'student'
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Registration successful! Welcome!');
    }
}