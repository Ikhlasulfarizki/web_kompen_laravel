<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = "prodi";
    protected $fillable = ["id_jurusan" , "nama_prodi"];

    // Ngedeklarasiin relasi bahwa di tb-prodi ini make foreignKey dari id_jurusan dari tb-jurusan
    // biar bisa make eloquent
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan');
    }

    // buat relasi untuk ngasih tau ke laravel bahwa id_prodi ini dipake di tb-kelas, jadi gaperlu join join lagi
    // biar bisa make eloquent
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_prodi');
    }

}
