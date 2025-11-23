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
    StudentRoomRegistrationController,
    AdminRoomRegistrationController,
    ContractController,
    StatisticController,
    RoleController
};


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
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'adminDashboard'])->name('dashboard');

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

    // Quản lý đăng ký phòng (ADMIN)
    Route::get('/registrations', [AdminRoomRegistrationController::class, 'index'])
        ->name('registrations.index');
    Route::get('/registrations/{registration}', [AdminRoomRegistrationController::class, 'show'])
        ->name('registrations.show');
    Route::patch('/registrations/{registration}/approve', [AdminRoomRegistrationController::class, 'approve'])
        ->name('registrations.approve');
    Route::patch('/registrations/{registration}/reject', [AdminRoomRegistrationController::class, 'reject'])
        ->name('registrations.reject');

    // Quản lý hợp đồng
    Route::resource('contracts', ContractController::class);

    // Thống kê
    Route::get('/statistics', [StatisticController::class, 'index'])->name('statistics.index');

    // Phân quyền (tùy chọn, có thể bỏ)
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
});


// ======================
// 🎓 STUDENT AREA (mọi user khác admin)
// ======================
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {

    // Dashboard sinh viên
    Route::get('/dashboard', [HomeController::class, 'studentDashboard'])->name('dashboard');

    // Đăng ký phòng (STUDENT)
    Route::get('/registrations', [StudentRoomRegistrationController::class, 'index'])
        ->name('registrations.index');
    Route::get('/registrations/create', [StudentRoomRegistrationController::class, 'create'])
        ->name('registrations.create');
    Route::post('/registrations', [StudentRoomRegistrationController::class, 'store'])
        ->name('registrations.store');

    // Xem thông tin cá nhân
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
});
