<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturerAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.lecturer-login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/^[A-Za-z0-9._%+-]+@g\.batstate-u\.edu\.ph$/'
            ],
            'password' => 'required'
        ], [
            'email.regex' => 'You must use a valid BatState-U GSuite email.'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/lecturer/dashboard');
        }

        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Invalid login credentials']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
