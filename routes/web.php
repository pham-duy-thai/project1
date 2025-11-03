<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\FloorController;

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// AUTH
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ✅ ADMIN routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/layout2', [HomeController::class, 'admin'])->middleware(['web', 'auth', 'role:admin'])->name('layout2.theme');
    Route::resource('users', UserController::class);
    Route::resource('students', StudentController::class);

    Route::resource('buildings', BuildingController::class);
    Route::resource('floors', FloorController::class);
});

// ✅ STUDENT routes
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/layout1', [HomeController::class, 'student'])->name('layout1.app');
});
