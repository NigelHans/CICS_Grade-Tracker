<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Enrollment;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        $enrollments = Enrollment::with('course')
            ->where('student_id', $user->id)
            ->get();

        return view('student.profile', compact('user', 'enrollments'));
    }

    public function edit()
    {
        $user = Auth::user();
        
        return view('student.edit-profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('profile.index')
            ->with('success', 'Profile updated successfully.');
    }
}