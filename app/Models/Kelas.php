<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = "kelas";

    protected $fillable = ["id_prodi", "nama_kelas"];

    // Ngedeklarasiin relasi bahwa di tb-kelas ini make foreignKey dari id_prodi dari tb-prodi
    // biar bisa make eloquent
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    // buat relasi untuk ngasih tau ke laravel bahwa id_kelas ini dipake di tb-mahasiswa, jadi gaperlu join join lagi
    // biar bisa make eloquent
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_kelas');
    }

}


