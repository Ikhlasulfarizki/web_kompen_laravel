<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\KompenController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\ProdiController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TeknisiController;
use App\Http\Controllers\LoginController;


Route::get('/', function () {
    return view('login');
});

// Login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Setelah login
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::middleware('role:1')->group(function () {
        // Dashboard Admin
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // AJAX untuk dynamic dropdown - harus didefinisikan sebelum resource routes
        Route::get('/admin/get-prodi/{id_jurusan}', [MahasiswaController::class, 'getProdi'])->name('admin.get-prodi');
        Route::get('/admin/get-kelas/{id_prodi}', [MahasiswaController::class, 'getKelas'])->name('admin.get-kelas');
        Route::get('/admin/kelas/get-prodi/{id_jurusan}', [KelasController::class, 'getProdi'])->name('admin.kelas.get-prodi');
        Route::get('/admin/dosen/get-prodi/{id_jurusan}', [DosenController::class, 'getProdi'])->name('admin.dosen.get-prodi');

        // Mahasiswa
        Route::resource('admin/mahasiswa', MahasiswaController::class, ['as' => 'admin']);

        // Dosen
        Route::resource('admin/dosen', DosenController::class, ['as' => 'admin']);

        // Teknisi
        Route::resource('admin/teknisi', TeknisiController::class, ['as' => 'admin']);

        // Kompen
        Route::resource('admin/kompen', KompenController::class, ['as' => 'admin']);

        // Master Data
        Route::resource('admin/jurusan', JurusanController::class, ['as' => 'admin']);
        Route::resource('admin/prodi', ProdiController::class, ['as' => 'admin']);
        Route::resource('admin/kelas', KelasController::class, ['as' => 'admin']);

        // Users
        Route::resource('admin/users', UserController::class, ['as' => 'admin']);

        // Profile Admin
        Route::get('/admin/profile', [ProfileController::class, 'show'])->name('admin.profile.show');
        Route::get('/admin/profile/edit', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::put('/admin/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    });

    // Dosen Routes
    Route::middleware('role:2')->group(function () {
        Route::get('/dosen/dashboard', [\App\Http\Controllers\Dosen\DashboardController::class, 'index'])->name('dosen.dashboard');

        // Profile Dosen
        Route::get('/dosen/profile', [\App\Http\Controllers\Dosen\ProfileController::class, 'show'])->name('dosen.profile.show');
        Route::get('/dosen/profile/edit', [\App\Http\Controllers\Dosen\ProfileController::class, 'edit'])->name('dosen.profile.edit');
        Route::put('/dosen/profile', [\App\Http\Controllers\Dosen\ProfileController::class, 'update'])->name('dosen.profile.update');

        // Tasks
        Route::resource('dosen/tasks', \App\Http\Controllers\Dosen\TaskController::class, ['as' => 'dosen']);

        // Export & Bulk Actions
        Route::get('/dosen/tasks/export/excel', [\App\Http\Controllers\Dosen\TaskController::class, 'export'])->name('dosen.tasks.export');
        Route::post('/dosen/tasks/bulk-delete', [\App\Http\Controllers\Dosen\TaskController::class, 'bulkDelete'])->name('dosen.tasks.bulk-delete');
        Route::post('/dosen/tasks/bulk-update-status', [\App\Http\Controllers\Dosen\TaskController::class, 'bulkUpdateStatus'])->name('dosen.tasks.bulk-update-status');

        // Participant Management
        Route::post('/dosen/participants/{participant}/accept',
            [\App\Http\Controllers\Dosen\TaskController::class, 'acceptParticipant']
        )->name('dosen.participants.accept');

        Route::post('/dosen/participants/{participant}/reject',
            [\App\Http\Controllers\Dosen\TaskController::class, 'rejectParticipant']
        )->name('dosen.participants.reject');

        Route::post('/dosen/participants/{participant}/update-status',
            [\App\Http\Controllers\Dosen\TaskController::class, 'updateParticipantStatus']
        )->name('dosen.participants.update-status');

        // Attendance Management
        Route::get('/dosen/tasks/{task}/attendance', [\App\Http\Controllers\Dosen\AttendanceController::class, 'index'])->name('dosen.attendance.index');
        Route::post('/dosen/attendance/{participant}/check-in', [\App\Http\Controllers\Dosen\AttendanceController::class, 'checkIn'])->name('dosen.attendance.check-in');
        Route::post('/dosen/attendance/{attendance}/check-out', [\App\Http\Controllers\Dosen\AttendanceController::class, 'checkOut'])->name('dosen.attendance.check-out');
        Route::get('/dosen/tasks/{task}/attendance/report', [\App\Http\Controllers\Dosen\AttendanceController::class, 'report'])->name('dosen.attendance.report');
        Route::delete('/dosen/attendance/{attendance}', [\App\Http\Controllers\Dosen\AttendanceController::class, 'delete'])->name('dosen.attendance.delete');
    });

    // Mahasiswa Routes
    Route::middleware('role:3')->group(function () {
        Route::get('/mahasiswa/dashboard', function () {
            return "Dashboard Mahasiswa";
        })->name('mahasiswa.dashboard');
    });

    // Teknisi Routes
    Route::middleware('role:4')->group(function () {
        Route::get('/teknisi/dashboard', function () {
            return "Dashboard Teknisi";
        })->name('teknisi.dashboard');
    });
});
