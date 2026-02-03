<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('/offline', 'offline')->name('offline');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Public route for QR code scanning (mobile)
Route::get('/attendance/qr-scan', [AttendanceController::class, 'qrScan'])->name('attendance.qr-scan');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/check-in', [AttendanceController::class, 'checkIn'])->name('check-in');
        Route::post('/check-in', [AttendanceController::class, 'storeCheckIn'])->name('check-in');
        Route::post('/check-out', [AttendanceController::class, 'storeCheckOut'])->name('check-out');
        Route::get('/history', [AttendanceController::class, 'history'])->name('history');
        Route::get('/check-status', [AttendanceController::class, 'checkStatus'])->name('check-status');
    });
    
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class)->middleware('can:manage-employees');
    
    Route::resource('departments', \App\Http\Controllers\DepartmentController::class)->middleware('can:manage-employees');
    
    Route::prefix('reports')->name('reports.')->middleware('can:manage-employees')->group(function () {
        Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('index');
        Route::get('/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('export');
    });
    
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SettingController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\SettingController::class, 'updateSettings'])->name('update');
        Route::post('/holidays', [\App\Http\Controllers\SettingController::class, 'storeHoliday'])->name('holidays.store');
        Route::put('/holidays/{holiday}', [\App\Http\Controllers\SettingController::class, 'updateHoliday'])->name('holidays.update');
        Route::delete('/holidays/{holiday}', [\App\Http\Controllers\SettingController::class, 'destroyHoliday'])->name('holidays.destroy');
    });

    Route::prefix('leave-requests')->name('leave-requests.')->group(function () {
        Route::get('/', [\App\Http\Controllers\LeaveRequestController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\LeaveRequestController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\LeaveRequestController::class, 'store'])->name('store');
        Route::post('/{leaveRequest}/approve', [\App\Http\Controllers\LeaveRequestController::class, 'approve'])->name('approve')->middleware('can:manage-employees');
        Route::post('/{leaveRequest}/reject', [\App\Http\Controllers\LeaveRequestController::class, 'reject'])->name('reject')->middleware('can:manage-employees');
    });

    Route::prefix('overtime-requests')->name('overtime-requests.')->group(function () {
        Route::get('/', [\App\Http\Controllers\OvertimeRequestController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\OvertimeRequestController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\OvertimeRequestController::class, 'store'])->name('store');
        Route::get('/{overtimeRequest}', [\App\Http\Controllers\OvertimeRequestController::class, 'show'])->name('show');
        Route::get('/{overtimeRequest}/edit', [\App\Http\Controllers\OvertimeRequestController::class, 'edit'])->name('edit');
        Route::put('/{overtimeRequest}', [\App\Http\Controllers\OvertimeRequestController::class, 'update'])->name('update');
        Route::delete('/{overtimeRequest}', [\App\Http\Controllers\OvertimeRequestController::class, 'destroy'])->name('destroy');
        Route::post('/{overtimeRequest}/approve', [\App\Http\Controllers\OvertimeRequestController::class, 'approve'])->name('approve')->middleware('can:manage-employees');
        Route::post('/{overtimeRequest}/reject', [\App\Http\Controllers\OvertimeRequestController::class, 'reject'])->name('reject')->middleware('can:manage-employees');
    });

    Route::prefix('payroll')->name('payroll.')->group(function () {
        Route::get('/my-payroll', [\App\Http\Controllers\PayrollController::class, 'myPayroll'])->name('my-payroll');
        Route::get('/', [\App\Http\Controllers\PayrollController::class, 'index'])->name('index')->middleware('can:manage-employees');
        Route::get('/export', [\App\Http\Controllers\PayrollController::class, 'export'])->name('export')->middleware('can:manage-employees');
        Route::post('/generate', [\App\Http\Controllers\PayrollController::class, 'generate'])->name('generate')->middleware('can:manage-employees');
        Route::get('/{payroll}', [\App\Http\Controllers\PayrollController::class, 'show'])->name('show')->middleware('can:manage-employees');
        Route::post('/{payroll}/update-status', [\App\Http\Controllers\PayrollController::class, 'updateStatus'])->name('update-status')->middleware('can:manage-employees');
    });

    // Quản lý hồ sơ nhân viên và hợp đồng (chỉ admin)
    Route::prefix('employee-profiles')->name('employee-profiles.')->group(function () {
        Route::get('/', [\App\Http\Controllers\EmployeeProfileController::class, 'index'])->name('index');
        Route::get('/{employee}/edit', [\App\Http\Controllers\EmployeeProfileController::class, 'edit'])->name('edit');
        Route::put('/{employee}', [\App\Http\Controllers\EmployeeProfileController::class, 'update'])->name('update');
        Route::get('/{employee}', [\App\Http\Controllers\EmployeeProfileController::class, 'show'])->name('show');
    });

    Route::resource('contracts', \App\Http\Controllers\ContractController::class);
});
