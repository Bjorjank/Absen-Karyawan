<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Models\Attendance;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\PermissionController;

use App\Models\Employee;


Route::get('/', function () {
    return view('welcome');
});


// // Route group for "izin"
// Route::get('/izin/urusan', [IzinController::class, 'urusan'])->name('izin.urusan');
// Route::get('/izin/sakit', [IzinController::class, 'sakit'])->name('izin.sakit');
// Route::get('/izin/cuti', [IzinController::class, 'cuti'])->name('izin.cuti');

// Route::post('/izin/urusan', [IzinController::class, 'urusan'])->name('izin.urusan');
// Route::post('/izin/sakit', [IzinController::class, 'sakit'])->name('izin.sakit');

// // routes/web.php


Route::middleware(['auth'])->group(function () {
    Route::get('/izin', [PermissionController::class, 'create'])->name('izin.create'); // Form izin karyawan
    Route::post('/izin', [PermissionController::class, 'store'])->name('izin.store'); // Simpan permohonan izin karyawan
});


    Route::get('/admin/izin', [PermissionController::class, 'index'])->name('admin.permissions.index'); // Dashboard admin untuk izin
    Route::put('/admin/izin/{id}/update', [PermissionController::class, 'update'])->name('admin.permissions.update'); // Update status izin oleh admin





Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// absen
Route::middleware(['auth'])->group(function () {
    
    Route::get('/attendance', function () {
        $attendances = Attendance::all();
        return view('attendance.index', ['attendances' => $attendances]);
    });

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');
    // filter && pdf
    Route::get('/attendance/filter', [AttendanceController::class, 'filter'])->name('attendance.filter');
    Route::get('/attendance/download-pdf', [AttendanceController::class, 'downloadPDF'])->name('attendance.downloadPDF');

    // hapus
    Route::delete('/attendance/{id}', [AdminController::class, 'destroy'])->name('attendance.destroy');

});

// daftar pekerja
Route::get('/employees', function () {
    $employees = Employee::all();
    return view('employees.index', ['employees' => $employees]);
});
Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::post('/attendance/check-in', [EmployeeController::class, 'checkIn'])->name('attendance.checkin');

// admin
    
    Route::get('/admin/dashboard', [AdminController::class, 'showAllAttendance'])->name('admin.dashboard');
    Route::get('/admin/izin', [AdminController::class, 'izin'])->name('admin.izin');
    Route::get('/Admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::post('/admin/confirm', [AdminController::class, 'confirmAttendance'])->name('admin.confirm');

// izin


require __DIR__.'/auth.php';
