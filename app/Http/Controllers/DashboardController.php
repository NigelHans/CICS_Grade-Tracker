<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        $enrollments = Enrollment::with('course')
            ->where('student_id', $user->id)
            ->get();

        return view('dashboard', compact('user', 'enrollments'));
    }
}