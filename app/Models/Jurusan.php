<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = "jurusan";
    protected $fillable = ["nama_jurusan"];

    // Relasi ke Prodis
    public function prodis()
    {
        return $this->hasMany(Prodi::class, 'id_jurusan');
    }

    // buat relasi untuk ngasih tau ke laravel bahwa id_jurusan ini dipake di tb-prodi, jadi gaperlu join join lagi
    // biar bisa make eloquent
    public function prodi()
    {
        return $this->hasMany(Prodi::class, 'id_jurusan');
    }

}
