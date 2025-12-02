<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;


Route::get('/', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('about' , ["nama" => "Ikhlasul Amal"]);
});


// User buka /admin → Route terdeteksi → Controller dipanggil → View direturn → Halaman tampil
Route::get('/admin', [AdminController::class, 'showMahasiswa']);

Route::get('/admin/mahasiswa/tambah', [AdminController::class, 'TambahMahasiswa'])->name('mahasiswa.form');
Route::post('/admin/mahasiswa/tambah', [AdminController::class, 'storeMahasiswa'])->name('mahasiswa.store');

// AJAX untuk dynamic dropdown
Route::get('/get-prodi/{id_jurusan}', [AdminController::class, 'getProdi']);
Route::get('/get-kelas/{id_prodi}', [AdminController::class, 'getKelas']);

