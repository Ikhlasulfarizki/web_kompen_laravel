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
        Route::get('/dosen/dashboard', function () {
            return "Dashboard Dosen";
        })->name('dosen.dashboard');
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
