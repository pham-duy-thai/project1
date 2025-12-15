<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DormitoryController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ViolationController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Redirect root to dashboard or login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Serve storage files (for Windows/Laragon compatibility)
// This route handles file serving when symbolic links don't work properly
Route::get('/storage/{path}', function ($path) {
    // Prevent directory traversal attacks
    $path = str_replace('..', '', $path);
    $path = ltrim($path, '/');
    
    $filePath = storage_path('app/public/' . $path);
    
    // Ensure the file is within the public storage directory
    $publicPath = storage_path('app/public');
    $realFilePath = realpath($filePath);
    $realPublicPath = realpath($publicPath);
    
    if (!$realFilePath || !$realPublicPath) {
        abort(404);
    }
    
    // Check if file is within public storage directory
    if (strpos($realFilePath, $realPublicPath) !== 0) {
        abort(403, 'Access denied');
    }
    
    if (!file_exists($realFilePath) || !is_file($realFilePath)) {
        abort(404);
    }
    
    $mimeType = mime_content_type($realFilePath) ?: 'application/octet-stream';
    $fileName = basename($realFilePath);
    
    return response()->file($realFilePath, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*')->name('storage.serve');

// Dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Student Routes (Admin only for CRUD)
    Route::middleware('can:manage-users')->group(function () {
        Route::resource('students', StudentController::class)->except(['show']);
    });
    // Show student detail - available for all authenticated users
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    
    // Student's own pages
    Route::get('/my-profile', [StudentController::class, 'myProfile'])->name('students.my-profile');
    Route::get('/my-room', [StudentController::class, 'myRoom'])->name('students.my-room');
    Route::get('/my-invoices', [StudentController::class, 'myInvoices'])->name('students.my-invoices');
    Route::get('/my-payments', [StudentController::class, 'myPayments'])->name('students.my-payments');
    Route::get('/my-maintenance', [StudentController::class, 'myMaintenance'])->name('students.my-maintenance');
    
    // Room & Building Management (Admin only)
    Route::middleware('can:manage-rooms')->group(function () {
        Route::resource('dormitories', DormitoryController::class);
        Route::resource('buildings', BuildingController::class);
        Route::resource('rooms', RoomController::class);
        Route::resource('room-types', RoomTypeController::class);
        Route::get('/rooms/{room}/availability', [RoomController::class, 'checkAvailability'])->name('rooms.availability');
    });
    
    // Contract Management (Admin can manage all, Students can only view their own)
    Route::middleware('can:manage-contracts')->group(function () {
        Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
        Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
        Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');
        Route::get('/contracts/{contract}/edit', [ContractController::class, 'edit'])->name('contracts.edit');
        Route::put('/contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');
        Route::delete('/contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy');
        Route::post('/contracts/{contract}/sign', [ContractController::class, 'sign'])->name('contracts.sign');
        Route::post('/contracts/{contract}/terminate', [ContractController::class, 'terminate'])->name('contracts.terminate');
        Route::post('/contracts/{contract}/renew', [ContractController::class, 'renew'])->name('contracts.renew');
    });
    // View contract detail - students can view their own
    Route::get('/contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');
    
    // Student room registration (students register for themselves)
    Route::get('/register-room', [ContractController::class, 'registerRoom'])->name('contracts.register-room');
    Route::post('/register-room', [ContractController::class, 'storeRegistration'])->name('contracts.store-registration');
    // Student's own contracts list
    Route::get('/my-contracts', [ContractController::class, 'myContracts'])->name('contracts.my-contracts');
    
    // Invoice Management
    Route::resource('invoices', InvoiceController::class);
    Route::post('/invoices/{invoice}/generate', [InvoiceController::class, 'generate'])->name('invoices.generate');
    Route::post('/invoices/bulk-generate', [InvoiceController::class, 'bulkGenerate'])->name('invoices.bulk-generate');
    
    // Payment Management
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create/{invoice}', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
    Route::post('/invoices/{invoice}/pay', [PaymentController::class, 'pay'])->name('payments.pay');
    Route::patch('/payments/{payment}/confirm-cash', [PaymentController::class, 'confirmCash'])->name('payments.confirm-cash');
    Route::delete('/payments/{payment}/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');
    Route::get('/payment/vnpay/callback', [PaymentController::class, 'vnpayCallback'])->name('payments.vnpay.callback');
    
    // Maintenance Request Management
    Route::resource('maintenance', MaintenanceRequestController::class);
    Route::post('/maintenance/{maintenance}/assign', [MaintenanceRequestController::class, 'assign'])->name('maintenance.assign');
    Route::post('/maintenance/{maintenance}/approve', [MaintenanceRequestController::class, 'approve'])->name('maintenance.approve');
    Route::post('/maintenance/{maintenance}/start', [MaintenanceRequestController::class, 'start'])->name('maintenance.start');
    Route::post('/maintenance/{maintenance}/process', [MaintenanceRequestController::class, 'process'])->name('maintenance.process');
    Route::post('/maintenance/{maintenance}/complete', [MaintenanceRequestController::class, 'complete'])->name('maintenance.complete');
    Route::post('/maintenance/{maintenance}/reject', [MaintenanceRequestController::class, 'reject'])->name('maintenance.reject');
    
    // Announcement Management
    Route::resource('announcements', AnnouncementController::class);
    Route::post('/announcements/{announcement}/publish', [AnnouncementController::class, 'publish'])->name('announcements.publish');
    Route::post('/announcements/{announcement}/unpublish', [AnnouncementController::class, 'unpublish'])->name('announcements.unpublish');
    Route::get('/announcements/public/list', [AnnouncementController::class, 'publicList'])->name('announcements.public');
    
    // Violation Management (Admin only)
    Route::middleware('can:manage-violations')->group(function () {
        Route::resource('violations', ViolationController::class);
        Route::post('/violations/{violation}/confirm', [ViolationController::class, 'confirm'])->name('violations.confirm');
        Route::post('/violations/{violation}/resolve', [ViolationController::class, 'resolve'])->name('violations.resolve');
        Route::put('/violations/{violation}/mark-paid', [ViolationController::class, 'markPaid'])->name('violations.mark-paid');
        Route::put('/violations/{violation}/waive-fine', [ViolationController::class, 'waive'])->name('violations.waive-fine');
        Route::post('/violations/{violation}/waive', [ViolationController::class, 'waive'])->name('violations.waive');
    });

    // Rule Management (Admin can CRUD, Students can view and acknowledge)
    Route::get('/rules', [RuleController::class, 'index'])->name('rules.index');
    Route::middleware('can:manage-users')->group(function () {
        Route::get('/rules/create', [RuleController::class, 'create'])->name('rules.create');
        Route::post('/rules', [RuleController::class, 'store'])->name('rules.store');
        Route::get('/rules/{rule}/edit', [RuleController::class, 'edit'])->name('rules.edit');
        Route::put('/rules/{rule}', [RuleController::class, 'update'])->name('rules.update');
        Route::delete('/rules/{rule}', [RuleController::class, 'destroy'])->name('rules.destroy');
    });
    Route::get('/rules/{rule}', [RuleController::class, 'show'])->name('rules.show');
    Route::post('/rules/{rule}/acknowledge', [RuleController::class, 'acknowledge'])->name('rules.acknowledge');

    // Service Management (Admin can CRUD, Students can view and register)
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/my-services', [ServiceController::class, 'myRegistrations'])->name('services.my-registrations');
    Route::post('/service-registrations/{registration}/cancel', [ServiceController::class, 'cancel'])->name('service-registrations.cancel');
    Route::middleware('can:manage-users')->group(function () {
        Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
        Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
        Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
        Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
        Route::get('/services/{service}/registrations', [ServiceController::class, 'registrations'])->name('services.registrations');
        Route::post('/service-registrations/{registration}/approve', [ServiceController::class, 'approve'])->name('service-registrations.approve');
    });
    Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
    Route::post('/services/{service}/register', [ServiceController::class, 'register'])->name('services.register');

    // User Account Management (Admin only)
    Route::middleware('can:manage-users')->group(function () {
        \App::bind('users.controller', function () { return \App\Http\Controllers\UsersController::class; });
        Route::resource('users', \App\Http\Controllers\UsersController::class)->except(['show']);
        Route::post('/users/{user}/toggle', [\App\Http\Controllers\UsersController::class, 'toggleActive'])->name('users.toggle');
    });
});

// Payment Gateway Callbacks (Public routes)
Route::get('/payment/return', [PaymentController::class, 'vnpayReturn'])->name('payment.return');
Route::post('/payment/ipn', [PaymentController::class, 'vnpayIPN'])->name('payment.ipn');

// Debug route - remove after testing
Route::get('/debug-maintenance/{id}', function($id) {
    $req = \App\Models\MaintenanceRequest::with(['student.user'])->findOrFail($id);
    
    return response()->json([
        'request_code' => $req->request_code,
        'student_id' => $req->student_id,
        'has_student_relationship' => $req->student ? true : false,
        'student' => $req->student ? [
            'id' => $req->student->id,
            'full_name' => $req->student->full_name,
            'student_code' => $req->student->student_code,
            'user_name' => $req->student->user ? $req->student->user->name : null,
        ] : null,
    ], 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});

require __DIR__.'/auth.php';
