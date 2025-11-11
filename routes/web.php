<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    HomeController,
    UserController,
    StudentController,
    BuildingController,
    FloorController,
    RoomController,
    RuleController,
    ServiceController,
    RoomRegistrationController,
    ContractController,
    StatisticController,
    RoleController
};

/*
|--------------------------------------------------------------------------
| Web Routes - Quản lý Ký túc xá (Single Admin Version)
|--------------------------------------------------------------------------
|
| Cấu trúc hệ thống:
| 1️⃣ Public routes (login, register, home)
| 2️⃣ Admin routes - chỉ cho admin@gmail.com
| 3️⃣ Student routes - dành cho sinh viên
|
*/

// ======================
// 🏠 GIAO DIỆN CHUNG
// ======================
Route::get('/', [HomeController::class, 'index'])->name('home');

// Đăng nhập / đăng ký
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// ======================
// 🧑‍💼 ADMIN AREA (chỉ email admin@gmail.com)
// ======================
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');

    // Quản lý tài khoản
    Route::resource('users', UserController::class);

    // Quản lý sinh viên
    Route::resource('students', StudentController::class);

    // Quản lý tòa & tầng
    Route::resource('buildings', BuildingController::class);
    Route::resource('floors', FloorController::class);

    // Quản lý phòng
    Route::resource('rooms', RoomController::class);

    // Quản lý nội quy
    Route::resource('rules', RuleController::class);

    // Quản lý dịch vụ
    Route::resource('services', ServiceController::class);

    // Quản lý đăng ký phòng
    Route::get('registrations', [RoomRegistrationController::class, 'index'])->name('registrations.index');
    Route::get('registrations/{id}', [RoomRegistrationController::class, 'show'])->name('registrations.show');
    Route::post('registrations/{id}/update-status/{status}', [RoomRegistrationController::class, 'updateStatus'])->name('registrations.updateStatus');
    Route::delete('registrations/{id}', [RoomRegistrationController::class, 'destroy'])->name('registrations.destroy');

    // Quản lý hợp đồng
    Route::resource('contracts', ContractController::class);

    // Thống kê
    Route::get('statistics', [StatisticController::class, 'index'])->name('statistics.index');

    // Phân quyền (tùy chọn, có thể bỏ)
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
});


// ======================
// 🎓 STUDENT AREA (mọi user khác admin)
// ======================
Route::middleware(['auth'])->prefix('student')->group(function () {

    // Dashboard sinh viên
    Route::get('/dashboard', [HomeController::class, 'studentDashboard'])->name('student.dashboard');

    // Đăng ký phòng
    Route::get('/registrations', [RoomRegistrationController::class, 'studentIndex'])->name('student.registrations');
    Route::get('/registrations/create', [RoomRegistrationController::class, 'studentCreate'])->name('student.registrations.create');
    Route::post('/registrations/store', [RoomRegistrationController::class, 'studentStore'])->name('student.registrations.store');

    // Xem thông tin cá nhân
    Route::get('/profile', [StudentController::class, 'profile'])->name('student.profile');
});
