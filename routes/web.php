<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;


Route::get('/', function () {
    return view('login');
});

Route::get('/profile', function () {
    return view('profile');
});



// Login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Setelah login
Route::middleware('auth')->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'showMahasiswa'])
    ->name('admin.dashboard')
    ->middleware('role:1');

    Route::get('/dosen/dashboard', function () {
        return "Dashboard Dosen";
    })->name('dosen.dashboard')->middleware('role:2');

    Route::get('/mahasiswa/dashboard', function () {
        return "Dashboard Mahasiswa";
    })->name('mahasiswa.dashboard')->middleware('role:3');

    Route::get('/admin/tambah', [AdminController::class, 'TambahMahasiswa'])->name('mahasiswa.form');
    Route::post('/admin/tambah', [AdminController::class, 'storeMahasiswa'])->name('mahasiswa.store');

    Route::get('/admin/edit/{id}', [AdminController::class, 'editMahasiswa'])
        ->name('mahasiswa.edit');
    Route::post('/admin/update/{id}', [AdminController::class, 'updateMahasiswa'])
        ->name('mahasiswa.update');
    Route::delete('/admin/dashboard/{id}', [AdminController::class, 'deleteMahasiswa'])
        ->name('mahasiswa.delete');

        
    // AJAX untuk dynamic dropdown
    Route::get('/get-prodi/{id_jurusan}', [AdminController::class, 'getProdi']);
    Route::get('/get-kelas/{id_prodi}', [AdminController::class, 'getKelas']);
});
    // User buka /admin → Route terdeteksi → Controller dipanggil → View direturn → Halaman tampil
