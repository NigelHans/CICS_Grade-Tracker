<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        $enrollments = Enrollment::with('course')
            ->where('student_id', $user->id)
            ->get();

        return view('dashboard', compact('user', 'enrollments'));
    }
}