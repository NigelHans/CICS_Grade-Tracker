<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/^[A-Za-z0-9._%+-]+@g\.batstate-u\.edu\.ph$/'
            ],
            'password' => 'required'
        ], [
            'email.regex' => 'You must use a valid BatState-U GSuite email.'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect('/dashboard');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid login credentials']);
    }
}
