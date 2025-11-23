<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');

Route::get('/dashboard', function () {
    return 'Logged in successfully!';
});



Route::get('/', function () {
    return view('welcome');
});
